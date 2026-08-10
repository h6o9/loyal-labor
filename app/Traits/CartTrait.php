<?php

namespace App\Traits;

use App\Models\Cart;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Modules\Product\app\Models\Product;

trait CartTrait
{
    /**
     * @param $guard
     */
    public function isLogged(string $guard = 'web')
    {
        return auth($guard)->check();
    }

    /**
     * @param string $guard
     */
    public function isNotLogged(string $guard = 'web')
    {
        return !$this->isLogged($guard);
    }

    /**
     * @return mixed
     */
    public function authUser($guard = 'web')
    {
        return $this->isLogged($guard) ? auth($guard)->user() : null;
    }

    /**
     * @return mixed
     */
    public function getHasApp()
    {
        return getSettings('has_app');
    }

    public function getRawSessionCart()
    {
        return session()->get('cart');
    }

    public function getSessionCart()
    {
        $this->updateSessionCartFromLoggedUser();

        return session()->has('cart') && session()->get('cart') ? session()->get('cart') : [];
    }

    /**
     * @return mixed
     */
    public function getUserCart()
    {
        if ($this->isLogged()) {
            $cart = Cart::where('user_id', $this->authUser()->id)->first();

            if ($cart) {
                return $cart->items;
            }
        }

        return null;
    }

    /**
     * @return null
     */
    public function updateSessionCartFromLoggedUser()
    {
        if (!$this->getHasApp() || !$this->isLogged()) {
            return;
        }

        $dbCart = Cart::firstOrCreate(
            ['user_id' => $this->authUser()->id],
            ['items' => []]
        );

        if (!$dbCart) {
            return;
        }

        $mergedItems = $this->margeDuplicateCartItems($dbCart);

        session()->put('cart', $mergedItems);

        $dbCart->items = $mergedItems;
        $dbCart->save();
    }

    /**
     * @param $dbCart
     */
    public function margeDuplicateCartItems($dbCart = null)
    {
        $sessionCart = $this->getRawSessionCart();

        $dbItems = $dbCart->items ?? [];

        if (empty($sessionCart)) {
            $combined = $dbItems;
        } else {
            $combined = $sessionCart + $dbItems;
        }

        $seen   = [];
        $merged = [];

        foreach ($combined as $rowid => $item) {
            $itemQty = (int) $item['qty'];

            $variantKey = (!empty($item['has_variant']) && !empty($item['sku'])) ? $item['sku'] : 'no-variant';
            $key        = $item['id'] . '::' . $variantKey;

            if (isset($seen[$key])) {
                $existingRow                       = $seen[$key];
                $merged[$existingRow]['qty']       = (string) ((int) $merged[$existingRow]['qty'] + $itemQty);
                $merged[$existingRow]['sub_total'] = $merged[$existingRow]['qty'] * $merged[$existingRow]['price'];
            } else {
                $seen[$key]        = $rowid;
                $item['qty']       = (string) $itemQty;
                $item['sub_total'] = $itemQty * $item['price'];
                $merged[$rowid]    = $item;
            }
        }

        return $merged;
    }

    /**
     * @param $data
     */
    public function addNewCartRow($data)
    {
        $previousCart = $this->getSessionCart();

        if (isset($data['rowid'])) {
            $previousCart[$data['rowid']] = $data;

            session()->put('cart', $previousCart);

            return $this->getSessionCart();
        }

        return $previousCart;
    }

    /**
     * @param array $data
     */
    public function addNewUserCartRow(array $data)
    {
        if ($this->getHasApp()) {
            $userId = $this->authUser()->id;
            $cart   = Cart::firstOrCreate(['user_id' => $userId]);

            if ($cart) {
                $items                 = $cart->items ?? [];
                $items[$data['rowid']] = $data;

                $cart_contents = $this->storeUserCart($items, $userId);

                return array_sum(Arr::pluck($cart_contents, 'qty'));

            } else {
                $cart_contents = $this->storeUserCart([$data['rowid'] => $data], $userId);

                return array_sum(Arr::pluck($cart_contents, 'qty'));
            }
        }
    }

