<?php

namespace Modules\Product\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\AttributeImage;
use Modules\Product\app\Models\AttributeValueTranslation;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductTag;
use Modules\Product\app\Models\Tag;
use Modules\Product\app\Models\Variant;
use Modules\Product\app\Models\VariantOption;
use Modules\Stock\app\Models\Stock;

class ProductService
{
    use GenerateTranslationTrait;

    protected Product $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProducts(array $with = ['orders', 'unit', 'brand.translation', 'categories.translation', 'translation'])
    {
        $query = $this->product->with($with);

        $query->when(request()->filled('keyword'), function ($q) {
            $q->where(function ($q) {
                $q->whereHas('translation', function ($q) {
                    $q->where('name', 'like', '%' . request()->keyword . '%');
                })
                    ->orWhere('sku', 'like', '%' . request()->keyword . '%')
                    ->orWhere('barcode', 'like', '%' . request()->keyword . '%')
                    ->orWhereHas('variants', function ($q) {
                        $q->where('sku', 'like', '%' . request()->keyword . '%');
                    });
            });
        });

        $query->when(request()->filled('order_by'), function ($q) {
            $key = match (request('order_by')) {
                'asc' => 'name',
                'desc'    => 'name',
                'id_desc' => 'id',
                'id_asc'  => 'id',
            };

            $type = match (request('order_by')) {
                'asc'     => 'asc',
                'desc'    => 'desc',
                'id_desc' => 'desc',
                'id_asc'  => 'asc',
            };

            if ($key == 'name') {
                $q->orderBy('slug', $type);
            } else {
                $q->orderBy($key, $type);
            }
        }, function ($q) {
            $q->orderBy('slug', 'asc');
        });

        $query->when(request()->filled('status'), function ($q) {
            $statusMapping = [
                'published'  => ['key' => 'status', 'value' => 1],
                'hidden'     => ['key' => 'status', 'value' => 0],
                'approved'   => ['key' => 'is_approved', 'value' => 1],
                'pending'    => ['key' => 'is_approved', 'value' => 0],
                'flash_deal' => ['key' => 'is_flash_deal', 'value' => 1],
            ];

            $status = $statusMapping[request('status')] ?? null;

            if ($status) {
                $q->where($status['key'], $status['value']);
            }
        });

        $query->when(request()->filled('brand_id'), function ($q) {
            $q->where('brand_id', request('brand_id'));
        });

        $query->when(request()->filled('category_id'), function ($q) {
            $q->whereRelation('categories', 'category_id', request('category_id'));
        });

        return $query;
    }

