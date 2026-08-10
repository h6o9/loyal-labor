<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Traits\CartTrait;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GTMPayload;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Coupon\app\Services\CouponService;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Services\ProductService;

class CartController extends Controller
{
    use GetGlobalInformationTrait, CartTrait, GTMPayload;

    /**
     * @param ProductService $productService
     * @param CouponService  $couponService
     */
    public function __construct(
        protected ProductService $productService,
        protected CouponService $couponService
    ) {
        if (getSettings('has_app')) {
            $this->middleware('auth');
        }
    }

    /**
     * @return mixed
     */
    public function cart()
    {
        $cart_contents = $this->getSessionCart();

        $changedItems = [];

        foreach ($cart_contents as $key => $cartItem) {
            $product = Product::find($cartItem['id']);

            if (!$product) {
                continue;
            }

            if ($this->hasPriceChanged($cartItem, $product, $cartItem['sku'])) {
                $changedItems[] = $key;
            }
        }

        $cart_contents = collect($cart_contents)->groupBy('vendor_id');

        return view('website.cart.cart', compact('cart_contents', 'changedItems'));
    }

    /**
     * @param Request $request
     */
    public function cartStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku'        => 'required',
        ], [
            'product_id.required' => __('Product is required'),
            'product_id.exists'   => __('Product not found'),
            'sku'                 => __('SKU is required'),
        ]);

        $product = Product::findOr($request->get('product_id'), function () {
            throw new HttpResponseException(response()->json([
                'message'    => __('Product not found'),
                'alert-type' => 'error',
            ], 422));
        });

        $this->checkStockAvailability($product, $request);

        $sku = $request->filled('variant_sku') ? $request->variant_sku : $product->sku;

        $qty = $request->get('qty', 1);

        $this->checkItemExistInCart($sku, $qty);

        $data = $this->generateCartItemData($product, $qty, $sku);

        $gtmPayload = $this->getGTMPayloadForCart($data, 'add_to_cart') ?? [];

        $pixelPayload = [
            'event' => 'AddToCart',
            'data'  => [
                'content_ids'  => [$data['id']],
                'content_name' => $data['name'],
                'content_type' => 'product',
                'value'        => $data['qty'],
                'currency'     => getSessionCurrency(),
            ],
        ];

        if ($this->getHasApp()) {
            $this->addNewUserCartRow($data);
        }

        $this->addNewCartRow($data);

        Session::forget([
            'coupon_discount',
            'coupon_id',
            'coupon_code',
            'free_shipping',
        ]);

        return response()->json([
            'message'   => __('Item Added To Cart'),
            'cart'      => $data,
            'cartCount' => $this->getCartCount(),
            'gtm'       => $gtmPayload,
            'pixel'     => $pixelPayload,
        ]);
    }

    /**
     * @return mixed
     */
    public function cartRemove(string $id)
    {
        $cart_contents = session()->get('cart');
        $cart_contents = $cart_contents ? $cart_contents : [];

        $cart = null;

        if ($this->getHasApp()) {
            $user          = auth()->user();
            $cart          = Cart::where('user_id', $user->id)->first();
            $cart->items   = $cart_contents;
            $cart_contents = $cart->items;
        }
        $removedItem = null;
        foreach ($cart_contents as $item) {
            if ($item['rowid'] == $id) {
                $removedItem = $item;
                break;
            }
        }

        $cart_contents = array_filter($cart_contents, function ($item) use ($id) {
            return $item['rowid'] != $id;
        });

        if ($this->getHasApp()) {
            if ($cart) {
                $cart->items = $cart_contents;
                $cart->save();
                $cart_contents = $cart->items;
            }
        }

        session()->put('cart', $cart_contents);

        Session::forget([
            'coupon_discount',
            'coupon_id',
            'coupon_code',
            'free_shipping',
        ]);

        $view = view('components::cart-total', [
            'cart_contents' => $this->getSessionCart(),
        ])->render();

        return response()->json([
            'message'   => __('Item Removed From Cart'),
            'cartCount' => $this->getCartCount(),
            'html'      => $view,
            'gtm'       => $removedItem ? $this->getGTMPayloadForCart($removedItem, 'remove_from_cart') : null,
        ]);
    }

    /**
     * @param Request $request
     */
    public function cartUpdate(Request $request)
    {
        $rowid         = $request->rowid;
        $qty           = $request->qty;
        $cart_contents = $this->updateCartQty($rowid, $qty);

        $updatedItem = null;

        foreach ($cart_contents as $item) {
            if ($item['rowid'] == $rowid) {
                $updatedItem = $item;
                break;
            }
        }

        Session::forget([
            'coupon_discount',
            'coupon_id',
            'coupon_code',
            'free_shipping',
        ]);

        $view = view('components::cart-total', compact('cart_contents'))->render();

        return response()->json([
            'message'       => __('Item Updated In Cart'),
            'cartCount'     => $this->getCartCount(),
            'cart_contents' => $cart_contents,
            'html'          => $view,
            'gtm'           => $updatedItem ? $this->getGTMPayloadForCart($updatedItem, 'update_cart') : null,
        ]);
    }

    /**
     * @param Request $request
     */
    public function applyCoupon(Request $request)
    {
        $cart_contents = $this->getSessionCart();

        Session::forget([
            'coupon_discount',
            'coupon_id',
            'coupon_code',
            'free_shipping',
        ]);

        $response = $this->couponService->applyCoupon($request->code, $cart_contents);

        $view = view('components::cart-total', compact('cart_contents'))->render();

        return response()->json([ ...$response, 'html' => $view]);
    }

    public function resyncTotalCart()
    {
        $this->resyncCart();

        Session::forget([
            'coupon_discount',
            'coupon_id',
            'coupon_code',
            'free_shipping',
        ]);

        return to_route('website.cart')->with([
            'alert-type' => 'success',
            'message'    => __('Cart Updated'),
        ]);
    }
}