    /**
     * @param $cart
     */
    public function storeUserCart($cart, $userId = null)
    {
        $userId = !is_null($userId) ? $userId : $this->authUser()->id;

        $userCart        = Cart::firstOrCreate(['user_id' => $userId]);
        $userCart->items = $cart;
        $userCart->save();

        return $userCart->items;
    }

    /**
     * @param $rowId
     */
    public function checkItemExistInCart($sku, $qty): void
    {
        $cart_contents = $this->getSessionCart();
        $item_exist    = false;
        $existing_item = [];

        if (count($cart_contents) > 0) {
            foreach ($cart_contents as $cart_content) {
                if ($cart_content['sku'] == $sku) {
                    $existing_item = $cart_content;
                    $item_exist    = true;
                }
            }
        }

        if ($item_exist) {
            $returnValue = $this->updateCartItemQty($existing_item, $qty);

            throw new HttpResponseException(response()->json($returnValue, $returnValue['type'] == 'success' ? 200 : 422));
        }
    }

    /**
     * @param $row
     */
    public function updateCartItemQty($existing_item, $qty)
    {
        $message = __('Item Quantity Updated');

        $cart = $this->updateCartQty($existing_item['rowid'], $qty);

        $cartCount = $this->getCartCount();

        $type = 'success';

        return [
            'cartCount' => $cartCount,
            'cart'      => $cart,
            'message'   => $message,
            'type'      => $type,
        ];
    }

    /**
     * @return mixed
     */
    public function updateCartQty(string $rowid, $qty = null)
    {
        $cart_contents = $this->getSessionCart();

        // If qty is null, increment by 1, else set qty to passed value
        $newQty                       = $qty ?? ($cart_contents[$rowid]['qty'] + 1);
        $cart_contents[$rowid]['qty'] = $newQty;

        $price         = $cart_contents[$rowid]['price'];
        $taxPercentage = $cart_contents[$rowid]['tax'] ?? 0; // tax % stored in cart
        $taxRate       = bcdiv($taxPercentage, '100', 4); // decimal

        // Calculate subtotal: price * qty
        $subTotal = bcmul((string) $price, (string) $newQty, 2);

        // Calculate unit tax: price * tax rate
        $unitTax = bcmul((string) $price, (string) $taxRate, 2);

        // Calculate total tax amount: unit tax * qty
        $taxAmount = bcmul($unitTax, (string) $newQty, 2);

        // Calculate total price: (price * qty) + tax amount
        $totalPrice = bcadd($subTotal, $taxAmount, 2);

        // Save updated calculations
        $cart_contents[$rowid]['sub_total']  = $subTotal;
        $cart_contents[$rowid]['unit_tax']   = $unitTax;
        $cart_contents[$rowid]['tax_amount'] = $taxAmount;
        $cart_contents[$rowid]['total']      = $totalPrice;

        if ($this->getHasApp()) {
            $cart_contents = $this->storeUserCart($cart_contents);
        }

        session()->put('cart', $cart_contents);

        return $this->getSessionCart();
    }

    /**
     * @param  $sku
     * @param  $product
     * @return mixed
     */
    public function getVariantData($product, $variantSku)
    {
        if ($product->has_variant) {
            if (!$variantSku) {
                $prodVar = $this->productService->getDefaultVariant($product);
            } else {
                $prodVar = $this->productService->getVariantBySku($variantSku, $product);
            }

            if ($prodVar) {
                $sku             = $prodVar->sku;
                $attributes      = $prodVar->attributes();
                $options         = $prodVar->attribute_and_value_ids;
                $price           = $prodVar->discount->price;
                $discount        = $prodVar->discount->discounted_price;
                $discountPercent = $prodVar->discount->discount_percent;

                return [
                    $sku,
                    $attributes,
                    $options,
                    $price,
                    $prodVar->discount->is_discounted,
                    $discount,
                    $discountPercent,
                ];

            }
        }

        return null;
    }

