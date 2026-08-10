<?php

namespace App\Http\Controllers;

use App\Facades\MailSender;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Services\MailSenderService;
use App\Traits\CartTrait;
use App\Traits\GetGlobalInformationTrait;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\BasicPayment\app\Services\PaymentMethodService;
use Modules\Coupon\app\Models\Coupon;
use Modules\Coupon\app\Models\CouponHistory;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderBillingAddress;
use Modules\Order\app\Models\OrderPaymentDetails;
use Modules\Order\app\Models\OrderShippingAddress;
use Modules\Order\app\Models\TransactionHistory;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Services\ProductService;
use Modules\Shipping\app\Models\ShippingRule;
use Modules\Shipping\app\Models\ShippingSetting;
use Throwable;

class CheckoutController extends Controller
{
    use GetGlobalInformationTrait, CartTrait;

    /**
     * @var mixed
     */
    protected $setting;

    /**
     * @param ProductService $productService
     */
    public function __construct(protected ProductService $productService)
    {
        $this->setting = cache('setting');

        if (!$this->setting->can_guest_checkout) {
            $this->middleware('auth');
        }
    }

    /**
     * @return mixed
     */
    public function checkout()
    {
        $cart_contents = $this->getSessionCart();

        if (count($cart_contents) <= 0) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('Cart is empty'),
            ]);
        }

        $this->checkPriceUpdateAvailability($cart_contents);

        $this->checkStockAvailablity($cart_contents);

        $codAvailable = $this->isCashOnDeliveryAvailable($cart_contents);

        $subTotal = $this->getCartSubTotal();

        $tax = $this->getCartTotalTax();

        $discount = session('coupon_discount') ?? 0;

        $total = $this->getCartTotal();

        session()->put('tax_amount', $tax);

        session()->put('amount', $subTotal);

        $countries = Country::all();

        $shippingSetting = ShippingSetting::first();

        $direction = $shippingSetting->sort_shipping_direction;

        $shippingRules = ShippingRule::whereStatus(1)->orderBy('from', $direction)->get();

        $addresses = auth()->user()->addresses ?? [];

        $loginView = auth('web')->check() ? 'website.cart.checkout' : 'website.cart.guest-checkout';

        return view($loginView, compact('addresses', 'shippingSetting', 'cart_contents', 'countries', 'shippingRules', 'subTotal', 'tax', 'discount', 'total', 'codAvailable'));
    }

    /**
     * @param Request $request
     */
    public function getCheckoutSummary(Request $request)
    {
        $request->validate([
            'method'              => ['required', 'string'],
            'shipping'            => ['required', 'integer', 'exists:shipping_rules,id,status,1'],
            'shipping_address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
        ], [
            'method.required'              => __('Payment method is required'),
            'shipping.required'            => __('Shipping method is required'),
            'shipping.exists'              => __('Selected shipping method is not available'),
            'shipping_address_id.required' => __('Shipping address is required'),
            'shipping_address_id.exists'   => __('Invalid shipping address selected'),
        ]);

        $method   = $request->get('method');
        $shipping = $request->get('shipping');
        $address  = $request->get('shipping_address_id');

        $shippingAddress = Address::where('user_id', auth()->id())->findOrFail($address);

        $shipping = ShippingRule::whereStatus(1)->findOrFail($shipping);

        if ($item = $shipping->items) {
            $cityMismatch    = !empty($item->city_id) && !in_array($shippingAddress->city_id, (array) $item->city_id, true);
            $stateMismatch   = !empty($item->state_id) && !in_array($shippingAddress->state_id, (array) $item->state_id, true);
            $countryMismatch = !empty($item->country_id) && !in_array($shippingAddress->country_id, (array) $item->country_id, true);

            if ($cityMismatch || $stateMismatch || $countryMismatch) {
                return response()->json([
                    'message' => __('No valid shipping method found for this address. Choose another shipping method or change your address.'),
                ], 422);
            }
        }

        $data['cart_contents'] = $this->getSessionCart();

        $cartGroupBy = collect($data['cart_contents'] ?? [])->groupBy('vendor_name');

        $data['countVendors'] = count($cartGroupBy->keys());

        $countVendors = isset($data['countVendors']) && $data['countVendors'] > 0 ? $data['countVendors'] : 1;

        $singleShippingCost = session()->has('free_shipping') && session('free_shipping') ? 0 : $shipping->price;
        $shippingCost       = $singleShippingCost * $countVendors;
        $subTotal           = $this->getCartSubTotal();
        $tax                = $this->getCartTotalTax();

        $couponDiscount             = $this->getCouponDiscount();
        $totalAmount                = $this->getCartTotal() + $shippingCost;
        $calculateAmount            = $this->calculatePayableCharge($totalAmount, $method);
        $payableAmount              = $calculateAmount->payable_with_charge;
        $payableAmountWithoutCharge = $calculateAmount->payable_amount;
        $gatewayCharge              = $calculateAmount->gateway_charge;
        $payableCurrency            = getSessionCurrency();

        session()->put('tax_amount', $tax);
        session()->put('amount', $subTotal);

        $data = [
            'subTotal'               => $subTotal,
            'tax'                    => $tax,
            'shipping_cost'          => $shippingCost,
            'singleShippingCost'     => $singleShippingCost,
            'discount'               => $couponDiscount,
            'payable_without_charge' => $payableAmountWithoutCharge,
            'payable'                => $payableAmount,
            'payable_currency'       => $payableCurrency,
            'gateway_charge'         => $gatewayCharge,
        ];

        session()->put('checkout_summary', $data);

        return response()->json([
            'products'    => view('website.cart.billing_summery', [
                'cartGroupBy'   => $cartGroupBy,
                'shipping_cost' => $singleShippingCost,
            ])->render(),
            'calculation' => view('website.cart.price_calculation', $data)->render(),
        ]);
    }

    /**
     * @param Request $request
     */
    public function placeOrder(OrderStoreRequest $request)
    {
        $cart_contents = $this->getSessionCart();

        if (count($cart_contents ?? []) <= 0) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('Cart is empty'),
            ]);
        }

        if ($request->payment_method == 'hand_cash' && !$this->isCashOnDeliveryAvailable($cart_contents)) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('Cash on delivery is not available for some of the products in cart, check cart list.'),
            ]);
        }

        $this->checkPriceUpdateAvailability($cart_contents);

        $this->checkStockAvailablity($cart_contents);

        $data['isGuestCheckout'] = (bool) $request->get('guest_checkout', false);

        if ($data['isGuestCheckout'] && !$this->setting->can_guest_checkout && !auth('web')->check()) {
            $data['userId'] = 0;
        } else {
            $data['userId'] = auth()->id();
        }

        $hasCoupon = session()->has('coupon_code') && session()->has('coupon_id');

        $data['checkoutSummary']          = $this->retrieveCheckoutSummary();
        $data['requestData']              = (object) $request->all();
        $data['couponCode']               = session('coupon_code');
        $data['discount']                 = $data['checkoutSummary']->discount;
        $data['tax']                      = $data['checkoutSummary']->tax;
        $data['deliveryCharge']           = $data['checkoutSummary']->shipping_cost;
        $data['gatewayCharge']            = $data['checkoutSummary']->gateway_charge;
        $data['payableAmountWithoutRate'] = $data['checkoutSummary']->payable_without_charge;
        $data['payableCurrency']          = $data['checkoutSummary']->payable_currency;
        $data['grandTotal']               = $data['checkoutSummary']->subTotal;
        $data['totalPayable']             = $data['checkoutSummary']->payable;

        $roundZero = round(floatval(0), 1);

        $isFreePurchase = (round(floatval($data['payableAmountWithoutRate']), 1) == $roundZero) && (round(floatval($data['deliveryCharge']), 1) == $roundZero);

        $paymentDetails = $this->storePaymentDetails($data, $isFreePurchase);

        $data['isFreePurchase'] = $isFreePurchase;

        $order = $this->storeOrders($data, $paymentDetails);

        if ($order && $hasCoupon) {
            $couponId       = session('coupon_id');
            $couponCode     = session('coupon_code');
            $discountAmount = session('coupon_discount');

            $this->storeCouponHistory(
                $couponId,
                $couponCode,
                $discountAmount,
                $order,
                $order->user_id
            );
        }

        PaymentMethodService::removeSessions();

        session()->put('just_ordered', true);

        if (!auth('web')->check() && $this->setting->can_guest_checkout && $request->filled('create_account') && $request->filled('account_password')) {
            $this->createUser($request);
        }

        if ($order->paymentDetails->payment_method == 'hand_cash') {
            return to_route('website.invoice', ['uuid' => $order->uuid])->with([
                'alert-type' => 'success',
                'message'    => __('Order placed successfully'),
            ]);
        }

        return to_route('website.complete.payment', ['uuid' => $order->uuid])->with([
            'alert-type' => 'success',
            'message'    => __('Order placed successfully'),
        ]);
    }

    public function retrieveCheckoutSummary()
    {
        if (!session('checkout_summary')) {
            throw new HttpResponseException(back()->with([
                'alert-type' => 'error',
                'message'    => __('Checkout summary not found!'),
            ]));
        }

        return (object) session('checkout_summary');
    }

    /**
     * @param $request
     */
    public function storeOrders($data, $orderPaymentDetails)
    {
        $cartGroupByVendor = collect($this->getSessionCart() ?? [])->groupBy('vendor_id');

        $vendorCount = count($cartGroupByVendor->keys());

        $singleShippingCost = $data['checkoutSummary']->singleShippingCost;

        $shippingAddress = $data['isGuestCheckout'] ? $this->makeGuestShippingAddressObject($data['requestData']) : Address::findOr($data['requestData']->shipping_address_id, function () {
            throw new HttpResponseException(back()->with([
                'alert-type' => 'error',
                'message'    => __('Shipping address not found'),
            ]));
        });

        foreach ($cartGroupByVendor as $vendorId => $cartItems) {
            $cartItems = (array) $cartItems->all();

            $vendor = Vendor::findOr($vendorId, function () {
                throw new HttpResponseException(back()->with([
                    'alert-type' => 'error',
                    'message'    => __('Vendor not found'),
                ]));
            });

            $scale = 2;

            $cartTotalTax = '0';
            $cartTotal    = '0';

            foreach ($cartItems as $item) {
                $cartTotalTax = bcadd($cartTotalTax, (string) $item['tax_amount'], $scale);
                $cartTotal    = bcadd($cartTotal, (string) $item['total'], $scale);
            }

            $vendorCount        = max((int) $vendorCount, 1);
            $discountTotal      = (string) $data['discount'];
            $cartCouponDiscount = bcdiv($discountTotal, (string) $vendorCount, $scale);

            $singleShippingCost = (string) $singleShippingCost;
            $gatewayChargeTotal = (string) $data['checkoutSummary']->gateway_charge;
            $gatewayCharge      = bcdiv($gatewayChargeTotal, (string) $vendorCount, $scale);

            $subtotalPlusExtras = bcadd($cartTotal, $singleShippingCost, $scale);
            $subtotalPlusExtras = bcadd($subtotalPlusExtras, $gatewayCharge, $scale);
            $totalAmount        = bcsub($subtotalPlusExtras, $cartCouponDiscount, $scale);

            try {
                DB::beginTransaction();

                $order                           = new Order();
                $order->order_id                 = $this->generateOrderId();
                $order->user_id                  = $data['userId'];
                $order->vendor_id                = $vendor->id;
                $order->note                     = $data['requestData']->note;
                $order->tax                      = $cartTotalTax;
                $order->shipping                 = $singleShippingCost;
                $order->sub_total                = $cartTotal;
                $order->gateway_fee              = $gatewayCharge;
                $order->total_amount             = $totalAmount;
                $order->order_payment_details_id = $orderPaymentDetails->id;
                $order->coupon_code              = $data['couponCode'];
                $order->discount                 = $cartCouponDiscount;
                $order->order_status             = $data['isFreePurchase'] ? OrderStatus::APPROVED->value : OrderStatus::PENDING->value;
                $order->is_guest_order           = $data['isGuestCheckout'] ? 1 : 0;
                $order->save();

                if ($order && $order->paymentDetails->payment_method == 'free') {
                    $this->storeTransactionHistory($order, $orderPaymentDetails->transaction_id);
                }

                $storedShippingAddress = $this->storeShippingAddress($order, $shippingAddress);

                $this->storeBillingAddress($order, $data['requestData'], $storedShippingAddress);

                $this->addProductsToOrder($order, $cartItems);

                $this->generateGTMData($order);

                session()->flash('pixel_payload', [
                    'event' => 'Purchase',
                    'data'  => $this->generateFacebookPixelData($order),
                ]);

                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                logError("Unable to create order", $e);

                throw new HttpResponseException(back()->with([
                    'alert-type' => 'error',
                    'message'    => __('Something went wrong while processing order'),
                ]));
            }

            $this->sendEmailToCustomer($order);
            $this->sendEmailToVendor($order, $vendor);

            notifyAdmin('New order placed #' . $order->order_id, 'New order placed by ' . $storedShippingAddress->name, 'order', route('admin.order', $order->order_id));
        }

        $this->destroyCart();

        return $order;
    }

    /**
     * @param $order
     */
    private function storePaymentDetails($data, $isFreePurchase = false): OrderPaymentDetails
    {
        try {
            $orderDetails                              = new OrderPaymentDetails();
            $orderDetails->coupon_code                 = $data['couponCode'];
            $orderDetails->total_discount              = $data['discount'];
            $orderDetails->payable_amount              = $data['totalPayable'];
            $orderDetails->payable_amount_without_rate = $data['payableAmountWithoutRate'];
            $orderDetails->payable_currency            = $data['payableCurrency'];
            $orderDetails->paid_amount                 = 0;
            $orderDetails->transaction_id              = $isFreePurchase ? uniqid('free_') : null;
            $orderDetails->payment_details             = null;
            $orderDetails->payment_method              = $isFreePurchase ? 'free' : $data['requestData']->payment_method;
            $orderDetails->payment_status              = $isFreePurchase ? PaymentStatus::COMPLETED->value : PaymentStatus::PENDING->value;
            $orderDetails->save();

            return $orderDetails;
        } catch (Throwable $th) {
            logError('Order Payment Details Error', $th);

            throw new HttpResponseException(back()->with([
                'alert-type' => 'error',
                'message'    => __('Something went wrong while processing payment details'),
            ]));
        }
    }

    /**
     * @param  $order
     * @param  $trxId
     * @return mixed
     */
    private function storeTransactionHistory($order, $trxId)
    {
        $newTH                  = new TransactionHistory();
        $newTH->order_id        = $order->id;
        $newTH->user_id         = $order->user_id;
        $newTH->vendor_id       = $order->vendor_id;
        $newTH->payment_method  = $order->paymentDetails->payment_method;
        $newTH->transaction_id  = $trxId;
        $newTH->payment_details = $order->paymentDetails->payment_details;
        $newTH->amount          = $order->paymentDetails->paid_amount;
        $newTH->currency        = $order->paymentDetails->payable_currency;
        $newTH->status          = $order->paymentDetails->payment_status->value;
        $newTH->save();

        return $newTH;
    }

    /**
     * @param  $request
     * @return mixed
     */
    private function makeGuestShippingAddressObject($request)
    {
        $obj                   = new \stdClass();
        $obj->name             = $request->name;
        $obj->email            = $request->email;
        $obj->phone            = $request->phone;
        $obj->zip_code         = $request->zip;
        $obj->address          = $request->address;
        $obj->walk_in_customer = 0;
        $obj->type             = 'home';

        $obj->country = Country::find($request->country_id);
        $obj->state   = State::find($request->state_id);
        $obj->city    = City::find($request->city_id);

        return $obj;
    }

    /**
     * @param $request
     */
    private function storeShippingAddress($order, $shippingAddress)
    {
        $address                   = new OrderShippingAddress();
        $address->order_id         = $order->id;
        $address->user_id          = $order->user_id;
        $address->name             = $shippingAddress->name;
        $address->email            = $shippingAddress->email;
        $address->phone            = $shippingAddress->phone;
        $address->country          = $shippingAddress->country->name;
        $address->state            = $shippingAddress->state->name;
        $address->city             = $shippingAddress->city->name;
        $address->zip_code         = $shippingAddress->zip_code;
        $address->address          = $shippingAddress->address;
        $address->walk_in_customer = $shippingAddress->walk_in_customer;
        $address->type             = $shippingAddress->type;
        $address->save();

        return $address->refresh();
    }

    /**
     * @param $order
     * @param $billingAddress
     * @param bool|OrderShippingAddress $shippingAddress
     */
    private function storeBillingAddress($order, $billingAddress, bool | OrderShippingAddress $shippingAddress = false)
    {
        $address           = new OrderBillingAddress();
        $address->order_id = $order->id;
        $address->user_id  = $order->user_id;
        $address->name     = $billingAddress->name;
        $address->email    = $billingAddress->email;
        $address->phone    = $billingAddress->phone;

        if ($billingAddress->same_as_shipping == 1 && $shippingAddress) {
            $address->country  = $shippingAddress->country;
            $address->state    = $shippingAddress->state;
            $address->city     = $shippingAddress->city;
            $address->zip_code = $shippingAddress->zip_code;
            $address->address  = $shippingAddress->address;
        } else {
            $address->country  = $billingAddress->country;
            $address->state    = $billingAddress->state;
            $address->city     = $billingAddress->city;
            $address->zip_code = $billingAddress->zip;
            $address->address  = $billingAddress->address;
        }

        $address->save();
    }

    private static function generateOrderId()
    {
        return generateInvoiceNumber();
    }

    /**
     * @param $order
     * @param $cartContents
     */
    public function addProductsToOrder($order, $cartContents = null): void
    {
        if ($cartContents == null) {
            $cartContents = $this->getSessionCart();
        }

        foreach ($cartContents as $value) {
            $orderDetails = [
                'user_id'           => $order->user_id,
                'product_id'        => data_get($value, 'id'),
                'vendor_id'         => data_get($value, 'vendor_id'),
                'qty'               => data_get($value, 'qty'),
                'price'             => data_get($value, 'price'),
                'tax_amount'        => data_get($value, 'tax_amount'),
                'total_price'       => data_get($value, 'total'),
                'options'           => data_get($value, 'has_variant') ? data_get($value, 'variant.attribute') : null,
                'product_name'      => data_get($value, 'name'),
                'product_thumbnail' => data_get($value, 'image'),
                'product_sku'       => data_get($value, 'sku'),
                'is_variant'        => data_get($value, 'has_variant'),
                'measurement'       => data_get($value, 'measure_unit'),
                'weight'            => data_get($value, 'weight'),
                'commission_rate'   => getSettings('product_commission_rate'),
                'is_flash_deal'     => data_get($value, 'is_flash_deal'),
            ];

            $order->items()->create($orderDetails);

            $getItem = $order->items()->where(
                [
                    'user_id'     => $order->user_id,
                    'product_id'  => data_get($value, 'id'),
                    'product_sku' => data_get($value, 'sku'),
                    'vendor_id'   => data_get($value, 'vendor_id'),
                    'qty'         => data_get($value, 'qty'),
                ]
            )->first() ?? $order->items()->latest()->first();

            $this->calculateStoreCommission($getItem->total_price, $getItem, $order);
        }
    }

    /**
     * @param $order
     */
    private function sendEmailToCustomer($order)
    {
        try {
            $order->refresh();

            [$subject, $message] = MailSender::fetchEmailTemplate('order_placed', [
                'user_name'        => $order->shippingAddress->name ?? $order->user->name ?? 'Name Missing!',
                'order_id'         => $order->order_id,
                'order_status'     => $order->order_status->getLabel(),
                'amount'           => $order->paymentDetails->payable_amount,
                'amount_currency'  => $order->paymentDetails->payable_currency,
                'payment_method'   => $order->paymentDetails->payment_method,
                'payment_status'   => $order->paymentDetails->payment_status->getLabel(),
                'shipping_address' => $order->shippingAddress->full_address ?? '',
            ]);

            $link = [
                __('INVOICE') . ' #' . ($order->order_id) => route('website.invoice', ['uuid' => $order->uuid]),
                __('COMPLETE PAYMENT')                    => route('website.complete.payment', ['uuid' => $order->uuid]),
            ];

            MailSender::sendMail($order->billingAddress->email, $subject, $message, $link);
        } catch (Exception $e) {
            logError("Unable to send order placed email to {$order?->billingAddress?->email} for #{$order->order_id}", $e);
        }
    }

    /**
     * @param $order
     * @param $vendorIds
     */
    private function sendEmailToVendor($order, $vendor)
    {
        try {
            $order->refresh();

            [$subject, $message] = MailSender::fetchEmailTemplate('order_placed_vendor', [
                'user_name'        => $vendor->shop_name ?? 'Name Missing!',
                'shop_name'        => $vendor->shop_name ?? 'Name Missing!',
                'order_id'         => $order->order_id,
                'order_status'     => $order->order_status->getLabel(),
                'amount'           => $order->paymentDetails->payable_amount,
                'amount_currency'  => $order->paymentDetails->payable_currency,
                'payment_method'   => $order->paymentDetails->payment_method,
                'payment_status'   => $order->paymentDetails->payment_status->getLabel(),
                'shipping_address' => $order->shippingAddress->full_address ?? '',
            ]);

            $link = [
                __('ORDER DETAILS') . ' #' . ($order->order_id) => route('seller.orders.show', ['id' => $order->order_id]),
            ];

            MailSender::sendMail($vendor->email, $subject, $message, $link);
        } catch (Exception $e) {
            logError('Unable to send order placed email to vendors for #{$order->order_id}', $e);
        }
    }

    /**
     * @param $couponId
     * @param $couponCode
     * @param $discountAmount
     * @param $orderId
     * @param $userId
     */
    private function storeCouponHistory(
        $couponId,
        $couponCode,
        $discountAmount,
        $order,
        $userId
    ) {
        try {
            $coupon = Coupon::findOrFail($couponId);

            $newCouponHistory                  = new CouponHistory();
            $newCouponHistory->author_id       = $coupon->author_id;
            $newCouponHistory->order_id        = $order->id;
            $newCouponHistory->user_id         = $userId;
            $newCouponHistory->coupon_code     = $couponCode;
            $newCouponHistory->coupon_id       = $couponId;
            $newCouponHistory->discount_amount = $discountAmount;
            $newCouponHistory->save();

            // Update coupon usage count
            $coupon->increment('used', 1);
        } catch (Exception $e) {
            logError('Unable to store coupon history (' . $couponCode . ')', $e);

            notifyAdmin('Unable to store coupon history', 'Unable to store coupon (' . $couponCode . ') history for order #' . $order->order_id, 'danger', route('admin.order', ['id' => $order->order_id]));
        }

    }

    /**
     * @param $cartContents
     */
    private function checkPriceUpdateAvailability($cartContents)
    {
        foreach ($cartContents as $cartItem) {
            $product = Product::find($cartItem['id']);

            if (!$product) {
                throw new HttpResponseException(to_route('website.cart')->with([
                    'alert-type' => 'error',
                    'message'    => $cartItem['name'] . " - " . __('not found in our database!'),
                ]));
            }

            if ($this->hasPriceChanged($cartItem, $product, $cartItem['sku'])) {
                throw new HttpResponseException(to_route('website.cart')->with([
                    'alert-type' => 'error',
                    'message'    => __('Please update your cart before checkout'),
                ]));
            }
        }
    }

    /**
     * @param $itemPrice
     * @param $itemID
     * @param $newOrderCourse
     * @param $order
     */
    private function calculateStoreCommission($itemPrice, $newOrder, $order)
    {
        $qty  = (string) $newOrder->qty;
        $rate = (string) $newOrder->commission_rate;

        // Commission logic
        $rateFraction     = bcdiv($rate, '100', 6);
        $commissionAmount = bcmul($itemPrice, $rateFraction, 6);
        $amountAfterCut   = bcsub($itemPrice, $commissionAmount, 6);
        $amountAfterCut   = bcround($amountAfterCut, 2);

        $totalCommission = bcmul($amountAfterCut, $qty, 2);

        $newOrder->commission = $totalCommission;

        if ($newOrder->save() && $order->paymentDetails->payment_status == PaymentStatus::COMPLETED && $newOrder?->vendor) {
            saveWalletHistory($newOrder, $order);
        }
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     */
    private function createUser($request)
    {
        $user = null;
        try {
            $user = User::create([
                'name'               => $request->name,
                'email'              => $request->email,
                'phone'              => $request->phone,
                'password'           => Hash::make($request->account_password),
                'verification_token' => Str::random(100),
            ]);

            if (isset($user) && $user) {
                (new MailSenderService)->sendVerifyMailSingleUser($user);
            }

        } catch (Exception $e) {
            logError("Unable to create user from guest order the email is {$request->email}", $e);
        }

        return $user;
    }

    /**
     * @param $order
     */
    private function generateGTMData($order)
    {
        $items = [];

        foreach ($order->items as $item) {
            $name = $item->is_variant == 1 ? $item->product_name . ' | ' . $item->product_sku . ' | ' . $item->options : $item->product_name . ' | ' . $item->product_sku;

            $items[] = [
                'item_id'   => $item->product_id,
                'item_name' => $name,
                'price'     => (float) number_format($item->price, 2, '.', ''),
                'quantity'  => (int) $item->qty,
            ];
        }

        pushToGTM([
            'event'     => 'purchase',
            'user_id'   => auth()->id() ?? 0,
            'user_role' => auth()->check() ? auth()->user()->name : 'guest',
            'language'  => getSessionLanguage(),
            'ecommerce' => [
                'transaction_id' => $order->order_number ?? $order->id,
                'value'          => (float) number_format($order->payable_amount, 2, '.', ''),
                'currency'       => $order->payable_currency ?? 'USD',
                'items'          => $items,
            ],
        ]);
    }

    /**
     * @param  $order
     * @return mixed
     */
    private function generateFacebookPixelData($order)
    {
        $contents = [];

        foreach ($order->items as $item) {
            $contents[] = [
                'id'         => $item->product_sku,
                'quantity'   => (int) $item->qty,
                'item_price' => (float) number_format($item->price, 2, '.', ''),
            ];
        }

        return [
            'contents'     => $contents,
            'content_type' => 'product',
            'value'        => (float) number_format($order->payable_amount, 2, '.', ''),
            'currency'     => $order->payable_currency ?? 'USD',
        ];
    }
}
