<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Product\app\Rules\DynamicProductPriceFieldRule;
use Modules\Product\app\Services\BrandService;
use Modules\Product\app\Services\ProductCategoryService;
use Modules\Product\app\Services\ProductService;

class SellerProductPriceController extends Controller
{

    use RedirectHelperTrait;

    /**
     * @param ProductService         $productService
     * @param ProductCategoryService $categoryService
     * @param BrandService           $brandService
     */
    public function __construct(
        private ProductService $productService,
        private ProductCategoryService $categoryService,
        private BrandService $brandService,
    ) {
    }

    public function priceUpdate()
    {

        try {
            $products = $this->productService->getProducts()->where('vendor_id', vendorId());

            if (request('par-page')) {
                $products = $products->paginate(request('par-page'));
                $products->appends(request()->query());
            } else {
                $products = $products->paginate(5);
            }

            $brands = $this->brandService->getActiveBrands();

            $categories = $this->categoryService->getAllProductCategoriesForSelect();

            return view('vendor::products.prices.price-update', compact('products', 'brands', 'categories'));
        } catch (\Exception $ex) {
            logError('Product Prices Update Error on Seller Profile', $ex);
            return back()->with([
                'message'    => __('Something Went Wrong'),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function priceUpdateStore(Request $request)
    {
        $validationRules = [
            'field'              => 'required|in:price,offer_price,offer_price_end,offer_price_start,offer_price_type',
            'value'              => [
                'required_if:field,price',
                new DynamicProductPriceFieldRule($request->input('field')),
            ],
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'sometimes|exists:variants,id',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => __('Validation Error'),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $field = $request->get('field');

        $reload = false;

        if ($request->filled('product_variant_id')) {
            $variant = $this->productService->getProductVariant($request->product_variant_id);

            if (!$variant) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product Variant not found'),
                ]);
            }

            $product = $this->productService->getProduct($variant->product_id, function ($query) {
                return $query->where('vendor_id', vendorId());
            });

            if (!$product) {
                return response()->json([
                    'message' => __('Product not found'),
                    'status'  => false,
                ]);
            }

            $offerTypeOld  = $variant->offer_price_type;
            $oldOfferPrice = $variant->offer_price;

            if ($field == 'offer_price' && $offerTypeOld == 'percentage' && $request->get('value') > 100) {
                return response()->json([
                    'status'  => false,
                    'reload'  => true,
                    'message' => __('Offer price cannot be more than 100% when offer price type is percentage'),
                ]);
            }

            $variant->$field = $request->value;

            if ($field == 'offer_price_type' && $offerTypeOld == 'fixed' && $oldOfferPrice > 100 && $request->get('value') == 'percentage') {
                $variant->offer_price = 0;
                $reload               = true;
            }

            $variant->save();
        } else {
            $product = $this->productService->getProduct($request->product_id);
            if (!$product) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product not found'),
                ]);
            }
            $offerTypeOld  = $product->offer_price_type;
            $oldOfferPrice = $product->offer_price;

            if ($field == 'offer_price' && $offerTypeOld == 'percentage' && $request->get('value') > 100) {
                return response()->json([
                    'status'  => false,
                    'reload'  => true,
                    'message' => __('Offer price cannot be more than 100% when offer price type is percentage'),
                ]);
            }

            $product->$field = $request->value;

            if ($field == 'offer_price_type' && $offerTypeOld == 'fixed' && $oldOfferPrice > 100 && $request->get('value') == 'percentage') {
                $product->offer_price = 0;
                $reload               = true;
            }

            $product->save();
        }

        return response()->json([
            'status'  => true,
            'reload'  => $reload,
            'message' => __(':field updated successfully', ['field' => str($field)->replace('_', ' ')->title()]),
        ]);
    }

}