    /**
     * @param $product
     */
    public function generateCartItemData($product, $qty, $sku, $rowId = null)
    {
        $price            = $product->price;
        $isDiscounted     = $product->discounted_price->is_discounted;
        $discountAmount   = $product->discounted_price->is_discounted ? $product->discounted_price->discounted_price : $product->discounted_price->price;
        $discountPercent  = $product->discounted_price->is_discounted ? $product->discounted_price->discount_percent : 0;
        $variantDataAdded = false;

        $data = [];

        if ($product->has_variant && $variantData = $this->getVariantData($product, $sku)) {
            [$sku, $attributes, $options, $price, $isDiscounted, $discountAmount, $discountPercent] = $variantData;
            $data['variant']['attribute']                                                           = $attributes;
            $data['variant']['options']                                                             = $options;
            $variantDataAdded                                                                       = true;
        }

        $data['price']             = round($isDiscounted ? $discountAmount : $price, 2);
        $data['is_discounted']     = $isDiscounted;
        $data['discount_percent']  = max(0, $discountPercent);
        $data['discounted_amount'] = $data['is_discounted'] ? round($data['price'] * ($discountPercent / 100), 2) : 0;
        $data['sku']               = $sku;
        $data['rowid']             = !is_null($rowId) ? $rowId : uniqid('cart_');
        $data['id']                = $product->id;
        $data['name']              = $product->name;
        $data['slug']              = $product->slug;
        $data['image']             = $product->thumbnail_image;

        $data['price'] = number_format((string) $data['price'], 2, '.', '');
        $data['qty']   = (string) $qty;
        $data['tax']   = number_format((string) ($product->tax_percentage ?? 0), 2, '.', '');

        // Step 1: Calculate tax rate
        $rate = bcdiv($data['tax'], '100', 6);

        // Step 2: Unit tax = price * rate
        $data['unit_tax'] = bcround(bcmul($data['price'], $rate, 6), 2);

        // Step 3: Subtotal = price * qty
        $data['sub_total'] = bcround(bcmul($data['price'], $data['qty'], 4), 2);

        // Step 4: Tax amount = unit_tax * qty
        $data['tax_amount'] = bcround(bcmul($data['unit_tax'], $data['qty'], 4), 2);

        // Step 5: Total = subtotal + tax_amount
        $data['total'] = bcround(bcadd($data['sub_total'], $data['tax_amount'], 4), 2);

        $data['is_flash_deal'] = $product->is_flash_deal_active && $product->flash_deal_qty > 0 ? 1 : 0;
        $data['has_variant']   = $variantDataAdded;
        $data['theme']         = config('services.theme');
        $data['vendor_id']     = $product->vendor_id;
        $data['vendor_name']   = $product->vendor?->shop_name;
        $data['measure_unit']  = sprintf(
            'Length: %s cm, Width: %s cm, Height: %s cm, Weight: %s grams',
            $product->length ?? 0,
            $product->width ?? 0,
            $product->height ?? 0,
            $product->weight ?? 0
        );
        $data['weight'] = $product->weight;
        $data['cod']    = $product->is_cash_delivery;

        return $data;

    }

    public function getCartCount()
    {
        $cart_contents = $this->getSessionCart();

        return array_sum(array_column($cart_contents, 'qty'));
    }

    public function getCartSubTotal()
    {
        $cart_contents = $this->getSessionCart();

        return array_sum(array_column($cart_contents, 'sub_total'));
    }

    public function getCartTotalDiscount()
    {
        $cart_contents = $this->getSessionCart();

        return array_sum(array_column($cart_contents, 'discounted_amount'));
    }

    public function getCartTotalTax()
    {
        $cart_contents = $this->getSessionCart();

        return array_sum(array_column($cart_contents, 'tax_amount'));
    }

    public function getCartTotal()
    {
        $cart_contents = $this->getSessionCart();

        $couponDiscount = $this->getCouponDiscount();

        return array_sum(array_column($cart_contents, 'total')) - $couponDiscount;
    }

