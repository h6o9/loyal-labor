<?php

namespace Modules\Product\app\Services;

use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\Brand;

class BrandService
{
    use GenerateTranslationTrait;

    protected Brand $brand;

    /**
     * @param Brand $brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->brand->all();
    }

    // get product paginate
    /**
     * @return mixed
     */
    public function getPaginateBrands()
    {
        $brand = $this->brand->with('translation');
        if (request()->keyword) {
            $keyword = request()->keyword;

            $brand = $brand->where(function ($query) use ($keyword) {
                $query->whereHas('translation', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }

        if (request()->filled('order_by')) {
            $brand = $brand->orderBy('slug', request()->get('order_by'));
        } else {
            $brand = $brand->orderBy('slug', 'desc');
        }

        return $brand;
    }

    // store product brand

    /**
     * @param  $request
     * @return mixed
     */
    public function store($request)
    {

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = file_upload($request->image);
        }

        if ($request->hasFile('icon')) {
            $data['icon'] = file_upload($request->icon);
        }

        $brand = $this->brand->create($data);

        $this->generateTranslations(
            TranslationModels::Brand,
            $brand,
            'brand_id',
            $request,
        );

        return $brand;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->brand->find($id);
    }

    /**
     * @param  $request
     * @param  $id
     * @return mixed
     */
    public function update($request, $id)
    {
        $brand         = $this->brand->find($id);
        $data          = $request->except('_token');
        $validatedData = $data;

        if ($request->hasFile('image')) {
            $data['image'] = file_upload($request->image);
        }
        if ($request->hasFile('icon')) {
            $data['icon'] = file_upload($request->icon);
        }

        $brand->update($data);

        $this->updateTranslations(
            $brand,
            $request,
            $validatedData,
        );

        return $brand;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function delete($id)
    {
        $brand = $this->brand->find($id);

        // delete category translations
        $brand->translations()->delete();

        // delete category
        if ($brand->image) {
            delete_file($brand->image);
        }

        if ($brand->icon) {
            delete_file($brand->icon);
        }

        return $brand->delete();
    }

    /**
     * @param  $request
     * @param  $id
     * @return mixed
     */
    public function statusUpdate($request, $id)
    {
        $brand         = $this->brand->find($id);
        $brand->status = $brand->status == 1 ? 0 : 1;
        $brand->save();

        return $brand;
    }

    /**
     * @return mixed
     */
    public function getActiveBrands()
    {
        return $this->brand->where('status', '1')->get();
    }

    /**
     * @param  $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->brand->where('slug', $slug)->first();
    }

    /**
     * @param  $slug
     * @return mixed
     */
    public function getProductByBrand($slug)
    {
        $brand = $this->brand->where('slug', $slug)->first();
        if ($brand) {
            return $brand->products;
        }

        return [];
    }

    /**
     * @param  $request
     * @return mixed
     */
    public function deleteAll($request)
    {
        $ids = $request->ids;

        return $this->brand->whereIn('id', $ids)->delete();
    }
}
