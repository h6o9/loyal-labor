<?php

namespace App\Http\Controllers;

use App\Traits\CartTrait;
use App\Traits\GetGlobalInformationTrait;
use Illuminate\Http\Request;
use Modules\Order\app\Models\Order;
use Modules\Product\app\Services\ProductService;
use Modules\Shipping\app\Models\ShippingRule;

class GuestCheckoutController extends Controller
{
    use CartTrait, GetGlobalInformationTrait;

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
     * @param Request $request
     */
    public function getCheckoutSummary(Request $request)
    {
        $request->validate([
            'method'     => ['required', 'string'],
            'shipping'   => ['required', 'integer', 'exists:shipping_rules,id,status,1'],
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'city_id'    => 'required|exists:cities,id',
        ], [
            'method.required'     => __('Payment method is required'),
            'shipping.required'   => __('Shipping method is required'),
            'shipping.exists'     => __('Selected shipping method is not available'),
            'country_id.required' => __('Country is required'),
            'country_id.exists'   => __('Invalid country selected'),
            'state_id.required'   => __('State is required'),
            'state_id.exists'     => __('Invalid state selected'),
            'city_id.required'    => __('City is required'),
            'city_id.exists'      => __('Invalid city selected'),
        ]);

        $method   = $request->get('method');
        $shipping = $request->get('shipping');

        $shipping = ShippingRule::whereStatus(1)->findOrFail($shipping);

        if ($item = $shipping->items) {
            $cityMismatch    = !empty($item->city_id) && !in_array($request->city_id, (array) $item->city_id, true);
            $stateMismatch   = !empty($item->state_id) && !in_array($request->state_id, (array) $item->state_id, true);
            $countryMismatch = !empty($item->country_id) && !in_array($request->country_id, (array) $item->country_id, true);

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
     * @param $uuid
     */
    public function showCompleteOrder($uuid)
    {
        $order = Order::with(['items', 'billingAddress', 'shippingAddress', 'paymentStatusHistory', 'orderStatusHistory'])->whereUuid($uuid)->firstOrFail();

        session()->forget('just_ordered');

        return view('website.guest-order-success', compact('order'));
    }

    /**
     * @param $uuid
     */
    public function showInvoice($orderId)
    {
        $order = Order::with(['items', 'billingAddress', 'shippingAddress', 'paymentStatusHistory', 'orderStatusHistory'])->where('order_id', $orderId)->firstOrFail();

        $view = $order->is_guest_order ? 'website.guest-invoice' : 'user.pages.invoice';

        return view($view, compact('order'));
    }
}