    /**
     * Build the Yajra DataTables ajax response used by the admin product
     * list (and the seller-scoped product list, which reuses the same
     * Blade view). Reproduces exactly the cell markup that used to be
     * rendered by the Blade @foreach loop for these pages.
     *
     * @param mixed $query
     */
    public function productListDataTable($query, bool $isSellerContext = false)
    {
        $dt = \Yajra\DataTables\Facades\DataTables::of($query)
            ->addColumn('photo', function ($product) {
                return '<img class="rounded-circle m-1" src="' . asset($product->thumbnail_image) . '">';
            })
            ->addColumn('name_display', function ($product) {
                $html = '<a href="' . route('website.product', ['product' => $product->slug]) . '">' . e($product->name) . '</a>';
                $html .= ' - ' . e($product->brand->name) . ' <br>';
                $html .= e(__('SKU')) . ': ' . e($product->sku);

                if ($product->is_flash_deal) {
                    $html .= ' <span class="badge bg-success p-1">' . e(__('Flash Deal')) . '</span>';
                }

                if ($product->has_variant) {
                    $html .= '<br>' . e($product->variants->count() ?? 0) . ' ' . e(__('Variants'));
                }

                return $html;
            })
            ->addColumn('stock_display', function ($product) {
                if ($product->manage_stock && $product->stock_status == 'in_stock') {
                    return e($product->stock_qty) . ' ' . e($product->unit?->name ?? '');
                } elseif (!$product->manage_stock) {
                    return e(__('Unmanaged'));
                }

                return e(__('Out of Stock'));
            })
            ->editColumn('price', function ($product) {
                return currency($product->price);
            })
            ->addColumn('discounted_price_display', function ($product) {
                if ($product->discounted_price->is_discounted) {
                    return currency($product->discounted_price->discounted_price) . ' (' . e($product->discounted_price->discount_percent) . '%)';
                }

                return e(__('No Discount'));
            });

        $rawColumns = ['photo', 'name_display', 'stock_display', 'discounted_price_display'];

        if ($isSellerContext) {
            $dt->addColumn('seller_display', function ($product) {
                if ($product->vendor) {
                    $icon = ($product?->vendor?->is_verified ?? false)
                        ? '<i class="fas fa-check-circle text-success" title="' . __('Verified') . '"></i>'
                        : '<i class="fas fa-times-circle text-danger" title="' . __('Not Verified') . '"></i>';

                    return '<span><a href="">' . e($product?->vendor?->shop_name ?? __('N/A')) . ' ' . $icon . '</a></span>';
                }

                return e(__('N/A'));
            });

            $rawColumns[] = 'seller_display';
        }

        if (checkAdminHasPermission('product.status')) {
            $dt->addColumn('status_toggle', function ($product) {
                return '<input id="status_toggle" data-toggle="toggle" data-onlabel="' . __('Published') . '" data-offlabel="' . __('Hidden') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="status(' . $product->id . ')" ' . ($product->status ? 'checked' : '') . '>';
            });

            $dt->addColumn('approved_toggle', function ($product) {
                return '<input id="is_approved_toggle" data-toggle="toggle" data-onlabel="' . __('Approved') . '" data-offlabel="' . __('Pending') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="approveProduct(' . $product->id . ')" ' . ($product->is_approved ? 'checked' : '') . '>';
            });

            $rawColumns[] = 'status_toggle';
            $rawColumns[] = 'approved_toggle';
        }

        $dt->addColumn('action', function ($product) {
            $html = '<div class="btn-group" role="group">';
            $html .= '<button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton' . $product->id . '" data-bs-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
            $html .= '<div class="dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);" x-placement="top-start">';

            if (!$product->is_flash_deal) {
                $html .= '<a class="dropdown-item" href="' . route('admin.product.product-variant', ['id' => $product->id]) . '"></i> ' . __('Product Variant') . ' (' . ($product->variants->count() ?? 0) . ')</a>';
            }

            $html .= '<a class="dropdown-item" href="' . route('admin.product.show', ['product' => $product->id]) . '"></i> ' . __('Details') . '</a>';

            if (checkAdminHasPermission('product.edit')) {
                $html .= '<a class="dropdown-item" href="' . route('admin.product-gallery', ['id' => $product->id]) . '"> ' . __('Gallery') . ' (' . ($product->gallery->count() ?? 0) . ')</a>';

                $updatedLabel = $product->updated_at
                    ? $product->updated_at->diffForHumans(['parts' => 1, 'short' => true])
                    : $product->created_at->diffForHumans(['parts' => 1, 'short' => true]);

                $html .= '<a class="dropdown-item" href="' . route('admin.product.edit', ['product' => $product->id, 'code' => getSessionLanguage()]) . '"> ' . __('Edit') . ' (' . $updatedLabel . ')</a>';
            }

            $html .= '<a class="dropdown-item" href="' . route('admin.product-review', ['product' => $product->slug]) . '"> ' . __('Reviews') . ' <span class="text-warning">(' . ($product->pending_reviews_count ?? 0) . ')</span></a>';

            if (checkAdminHasPermission('product.delete')) {
                if ($product->orders->count() > 0) {
                    $html .= '<a class="dropdown-item" href="javascript:;" data-bs-target="#canNotDeleteModal">' . __('Delete') . '</a>';
                } else {
                    $html .= '<a class="dropdown-item" href="javascript:;" onclick="deleteData(' . $product->id . ')">' . __('Delete') . '</a>';
                }
            }

            $html .= '</div></div>';

            return $html;
        });

        $rawColumns[] = 'action';

        return $dt->rawColumns($rawColumns)->make(true);
    }