    public function getCouponDiscount()
    {
        return session()->has('coupon_discount') ? round(session()->get('coupon_discount', 0), 2) : 0;
    }

    /**
     * @param $value
     */
    public function checkArrayIsset($value)
    {
        return isset($value) ? $value : null;
    }

    /**
     * @param array      $cartItem
     * @param $product
     * @param string     $sku
     */
    protected function hasPriceChanged(array $cartItem, $product, ?string $variantSku = null): bool
    {
        $currentPrice = $this->getCurrentPrice($product, $variantSku);
        $currentTax   = round($product->tax_percentage ?? 0, 2);

        if ($product->has_variant) {
            $variant = $variantSku
            ? $this->productService->getVariantBySku($variantSku, $product)
            : $this->productService->getDefaultVariant($product);

            $discountPercent = $variant?->discount?->is_discounted
            ? $variant->discount->discount_percent
            : 0;
        } else {
            $discountPercent = $product->discounted_price->is_discounted
            ? $product->discounted_price->discount_percent
            : 0;
        }

        return (
            floatval($cartItem['price']) !== floatval($currentPrice) ||
            floatval($cartItem['tax']) !== floatval($currentTax) ||
            floatval($cartItem['discount_percent'] ?? 0) !== floatval($discountPercent)
        );
    }

    /**
     * @param $product
     * @param string     $sku
     */
    protected function getCurrentPrice($product, ?string $variantSku = null): float
    {
        if ($product->has_variant) {
            $variant = $variantSku
            ? $this->productService->getVariantBySku($variantSku, $product)
            : $this->productService->getDefaultVariant($product);

            if ($variant && $variant->discount) {
                $price         = $variant->discount->price;
                $discountPrice = $variant->discount->discounted_price;
                $isDiscounted  = $variant->discount->is_discounted;

                return round($isDiscounted ? $discountPrice : $price, 2);
            }
        }

        if ($product->discounted_price) {
            $price         = $product->discounted_price->price;
            $discountPrice = $product->discounted_price->discounted_price;
            $isDiscounted  = $product->discounted_price->is_discounted;

            return round($isDiscounted ? $discountPrice : $price, 2);
        }

        return round($product->price, 2);
    }

    public function destroyCart()
    {
        session()->forget('cart');

        if ($this->isLogged()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $this->authUser()->id],
                ['items' => []]
            );

