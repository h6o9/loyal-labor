<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Product\app\Rules\DynamicProductInventoryFieldRule;
use Modules\Product\app\Services\BrandService;
use Modules\Product\app\Services\ProductCategoryService;
use Modules\Product\app\Services\ProductService;
use Yajra\DataTables\Facades\DataTables;

class SellerProductStockController extends Controller
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

    public function productInventory(Request $request)
    {
        try {
            $query = $this->productService->getProducts()->where('vendor_id', vendorId());

            if ($request->ajax()) {
                $query->with([
                    'variants.product.unit',
                    'variants.optionValues.translation',
                    'variants.optionValues.attribute.translation',
                ]);

                return DataTables::of($query)
                    ->addColumn('sku', function ($product) {
                        return $product->has_variant ? '<b>' . e($product->sku) . '</b>' : e($product->sku);
                    })
                    ->addColumn('name', function ($product) {
                        return $product->has_variant ? '<b>' . e($product->name) . '</b>' : e($product->name);
                    })
                    ->addColumn('manage_stock', function ($product) {
                        return $this->renderManageStockControl($product);
                    })
                    ->addColumn('stock_quantity', function ($product) {
                        if ($product->has_variant) {
                            $html = '';

                            foreach ($product->variants as $variant) {
                                $optionsHtml = '';

                                if ($variant->optionValues) {
                                    foreach ($variant->optionValues as $option) {
                                        $optionsHtml .= '<span class="badge badge-info">' . e($option->attribute->name) . ': ' . e($option->name) . '</span> ';
                                    }
                                }

                                $disabled = (optional($product)->manage_stock !== 1 || optional($product)->stock_status == 'out_of_stock') ? ' disabled' : '';

                                $html .= '<div class="mb-2 pb-2 border-bottom">'
                                    . '<div class="small text-muted">' . e($variant->sku) . '</div>'
                                    . '<div class="mb-1">' . $optionsHtml . '</div>'
                                    . '<div class="input-group">'
                                    . '<input class="form-control update-stock stock-product-variant-' . $product->id . '"'
                                    . ' id="stock_qty-' . $variant->id . '" name="stock_qty"'
                                    . ' data-sku="' . e($variant->sku) . '" data-is-variant="true" data-field="stock_qty"'
                                    . ' data-product-variant-id="' . $variant->id . '" data-product-id="' . $product->id . '"'
                                    . ' type="number" value="' . e($variant->stock_qty) . '" placeholder="' . __('Stock Quantity') . '"' . $disabled . '>'
                                    . '<span class="input-group-text">' . e(optional($variant->product->unit)->name) . '</span>'
                                    . '</div>'
                                    . '</div>';
                            }

                            return $html;
                        }

                        $disabled = (optional($product)->manage_stock !== 1 || optional($product)->stock_status == 'out_of_stock') ? ' disabled' : '';

                        return '<div class="input-group">'
                            . '<input class="form-control update-stock" id="stock_qty-' . $product->id . '" data-field="stock_qty"'
                            . ' data-product-id="' . $product->id . '" data-sku="' . e($product->sku) . '" type="number"'
                            . ' value="' . e($product->stock_qty) . '" placeholder="' . __('Stock Quantity') . '"' . $disabled . '>'
                            . '<span class="input-group-text">' . e(optional($product->unit)->name) . '</span>'
                            . '</div>';
                    })
                    ->rawColumns(['sku', 'name', 'manage_stock', 'stock_quantity'])
                    ->make(true);
            }

            $brands = $this->brandService->getActiveBrands();

            $categories = $this->categoryService->getAllProductCategoriesForSelect();

            return view('vendor::products.inventory.index', compact('brands', 'categories'));
        } catch (\Exception $ex) {
            logError('Product Prices Update Error on Seller Profile', $ex);

            return back()->with([
                'message'    => __('Something Went Wrong'),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param  mixed  $product
     * @return string
     */
    private function renderManageStockControl($product): string
    {
        $selectDisabled = optional($product)->manage_stock !== 1 ? ' disabled' : '';

        return '<div class="input-group">'
            . '<div class="input-group-text">'
            . '<input class="form-check-input mt-0 update-stock" id="manage_stock-' . $product->id . '"'
            . ' data-sku="' . e($product->sku) . '" data-field="manage_stock" data-product-id="' . $product->id . '"'
            . ' type="checkbox" value="1"' . (optional($product)->manage_stock == 1 ? ' checked' : '') . '>'
            . '</div>'
            . '<select class="form-select update-stock" id="stock_status-' . $product->id . '" data-field="stock_status"'
            . ' data-sku="' . e($product->sku) . '" data-product-id="' . $product->id . '"' . $selectDisabled . '>'
            . '<option value="">' . __('Select') . '</option>'
            . '<option value="in_stock"' . ($product->stock_status == 'in_stock' ? ' selected' : '') . '>' . __('In Stock') . '</option>'
            . '<option value="out_of_stock"' . ($product->stock_status == 'out_of_stock' ? ' selected' : '') . '>' . __('Out of Stock') . '</option>'
            . '</select>'
            . '</div>';
    }

    /**
     * @param Request $request
     */
    public function productInventoryStore(Request $request)
    {
        $validationRules = [
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'sometimes|exists:variants,id',
            'sku'                => 'required|string',
            'value'              => [
                'required',
                new DynamicProductInventoryFieldRule($request->input('field')),
            ],
            'field'              => 'required|in:manage_stock,stock_status,stock_qty',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $field = $request->get('field');

        if ($request->filled('product_variant_id') && $request->input('field') == 'stock_qty') {
            $variant = $this->productService->getProductVariant($request->product_variant_id);

            $product = $this->productService->getProduct($variant->product_id, function ($query) {
                return $query->where('vendor_id', vendorId());
            });

            if (!$product) {
                return response()->json([
                    'message' => __('Product not found'),
                    'status'  => false,
                ]);
            }

            $variantStock = $variant->manageStocks()->where('sku', $request->sku)->first();

            if (!$variant) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product Variant not found'),
                ]);
            }

            $variantStock->update(['quantity' => $request->value]);
        }

        if (!$request->filled('product_variant_id')) {
            $product = $this->productService->getProduct($request->product_id, function ($query) {
                return $query->where('vendor_id', vendorId());
            });

            if (!$product) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product not found'),
                ]);
            }

            if ($field !== 'stock_qty') {
                $product->$field = $request->value;
                $product->save();
            }

            if ($field == 'stock_qty') {
                $product->manageStocks()->update(['quantity' => $request->value]);
            }
        }

        $response = [
            'status'     => true,
            'field'      => $field,
            'product_id' => $request->product_id,
            'message'    => __(':field updated successfully', ['field' => str($request->input('field'))->replace('_', ' ')->title()]),
        ];

        if ($request->filled('product_variant_id')) {
            $response['variant_id'] = $request->product_variant_id;
        }

        return response()->json($response);
    }

}