    // get all active products
    /**
     * @return mixed
     */
    public function allActiveProducts($request)
    {
        $products = $this->product->where('status', 1)->withCount('variants')->with('translation', 'categories');

        $sort = request()->order_by ? request()->order_by : 'asc';

        $products = $products->whereHas('translation', function ($q) use ($sort) {
            $q->orderBy('name', $sort);
        });

        return $products;
    }

    /**
     * @return mixed
     */
    public function getProduct($id,  ?callable $callback = null): ?Product
    {
        $query = $this->product->where('id', $id);

        if (is_callable($callback)) {
            $query = $callback($query);
        }

        return $query->first();
    }

    /**
     * @return mixed
     */
    public function storeProduct($request)
    {
        $data = $request->validated();

        if ($request->has('offer_price_start')) {
            $data['offer_price_start'] = date($request->offer_price_start);
        }

        if ($request->has('offer_price_end')) {
            $data['offer_price_end'] = date($request->offer_price_end);
        }

        if ($request->file('thumbnail_image')) {
            $data['thumbnail_image'] = file_upload($request->thumbnail_image);
        }

        if ($request->file('flash_deal_image')) {
            $data['flash_deal_image'] = file_upload($request->flash_deal_image);
        }

        if ($request->has('flash_deal_start')) {
            $data['flash_deal_start'] = date($request->flash_deal_start);
        }

        if ($request->has('flash_deal_end')) {
            $data['flash_deal_end'] = date($request->flash_deal_end);
        }

        $product = $this->product->create(
            $data
        );

        $this->generateTranslations(
            TranslationModels::Product,
            $product,
            'product_id',
            $request,
        );

        // product categories
        $product->categories()->attach($request->category_id);

        // product tags

        if ($request->has('tags')) {
            $tags = json_decode($request->tags);

            $this->storeTags($tags, $product);
        }

        // stock

        if ($request->has('manage_stock') && $request->filled('stock_qty')) {
            Stock::create([
                'product_id' => $product->id,
                'quantity'   => $request->stock_qty,
                'sku'        => $request->sku,
                'type'       => null,
            ]);
        }

        if ($request->filled('labels') && count($request->labels) > 0) {
            $product->labels()->attach($request->labels);
        }

        if ($request->filled('tax_ids') && count($request->tax_ids) > 0) {
            $product->taxes()->attach($request->tax_ids);
        }

        return $product;
    }