            $cart->items = [];
            $cart->save();
        }

        $this->getSessionCart();

        return true;
    }

    /**
     * @return mixed
     */
    public function resyncCart()
    {
        $cart_contents = $this->getSessionCart();

        foreach ($cart_contents as $rowId => $cartItem) {
            $product = Product::find($cartItem['id']);

            if (!$product) {
                continue;
            }

            if ($this->hasPriceChanged($cartItem, $product, $cartItem['sku'])) {
                $updatedItem = $this->generateCartItemData($product, $cartItem['qty'], $cartItem['sku'], $rowId);

                $cart_contents[$rowId] = $updatedItem;
            }
        }

        if ($this->getHasApp()) {
            $cart_contents = $this->storeUserCart($cart_contents);
        }

        session()->put('cart', $cart_contents);

        return $this->getSessionCart();
    }

    /**
     * @param $product
     * @param Request    $request
     * @param int        $quantity
     */
    protected function checkStockAvailability($product, Request $request)
    {
        $quantity = $request->filled('qty') ? $request->qty : 1;

        if (!$product->allow_checkout_when_out_of_stock && $product->manage_stock) {
            $stockQty        = $product->stock_qty;
            $stockOutMessage = __('Product is out of stock');

            if ($request->filled('variant_sku') && $request->filled('is_variant') && $request->is_variant == 1) {
                $variant = $product->variants()->where('sku', $request->variant_sku)->first();

                if (!$variant) {
                    throw new HttpResponseException(response()->json([
                        'message'    => __('Variant not found'),
                        'alert-type' => 'error',
                    ], 422));
                }

                $variant->append('stock_qty');
                $stockQty        = $variant->stock_qty;
                $stockOutMessage = __('Variant is out of stock');
            }

            if ($product->variants->count() > 0 && $request->get('is_variant') == 0) {
                $variant = $product->variants()->where('sku', $product->default_variant_sku)->first();

                if ($variant) {
                    $variant->append('stock_qty');
                    $stockQty        = $variant->stock_qty;
                    $stockOutMessage = __('Default variant is out of stock');
                }
            }

            if ($product->stock_status === 'out_of_stock' || $stockQty < $quantity) {
                throw new HttpResponseException(response()->json([
                    'message'    => $stockOutMessage,
                    'alert-type' => 'error',
                ], 422));
            }
        }
    }

    /**
     * @param $throw
     */
    protected function isCartSingleVendor($throw = false, $responseJson = false): bool
    {
        $cart = $this->getSessionCart();

        if (empty($cart)) {
            return true;
        }

        $firstVendorId = collect($cart)->first()['vendor_id'];

        foreach ($cart as $item) {
            if ($item['vendor_id'] !== $firstVendorId) {
                if ($throw) {
                    $message = __('Checkout with multiple vendors is not allowed');

                    if ($responseJson) {
                        throw new HttpResponseException(response()->json([
                            'message'    => $message,
                            'alert-type' => 'error',
                        ], 422));
                    }

                    throw new HttpResponseException(redirect()->back()->with([
                        'message'    => $message,
                        'alert-type' => 'error',
                    ]));
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @param $throw
     * @param false    $responseJson
     */
    protected function isCartMultiVendor($throw = false, $responseJson = false): bool
    {
        $cart = $this->getSessionCart();

        if (empty($cart)) {
            return false;
        }

        $firstVendorId = collect($cart)->first()['vendor_id'];

        foreach ($cart as $item) {
            if ($item['vendor_id'] !== $firstVendorId) {
                if ($throw) {
                    $message = __('Checkout with multiple vendors is not allowed');

                    if ($responseJson) {
                        throw new HttpResponseException(response()->json([
                            'message'    => $message,
                            'alert-type' => 'error',
                        ], 422));
                    }

                    throw new HttpResponseException(redirect()->back()->with([
                        'message'    => $message,
                        'alert-type' => 'error',
                    ]));
                }
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    protected function getCartVendorId(): int
    {
        $cart = $this->getSessionCart();

        if (empty($cart)) {
            return 0;
        }

        return collect($cart)->first()['vendor_id'];
    }

    /**
     * @param $cartContents
     */
    private function checkStockAvailablity($cartContents)
    {
        foreach ($cartContents as $cartItem) {
            $product = Product::find($cartItem['id']);

            if (!$product) {
                throw new HttpResponseException(to_route('website.cart')->with([
                    'alert-type' => 'error',
                    'message'    => $cartItem['name'] . " - " . __('not found in our database!'),
                ]));
            }

            if ($product->manage_stock && !$product->allow_checkout_when_out_of_stock) {
                if ($cartItem['has_variant']) {
                    $stockQty = $product->variants()
                        ->where('sku', $cartItem['sku'])
                        ->first()
                    ?->stock_qty ?? 0;
                } else {
                    $stockQty = $product->stock_qty ?? 0;
                }

                if ($stockQty < $cartItem['qty']) {
                    throw new HttpResponseException(to_route('website.cart')->with([
                        'alert-type' => 'error',
                        'message'    => __('Stock not available for ') . $cartItem['name'] . ' (' . $cartItem['sku'] . '). ' . __('Available stock: ') . $stockQty,
                    ]));
                }
            }
        }
    }

    /**
     * @param $cartContents
     */
    protected function isCashOnDeliveryAvailable($cartContents)
    {
        $productIds = collect($cartContents)->pluck('id');

        $hasRestrictedProduct = Product::whereIn('id', $productIds)
            ->where('is_cash_delivery', 0)
            ->exists();

        return !$hasRestrictedProduct;
    }
}
