<?php

namespace Modules\Product\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Language\app\Models\Language;
use Modules\Product\app\Http\Requests\ProductCategoryRequest;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\ProductCategory;
use Modules\Product\app\Services\ProductCategoryService;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    use RedirectHelperTrait;

    /**
     * @var mixed
     */
    protected $category;

    /**
     * @param ProductCategoryService $category
     */
    public function __construct(ProductCategoryService $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.category.view');

        if ($request->ajax()) {
            return $this->categoryDataTable($request);
        }

        $parentCategories = $this->category->getParentCategoriesOnly();

        return view('product::products.category.index', compact('parentCategories'));
    }

    /**
     * Build the flattened (parent + children) rows used by the AJAX
     * DataTables listing on the category index page, replicating the
     * filters previously applied by ProductCategoryService::getAllProductCategories().
     */
    private function categoryDataTable(Request $request)
    {
        $query = Category::with(['translation', 'parent'])
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('translation', 'parent');
            }]);

        $query->when($request->filled('keyword'), function ($query) use ($request) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('translation', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })->orWhereRelation('children', function ($query) use ($keyword) {
                    $query->whereHas('translation', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
                });
            });
        });

        if ($request->filled('order_by')) {
            $query->with(['translation' => function ($query) use ($request) {
                $query->orderBy('name', $request->get('order_by'));
            }]);
        } else {
            $query->with(['translation' => function ($query) {
                $query->orderBy('name', 'asc');
            }]);
        }

        if ($request->filled('parent_id')) {
            $query->where('id', (int) $request->get('parent_id', 0));
        }

        $categories = $query->get();

        $rows = collect();

        foreach ($categories as $category) {
            $rows->push((object) [
                'id'          => $category->id,
                'name'        => $category->name,
                'image'       => $category->image,
                'parent_name' => __('N/A'),
                'status'      => $category->status,
                'is_child'    => false,
            ]);

            foreach ($category->children as $child) {
                $rows->push((object) [
                    'id'          => $child->id,
                    'name'        => $child->name,
                    'image'       => $child->icon,
                    'parent_name' => $child->parent_id ? optional($child->parent)->name : __('N/A'),
                    'status'      => $child->status,
                    'is_child'    => true,
                ]);
            }
        }

        return DataTables::of($rows)
            ->addColumn('name', function ($row) {
                $size  = $row->is_child ? 20 : 50;
                $image = $row->image ? '<img class="me-2" src="' . asset($row->image) . '" alt="" width="' . $size . 'px" height="' . $size . 'px">' : '';
                $prefix = $row->is_child ? '&nbsp;&nbsp;&nbsp;&nbsp;&#8627;&nbsp;' : '';

                return $prefix . $image . e($row->name);
            })
            ->addColumn('parent_name', function ($row) {
                return e($row->parent_name);
            })
            ->addColumn('status_badge', function ($row) {
                if (!checkAdminHasPermission('product.category.update')) {
                    return '';
                }

                return '<input id="status_toggle" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $row->id . ')" ' . ($row->status ? 'checked' : '') . '>';
            })
            ->addColumn('action', function ($row) {
                $html = '';

                if (checkAdminHasPermission('product.category.edit')) {
                    $html .= '<a href="' . route('admin.category.edit', ['category' => $row->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                }

                if (checkAdminHasPermission('product.category.delete')) {
                    $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $row->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }

                return $html;
            })
            ->rawColumns(['name', 'parent_name', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('product.category.create');

        $categories = $this->category->getParentCategoriesOnly();

        return view('product::products.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request)
    {
        checkAdminHasPermissionAndThrowException('product.category.create');
        DB::beginTransaction();
        try {
            $category = $this->category->storeProductCategory($request);
            DB::commit();
            if ($request->ajax()) {
                return response()->json(['message' => 'Created successfully', 'categories' => $category, 'status' => 200], 200);
            }

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.category.edit', ['category' => $category->id, 'code' => getSessionLanguage()]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.category.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        checkAdminHasPermissionAndThrowException('product.category.edit');

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $cat        = $this->category->getProductCategory($id);

        $productCategory = Category::whereSlug($cat->slug)->where('id', '!=', $id)->count();

        if ($productCategory > 0) {
            $cat->slug = $cat->slug . '-' . $productCategory;
            $cat->save();
        }

        $categories = $this->category->getParentCategoriesOnly($id);

        return view('product::products.category.edit', compact('categories', 'cat', 'code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryRequest $request, string $id)
    {
        checkAdminHasPermissionAndThrowException('product.category.edit');

        DB::beginTransaction();

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        try {
            $this->category->updateProductCategory($request, $id);
            DB::commit();

            return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.category.edit', ['category' => $id, 'code' => $code], [
                'message'    => __('Updated successfully'),
                'alert-type' => 'success',
            ]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        checkAdminHasPermissionAndThrowException('product.category.delete');
        try {
            $cat = $this->category->getProductCategory($id);

            if ($cat->parent_id && $cat?->parent && $cat?->parent?->products?->count() > 0) {
                return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.category.index', [], [
                    'message'    => __('Category delete failed, Parent category associted with products!'),
                    'alert-type' => 'error',
                ]);
            }

            if ($cat && $cat?->products?->count() > 0) {
                return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.category.index', [], [
                    'message'    => __('Category delete failed, Associted with products!'),
                    'alert-type' => 'error',
                ]);
            }

            $category = $this->category->deleteProductCategory($id);
            if (!$category) {
                return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.category.index', [], [
                    'message'    => 'Category has products',
                    'alert-type' => 'error',
                ]);
            } else {
                return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.category.index')->with([
                    'message'    => 'Deleted successfully',
                    'alert-type' => 'success',
                ]);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.category.index');
        }
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function status(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('product.category.update');
        try {
            $this->category->statusUpdate($request, $id);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * @param Request $request
     */
    public function deleteAll(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.category.delete');
        try {

            foreach ($this->category->getAll() as $cat) {
                if ($cat->parent_id && $cat?->parent && $cat?->parent?->products?->count() > 0) {
                    return response()->json(['message' => __('Category delete failed, parent category associted with products!')], 200);
                }

                if ($cat && $cat?->products?->count() > 0) {
                    return response()->json(['message' => __('Category delete failed, Associted with products!')], 200);
                }
            }

            $this->category->deleteAll($request);

            return response()->json(['success' => true, 'message' => 'Deleted successfully'], 200);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