    /**
     * @param $tags
     * @param $product
     */
    public function storeTags($tags, $product)
    {
        foreach ($tags as $tag) {
            $slug     = Str::slug($tag->value);
            $existTag = Tag::where('slug', $slug)->first();
            if ($existTag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id'     => $existTag->id,
                ]);
            } else {
                $newTag = Tag::create([
                    'slug' => $slug,
                ]);

                $newRequest = new Request;

                $newRequest->merge([
                    'name' => $tag->value,
                ]);

                $newRequest = Validator::make($newRequest->all(), [
                    'name' => ['required', 'string', 'max:255'],
                ]);

                // tag translation
                $this->generateTranslations(
                    TranslationModels::Tag,
                    $newTag,
                    'tag_id',
                    $newRequest,
                );

                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id'     => $newTag->id,
                ]);
            }
        }
    }

    /**
     * @return mixed
     */
    public function updateProduct($request, $product)
    {

        $data = $request->validated();

        if ($request->has('offer_price_start')) {
            $data['offer_price_start'] = date($request->offer_price_start);
        }

        if ($request->has('offer_price_end')) {
            $data['offer_price_end'] = date($request->offer_price_end);
        }

        if ($request->has('flash_deal_start')) {
            $data['flash_deal_start'] = date($request->flash_deal_start);
        }

        if ($request->has('flash_deal_end')) {
            $data['flash_deal_end'] = date($request->flash_deal_end);
        }

        if ($request->file('thumbnail_image')) {
            $data['thumbnail_image'] = file_upload($request->thumbnail_image);
        }

        if ($request->file('flash_deal_image')) {
            $data['flash_deal_image'] = file_upload($request->flash_deal_image);
        }

        if ($request->filled('code') && $request->code == allLanguages()->first()->code) {
            $product->update($data);
        }

        $this->updateTranslations(
            $product,
            $request,
            $data,
        );

        if ($request->filled('code') && $request->code == allLanguages()->first()->code) {
            // product categories
            $product->categories()->sync($request->category_id);

            // product tags
            if ($request->has('tags')) {
                $tags = json_decode($request->tags); // Convert JSON string to an array

                $tagIds = []; // To store tag IDs for syncing

                foreach ($tags as $tag) {
                    $slug     = Str::slug($tag->value);
                    $existTag = Tag::where('slug', $slug)->first();

                    if ($existTag) {
                        $tagIds[] = $existTag->id; // Add existing tag ID
                    } else {
                        // Create new tag
                        $newTag = Tag::create([
                            'slug' => $slug,
                        ]);

                        $newRequest = new Request;

                        $newRequest->merge([
                            'name' => $tag->value,
                            'code' => $request->code,
                        ]);

                        $newRequest = Validator::make($newRequest->all(), [
                            'name' => ['required', 'string', 'max:255'],
                            'code' => ['required', 'string', 'max:10'],
                        ]);

                        // Generate translations
                        $this->generateTranslations(
                            TranslationModels::Tag,
                            $newTag,
                            'tag_id',
                            $newRequest,
                        );

                        $tagIds[] = $newTag->id; // Add new tag ID
                    }
                }

                // Sync tags (remove old, add new)
                $product->tags()->sync($tagIds);
            }

            if ($request->filled('labels') && count($request->labels) > 0) {
                $product->labels()->sync($request->labels);
            }

            if ($request->filled('tax_ids') && count($request->tax_ids) > 0) {
                $product->taxes()->sync($request->tax_ids);
            }

            // stock

            if ($request->filled('manage_stock') && $request->stock_status == 'in_stock') {
                $stock = $product->manageStocks ?: null;

                if ($stock && $stock->quantity != $request->stock_qty) {
                    if ($stock) {
                        $stock->update([
                            'quantity' => $request->stock_qty,
                            'sku'      => $request->sku,
                        ]);
                    } else {
                        Stock::create([
                            'product_id' => $product->id,
                            'quantity'   => $request->stock_qty,
                            'sku'        => $request->sku,
                            'type'       => null,
                        ]);
                    }
                }
            }
        }

        return $product;
    }

    /**
     * @return mixed
     */
    public function getActiveProductById($id)
    {
        return $this->product->where('id', $id)->where('status', 1)->first();
    }

    /**
     * @return mixed
     */
    public function deleteProduct($product)
    {
        // check if product has orders
        if ($product->orders->count() > 0) {
            return false;
        }

        $product->labels()->detach();

        return $product->delete();
    }

    /**
     * @param $ids
     */
    public function bulkDelete($ids)
    {
        foreach ($ids as $id) {
            $this->deleteProduct($this->getActiveProductById($id));
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function storeRelatedProducts($request, $product)
    {
        $ids = $request->product_id;

        // Remove existing related products
        $product->relatedProducts()->delete();

        // Add new related products
        foreach ($ids as $relatedProductId) {
            $product->relatedProducts()->create([
                'related_product_id' => $relatedProductId,
            ]);
        }

        return $product;
    }

    /**
     * @return mixed
     */
    public function getProductBySlug($slug): ?Product
    {
        return $this->product->where('slug', $slug)->first();
    }

    /**
     * @return mixed
     */
    public function getProductsByCategory($category_id, $limit = 10): Collection
    {
        return $this->product->where('category_id', $category_id)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getProductsByBrand($brand_id, $limit = 10): Collection
    {
        return $this->product->where('brand_id', $brand_id)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getProductsByTag($tag, $limit = 10): Collection
    {
        return $this->product->where('tags', 'like', '%' . $tag . '%')->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getFeaturedProducts($limit = 10): Collection
    {
        return $this->product->where('is_featured', 1)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getBestSellingProducts($limit = 10): Collection
    {
        return $this->product->where('is_best_selling', 1)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getTopRatedProducts($limit = 10): Collection
    {
        return $this->product->where('is_top_rated', 1)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getNewArrivalProducts($limit = 10): Collection
    {
        return $this->product->where('is_new_arrival', 1)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getRelatedProducts($product)
    {
        return $product->relatedProducts->pluck('related_product_id')->toArray();
    }

    /**
     * @return mixed
     */
    public function getProductsBySearch($search, $limit = 10): Collection
    {
        return $this->product->where('name', 'like', '%' . $search . '%')->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getProductsByPriceRange($min, $max, $limit = 10): Collection
    {
        return $this->product->whereBetween('price', [$min, $max])->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getProductsByDiscount($limit = 10): Collection
    {
        return $this->product->where('discount', '>', 0)->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getProductsByAttribute($attribute, $limit = 10): Collection
    {
        return $this->product->where('attributes', 'like', '%' . $attribute . '%')->limit($limit)->get();
    }

    /**
     * @return mixed
     */
    public function getVariantBySku($sku, $product)
    {
        $variant = Variant::where('sku', $sku)->where('product_id', $product->id)->first();

        if ($variant) {
            return $variant;
        }

        return $this->getDefaultVariant($product);
    }

    /**
     * @return mixed
     */
    public function getDefaultVariant($product)
    {
        return $product->variants->where('is_default', 1);
    }

    /**
     * @return mixed
     */
    public function getProductVariants($product)
    {
        $variants = $product->variants->load('options')->map(function ($variant) {
            return [
                'id'                => $variant->id,
                'sku'               => $variant->sku,
                'price'             => $variant->price,
                'offer_price'       => $variant->offer_price,
                'offer_price_type'  => $variant->offer_price_type,
                'offer_price_start' => $variant->offer_price_start,
                'offer_price_end'   => $variant->offer_price_end,
                'is_default'        => $variant->is_default,
                'stock_qty'         => $variant->stock_qty,
                'discounted_price'  => $variant->discount,
                'attribute'         => $variant->attributes(),
                'attributes'        => $variant->options->map(function ($option) {
                    return [
                        'attribute_id'       => $option->attribute_id,
                        'attribute_value_id' => $option->attribute_value_id,
                        'attribute'          => $option->attribute->name,
                        'attribute_value'    => $option->attributeValue->name,
                    ];
                }),
            ];
        });

        return $variants;
    }

    /**
     * @return mixed
     */
    public function variantImage($product)
    {
        return $product->variantImage;
    }

    /**
     * @return mixed
     */
    public function variantImageDelete($id)
    {
        $image = AttributeImage::find($id);

        return $image->delete();
    }

    /**
     * @return mixed
     */
    public function getProductAttributesByVariant($product)
    {
        $variants = $product->variants->map(function ($variant) {
            return $variant->options->map(function ($option) {
                return [
                    'attribute_id'       => $option->attribute_id,
                    'attribute_value_id' => $option->attribute_value_id,
                    'attribute'          => $option->attribute->name,
                    'attribute_value'    => $option->attributeValue->name,
                ];
            });
        });

        return $variants;
    }

    /**
     * @return mixed
     */
    public function getProductAttributeValuesIds($product)
    {
        $variants = $product->variants->map(function ($variant) {
            return $variant->options->map(function ($option) {
                return $option->attribute_value_id;
            });
        });

        return $variants;
    }

    /**
     * @param $request
     * @param $product
     */
    public function storeProductVariant($request, $product)
    {
        $colorVar = $request->color_image;
        $image    = $request->image;

        if ($image) {
            foreach ($image as $key => $img) {
                $var = $colorVar[$key];

                $varItem = AttributeValueTranslation::where('name', $var)->first();
                if ($varItem) {
                    $image = file_upload($img);
                    AttributeImage::create(
                        [
                            'attribute_value_id' => $varItem?->attributeValue->id,
                            'attribute_id'       => $varItem?->attributeValue->attribute_id,
                            'product_id'         => $product->id,
                            'image'              => $image,
                        ]
                    );
                }
            }
        }

        $variantData   = $request->variant;
        $sellingPrices = $request->price;
        $stockQuanties = $request->stock_qty;
        $skus          = $request->sku;

        foreach ($variantData as $key => $variantInfo) {
            // check if variant already exists
            $existingVariant = $product->variants->where('sku', $skus[$key])->first();

            if ($existingVariant) {
                continue;
            }

            $variantInfoArray = explode('-', $variantInfo);

            if ($request->is_default == $key) {
                Variant::where('product_id', $product->id)->where('is_default', 1)->first()?->update(['is_default' => 0]);
                $isDefault = 1;
            } else {
                $isDefault = 0;
            }

            // Insert variant into the variants table
            $variant = Variant::create([
                'product_id'       => $product->id,
                'sku'              => $skus[$key],
                'price'            => $sellingPrices[$key],
                'offer_price'      => $request->offer_price[$key],
                'offer_price_type' => $request->offer_Type[$key],
                'is_default'       => $isDefault,
            ]);

            Stock::create([
                'product_id' => $variant->product_id,
                'quantity'   => $stockQuanties[$key] ? $stockQuanties[$key] : 0,
                'sku'        => $variant->sku,
                'type'       => 'variant',
            ]);

            // Insert variant-specific information into the variant_attribute_values table
            foreach ($variantInfoArray as $attributeValue) {
                $attributeValueModel = AttributeValueTranslation::where('name', $attributeValue)->first();
                $attributeValueModel = $attributeValueModel->attributeValue;
                if ($attributeValueModel) {
                    VariantOption::create([
                        'variant_id'         => $variant->id,
                        'attribute_id'       => $attributeValueModel->attribute_id,
                        'attribute_value_id' => $attributeValueModel->id,
                    ]);
                }
            }
        }
    }

    /**
     * @param $variant_id
     */
    public function getProductVariantById($variant_id)
    {
        return Variant::with('options', 'optionValues')->find($variant_id);
    }

    /**
     * @return mixed
     */
    public function getProductVariant($variant_id)
    {
        return $this->getProductVariantById($variant_id);
    }

    /**
     * @return mixed
     */
    public function updateProductVariant($request, $variant)
    {
        $oldSku = $variant->sku;

        $oldStocks = $variant->manageStocks()->where('sku', $oldSku)->first();

        $oldStockQty = $oldStocks ? $oldStocks->quantity : 0;

        $data = [
            'price'             => $request->selling_price,
            'offer_price'       => $request->offer_price,
            'offer_price_start' => $request->offer_validity == 1 ? $request->offer_price_start : null,
            'offer_price_end'   => $request->offer_validity == 1 ? $request->offer_price_end : null,
            'offer_price_type'  => $request->offer_price_type,
            'sku'               => $request->sku,
            'is_default'        => $request->is_default,
        ];

        $variant->update($data);

        if ($variant->is_default == 1) {
            Variant::where('product_id', $variant->product_id)->where('id', '!=', $variant->id)->update(['is_default' => 0]);
        }

        if (!$oldStocks) {
            $oldStocks = Stock::create([
                'product_id' => $variant->product_id,
                'quantity'   => $request->filled('stock_qty') ? $request->stock_qty : 0,
                'sku'        => $request->sku,
                'type'       => 'variant',
            ]);
        }

        if ($oldStocks && $oldStockQty != $request->stock_qty) {
            $oldStocks->update([
                'quantity' => $request->stock_qty,
            ]);
        }

        if ($oldStocks && $oldSku != $request->sku) {
            $oldStocks->update([
                'sku' => $request->sku,
            ]);
        }

        return $variant;
    }

    /**
     * @return mixed
     */
    public function deleteProductVariant($variant)
    {
        $variant->options()->delete();

        $variant->manageStocks()->delete();

        return $variant->delete();
    }

    // public function bulkImport($request)
    // {
    //     $file = $request->file('file');

    //     $data = Excel::toCollection(null, $file);

    //     $data = $data->first()->slice(0);

    //     $data = $data->slice(1);

    //     $unsavedData = [];
    //     foreach ($data as $row) {
    //         $findProduct = $this->product->where(function ($q) use ($row) {
    //             $q->where('barcode', trim($row[2]));
    //         })->first();

    //         if ($findProduct) {
    //             $unsavedData[] = $row;

    //             continue;
    //         }

    //         // if category not found, create a new one

    //         $categoryName = trim($row[2]);

    //         $category = Category::where('name', $categoryName)->first();
    //         if ($categoryName && !$category) {
    //             $category = Category::create([
    //                 'name'   => $categoryName,
    //                 'status' => 1,
    //             ]);
    //         }

    //         // if brand not found, create a new one

    //         $brand_id = null;
    //         if (isset($row[5])) {
    //             $brandName = trim($row[5]);
    //             $brand     = ProductBrand::where('name', $brandName)->first();
    //             if ($brandName && !$brand) {
    //                 $brand = ProductBrand::create([
    //                     'name'   => $brandName,
    //                     'status' => '1',
    //                 ]);
    //             }

    //             $brand_id = $brand->id;
    //         }

    //         // if unit not found, create a new one

    //         $unitName = trim($row[4]);
    //         $unit     = UnitType::where('name', $unitName)->first();
    //         if ($unitName && !$unit) {
    //             $unit = UnitType::create([
    //                 'name'      => $unitName,
    //                 'ShortName' => $unitName,
    //                 'status'    => 1,
    //             ]);
    //         }

    //         // if product not found, create a new one

    //         // generate product sku
    //         $sku     = mt_rand(10000000, 99999999);
    //         $product = $this->product->create([
    //             'name'             => trim($row[0]),
    //             'sku'              => trim($row[1]) != null ? trim($row[1]) : $sku,
    //             'category_id'      => $category->id,
    //             'unit_type_id'     => $unit->id,
    //             'unit_sale_id'     => $unit->id,
    //             'unit_purchase_id' => $unit->id,
    //             'brand_id'         => $brand_id,
    //             'stock_alert'      => trim($row[6]),
    //             'barcode'          => trim($row[10]),
    //             'cost'             => trim($row[11]),
    //             'price'            => trim($row[12]),
    //             'stock'            => (trim($row[18]) == null || trim($row[18]) < 0) ? 0 : trim($row[18]),
    //             'status'           => 1,
    //             'images'           => ['null'],
    //         ]);

    //         // store product stock

    //         Stock::create([
    //             'product_id'     => $product->id,
    //             'date'           => now(),
    //             'type'           => '    Opening Stock',
    //             'in_quantity'    => (trim($row[18]) == null || trim($row[18]) < 0) ? 0 : trim($row[18]),
    //             'sku'            => $product->sku,
    //             'purchase_price' => 0,
    //             'rate'           => 0,
    //             'sale_price'     => 0,
    //             'created_by'     => auth('admin')->user()->id,
    //         ]);
    //     }
    // }

    /**
     * @return mixed
     */
    public function storeProductGallery($request, $product)
    {
        $images = $request->images;

        $product->images = $images;

        $product->save();

        return $product;
    }

    public function generateSku()
    {
        return generateSku();
    }

    public function generateBarcode()
    {
        return generateUniqueBarcode();
    }
}
