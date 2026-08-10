<?php

namespace Modules\Product\app\Services;

use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\Category;

class ProductCategoryService
{
    use GenerateTranslationTrait;

    /**
     * @var mixed
     */
    private $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    // Get all product categories

    /**
     * @return mixed
     */
    public function getAllProductCategories($onlyParents = false, $onlyChilds = false, $callback = null)
    {
        $category = $this->category->with(['translation', 'parent']);
        if (request()->keyword) {
            $category = $category->whereHas('translation', function ($query) {
                $query->where('name', 'like', '%' . request()->keyword . '%');
            })->orWhereRelation('children', function ($query) {
                $query->whereHas('translation', function ($query) {
                    $query->where('name', 'like', '%' . request()->keyword . '%');
                });
            });
        }
        if (request()->order_by) {
            $category = $category->with(['translation' => function ($query) {
                $query->orderBy('name', request()->order_by);
            }]);
        } else {
            $category = $category->with(['translation' => function ($query) {
                $query->orderBy('name', 'asc');
            }]);
        }

        if (request()->filled('parent_id')) {
            $category = $category->where('id', (int) request('parent_id', 0));
            $onlyParents = false;
        }

        if ($onlyParents) {
            $category = $category->whereNull('parent_id');
        } elseif ($onlyChilds) {
            $category = $category->whereNotNull('parent_id');
        }

        $category->when($callback, function ($query) use ($callback) {
            return $callback($query);
        });

        $parpage = request('par-page', 10);

        $category = $category->paginate($parpage);
        $category->appends(request()->query());

        return $category;
    }

    // Get all active product categories

    /**
     * @return mixed
     */
    public function getActiveProductCategories()
    {
        return $this->category->where('status', '1')->get();
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        $category = $this->category->with('products', 'products.purchaseDetails', 'products.salesDetails');

        if (request()->keyword) {
            $category = $category->where('name', 'like', '%' . request()->keyword . '%');
        }

        return $category;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->category->get();
    }

    /**
     * @return mixed
     */
    public function getTopProductCategories()
    {
        return $this->category->where('status', '1')->where('top_category', '1');
    }

    // store product category

    /**
     * @return mixed
     */
    public function storeProductCategory($request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = file_upload($request->image);
        }
        $data['theme'] = config('services.theme');

        if ($request->hasFile('icon')) {
            $data['icon'] = file_upload($request->icon);
        }

        $category = $this->category->create($data);

        $this->generateTranslations(
            TranslationModels::Category,
            $category,
            'category_id',
            $request,
        );

        return $category;
    }

    // update product category

    /**
     * @return mixed
     */
    public function updateProductCategory($request, $id)
    {
        $category      = $this->category->find($id);
        $data          = $request->except('_token');
        $validatedData = $data;

        if ($request->hasFile('image')) {
            $data['image'] = file_upload($request->image);
        }
        if ($request->hasFile('icon')) {
            $data['icon'] = file_upload($request->icon);
        }

        $category->update($data);

        $this->updateTranslations(
            $category,
            $request,
            $validatedData,
        );

        return $category;
    }

    // delete product category

    /**
     * @return mixed
     */
    public function deleteProductCategory($id)
    {
        // check if category has products
        $category = $this->category->find($id);
        if ($category->products->count() > 0) {
            return false;
        }

        // delete category translations
        $category->translations()->delete();

        // delete category
        if ($category->image) {
            delete_file($category->image);
        }

        if ($category->icon) {
            delete_file($category->icon);
        }

        return $this->category->destroy($id);
    }

    /**
     * @return mixed
     */
    public function statusUpdate($request, $id)
    {
        $category         = $this->category->find($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return $category;
    }
    // get all product categories for select

    /**
     * @return mixed
     */
    public function getAllProductCategoriesForSelect($id = null)
    {
        if ($id) {
            return $this->category->with('parent.translation')->where('id', '!=', $id)->get();
        }

        return $this->category->with('parent')->get();
    }

    /**
     * @return mixed
     */
    public function getParentCategoriesOnly()
    {
        return $this->category
            ->whereNull('parent_id')
            ->with(['translation'])
            ->get();
    }

    /**
     * @return mixed
     */
    public function getCategoryWithChild($id = null)
    {
        return $this->category
            ->whereNull('parent_id')
            ->when($id, function ($query) use ($id) {
                $query->where('id', '==', $id);
            })
            ->with(['translation', 'children.translation', 'children.parent', 'children.parent.translation'])
            ->get();
    }

    // get categories id by product id
    /**
     * @return mixed
     */
    public function getCategoriesIdsByProductId($product_id)
    {
        return $this->category->whereHas('products', function ($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->pluck('id')->toArray();
    }

    /**
     * @return mixed
     */
    public function getProductCategory($id)
    {
        return $this->category->with('translation', 'parent')->find($id);
    }

    /**
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->category->where('slug', $slug)->first();
    }

    /**
     * @return mixed
     */
    public function getProductByCategory($slug)
    {
        $category = $this->category->where('slug', $slug)->first();
        if ($category) {
            return $category->products;
        }

        return [];
    }

    /**
     * @param $request
     */
    public function deleteAll($request)
    {
        $this->category->whereIn('id', $request->ids)->delete();
    }
}
