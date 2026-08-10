<?php

namespace Modules\Product\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Modules\Product\app\Services\BrandService;
use Modules\Product\app\Services\ProductCategoryService;
use Modules\Product\app\Services\ProductService;

class SellerProductController extends Controller
{

    /**
     * @param ProductService         $productService
     * @param ProductCategoryService $categoryService
     * @param BrandService           $brandService
     */
    public function __construct(
        private ProductService $productService,
        private ProductCategoryService $categoryService,
        private BrandService $brandService
    ) {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('product.seller.view');
        try {
            $products = $this->productService->getProducts(['vendor'])->whereNotNull('vendor_id');

            $products->when(request()->filled('vendor_id'), function ($query) {
                $query->where('vendor_id', request('vendor_id'));
            });

            $products->when(request()->filled('seller_status'), function ($query) {
                $sellerStatus = request('seller_status');
                match ($sellerStatus) {
                    'verified' => $query->whereRelation('vendor', 'is_verified', 1),
                    'pending'   => $query->whereRelation('vendor', 'is_verified', 0),
                    'published' => $query->whereRelation('vendor', 'status', 1),
                    'hidden'    => $query->whereRelation('vendor', 'status', 1),
                    default     => $query,
                };
            });

            if (request()->ajax()) {
                return $this->productService->productListDataTable($products, true);
            }

            $brands     = $this->brandService->getActiveBrands();
            $categories = $this->categoryService->getAllProductCategoriesForSelect();
            $vendors    = Vendor::with('user')->get();

            return view('product::products.index', compact('brands', 'categories', 'vendors'));
        } catch (\Exception $ex) {
            logError('Error fetching products', $ex);
            abort(500);
        }
    }
}
