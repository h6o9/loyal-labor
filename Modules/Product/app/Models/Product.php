<?php

namespace Modules\Product\app\Models;

use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;
use Modules\Product\app\Models\Scopes\WebsiteProductScope;
use Modules\Stock\app\Models\Stock;
use Modules\Tax\app\Models\Tax;

class Product extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'brand_id',
        'vendor_id',
        'unit_type_id',
        'thumbnail_image',
        'video_link',
        'is_cash_delivery',
        'is_return',
        'return_policy_id',
        'is_featured',
        'is_popular',
        'is_best_selling',
        'allow_checkout_when_out_of_stock',
        'price',
        'offer_price',
        'offer_price_type',
        'offer_price_start',
        'offer_price_end',
        'is_flash_deal',
        'flash_deal_image',
        'flash_deal_start',
        'flash_deal_end',
        'flash_deal_price',
        'flash_deal_qty',
        'manage_stock',
        'stock_status',
        'sku',
        'barcode',
        'status',
        'tax_id',
        'length',
        'wide',
        'height',
        'weight',
        'viewed',
        'theme',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'images'     => 'array',
        'attributes' => 'array',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'name',
        'image_url',
        'short_description',
        'description',
    ];

    /**
     * @var array
     */
    protected $with = ['translation'];

    /**
     * @param  $value
     * @return mixed
     */
    public function getThumbnailImageAttribute($value)
    {
        $path = public_path($value);

        if ($value && File::exists($path)) {
            return $value;
        }

        return getSettings('default_avatar');
    }

    /**
     * @param  $value
     * @return mixed
     */
    public function getFlashDealImageAttribute($value)
    {
        $finalImage = getSettings('default_avatar');

        if ($value && File::exists(public_path($value))) {
            $finalImage = $value;
        } elseif ($this->thumbnail_image && File::exists(public_path($this->thumbnail_image))) {
            $finalImage = $this->thumbnail_image;
        }

        return $finalImage;
    }

    public function getSingleImageAttribute()
    {
        $imageUrl = $this->getImagesUrlAttribute();
        if ($imageUrl && file_exists(public_path($imageUrl))) {
            return asset($imageUrl);
        }

        return asset(getSettings('default_avatar'));
    }

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        $this->loadMissing('translation');
        return $this->translation->name ?? '';
    }

    /**
     * @return mixed
     */
    public function getDescriptionAttribute(): ?string
    {
        return optional($this->loadMissing('translation')->translation)?->description ?? '';
    }

    /**
     * @return mixed
     */
    public function getShortDescriptionAttribute(): ?string
    {
        return optional($this->loadMissing('translation')->translation)?->short_description ?? '';
    }

    /**
     * @return mixed
     */
    public function getIsFlashDealActiveAttribute(): bool
    {
        if (!$this->is_flash_deal || !$this->flash_deal_start || !$this->flash_deal_end) {
            return false;
        }

        $today = now()->startOfDay();

        return $today->between(
            \Carbon\Carbon::parse($this->flash_deal_start)->startOfDay(),
            \Carbon\Carbon::parse($this->flash_deal_end)->endOfDay()
        );
    }

    /**
     * @param  $value
     * @return mixed
     */
    public function getManageStockAttribute($value)
    {
        if ($this->is_flash_deal_active && $this->flash_deal_qty > 0) {
            return 1;
        }

        return $value;
    }

    /**
     * @param  $value
     * @return mixed
     */
    public function getStockStatusAttribute($value)
    {
        if ($this->is_flash_deal_active) {
            return $this->flash_deal_qty > 0 ? 'in_stock' : 'out_of_stock';
        }

        return $value;
    }

    // /**
    //  * @param  $value
    //  * @return mixed
    //  */
    // public function getAllowCheckoutWhenOutOfStockAttribute($value)
    // {
    //     if ($this->is_flash_deal_active) {
    //         return $this->flash_deal_qty > 0 ? 1 : 0;
    //     }

    //     return $value;
    // }

    /**
     * @return mixed
     */
    public function getHasDefaultVariantAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return false;
        }

        $this->loadMissing('variants');

        return $this->variants->contains('is_default', true);
    }

    /**
     * @return mixed
     */
    public function getDefaultVariantStockQtyAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return 0;
        }

        $this->loadMissing('variants');

        $defaultVariant = $this->variants->firstWhere('is_default', true);

        if (!$defaultVariant) {
            return 0;
        }

        $defaultVariant->loadMissing('manageStocks');

        return $defaultVariant->stock_qty ?? 0;
    }

    /**
     * @return mixed
     */
    public function getDefaultVariantIdAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return null;
        }

        $this->loadMissing('variants');

        if ($this->is_flash_deal_active) {
            return null;
        }

        if ($this->has_variant) {
            $variant = $this->variants->where('is_default', 1)->first();

            if ($variant) {
                return $variant->id;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getDefaultVariantPriceAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return null;
        }

        $this->loadMissing('variants');

        if ($this->has_variant) {
            $variant = $this->variants->where('is_default', 1)->first();
            if ($variant) {
                return $variant->discount?->price;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getDefaultVariantDiscountPriceAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return null;
        }

        $this->loadMissing('variants');

        if ($this->has_variant) {
            $variant = $this->variants->where('is_default', 1)->first();
            if ($variant && $variant->discount->is_discounted) {
                return $variant->discount?->discounted_price;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getDefaultVariantSkuAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return null;
        }

        $this->loadMissing('variants');

        if ($this->has_variant) {
            $variant = $this->variants->where('is_default', 1)->first();
            if ($variant) {
                return $variant->sku;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getDiscountedPriceAttribute()
    {
        if ($this->is_flash_deal_active) {
            $price           = (float) $this->price;
            $flashPrice      = (float) $this->flash_deal_price;
            $discountedPrice = $flashPrice;
            $percent         = 0;

            if ($price > 0 && $flashPrice < $price) {
                $percent = round((($price - $flashPrice) / $price) * 100);
            }

            return (object) [
                'price'            => $price,
                'discounted_price' => $discountedPrice,
                'is_discounted'    => true,
                'discount_percent' => $percent,
            ];
        }

        $price             = (float) $this->price;
        $discountedPrice   = $price;
        $isDiscounted      = false;
        $discountedPercent = 0;

        $discountAmount = $this->offer_price;
        $discountType   = $this->offer_price_type;
        $offerStart     = $this->offer_price_start;
        $offerEnd       = $this->offer_price_end;

        if ($this->has_variant) {
            $this->loadMissing('variants');
            $varPrice = $this->variants->where('is_default', 1)->first();

            if ($varPrice) {
                $isDiscounted      = $varPrice->discount->is_discounted;
                $discountedPrice   = $varPrice->discount->discounted_price;
                $discountedPercent = $varPrice->discount->discount_percent;
                $price             = $varPrice->discount->price;

                return (object) [
                    'price'            => $price,
                    'discounted_price' => $discountedPrice,
                    'is_discounted'    => (bool) $isDiscounted,
                    'discount_percent' => $discountedPercent,
                ];
            }
        }

        $checkAmount = (float) $discountAmount;

        if ($checkAmount > 0) {
            $hasDate = $offerStart || $offerEnd;
            if ($hasDate) {
                $isOfferValid = $this->isOfferValid($offerStart, $offerEnd);
            } else {
                $isOfferValid = true;
            }

            if ($isOfferValid) {
                $discountedPrice = $this->calculateDiscountedPrice($price, $discountType, $discountAmount);

                $isDiscounted = true;
            }

            if ($price > 0 && $discountedPrice < $price) {
                $discountedPercent = round((($price - $discountedPrice) / $price) * 100);
            }
        }

        return (object) [
            'price'            => $price,
            'discounted_price' => $discountedPrice,
            'is_discounted'    => (bool) $isDiscounted,
            'discount_percent' => $discountedPercent,
        ];
    }

    // make a attribute to count total tax on the product
    /**
     * @return mixed
     */
    public function getTotalTaxAttribute()
    {
        $this->loadMissing('taxes');

        return $this->taxes->sum('percentage');
    }

    /**
     * @return mixed
     */
    public function getHasVariantAttribute(): bool
    {
        if ($this->is_flash_deal_active) {
            return false;
        }

        return $this->variants->count() > 0;
    }

    /**
     * @return mixed
     */
    public function getActualPriceAttribute()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getImagesUrlAttribute()
    {
        $image = $this->image;
        if (gettype($image) == 'array') {
            return false;
        }

        return $image;
    }

    /**
     * @return mixed
     */
    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getStockQtyAttribute()
    {
        if ($this->is_flash_deal_active) {
            return (int) $this->flash_deal_qty;
        }

        $this->loadMissing('manageStocks');

        if ($this->stock_status == 'out_of_stock') {
            return 0;
        }

        return optional($this->manageStocks)->quantity ?: 0;
    }

    /**
     * @return mixed
     */
    public function getTaxPercentageAttribute()
    {
        $this->loadMissing('taxes');

        if ($this->taxes->count() == 0) {
            return 0;
        }

        return $this->taxes->sum('percentage');
    }

    /**
     * @return mixed
     */
    public function getAttributeAndValuesAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return false;
        }

        $attributeItems = collect();

        foreach ($this->variants as $variant) {
            foreach ($variant->options as $option) {
                $attributeItems->push([
                    'is_default'         => $variant->is_default,
                    'attribute_id'       => $option->attribute_id,
                    'attribute_value_id' => $option->attribute_value_id,
                    'attribute'          => $option->attribute->name,
                    'attribute_slug'     => $option->attribute->slug,
                    'image'              => $option->attribute_image_url,
                    'attribute_value'    => $option->attributeValue->name,
                ]);
            }
        }

        return $attributeItems
            ->unique('attribute') // get unique attributes
            ->values()
            ->map(function ($attribute) use ($attributeItems) {
                $values = $attributeItems
                    ->where('attribute', $attribute['attribute'])
                    ->unique('attribute_value_id')
                    ->values()
                    ->map(function ($item) {
                        return [
                            'id'         => $item['attribute_value_id'],
                            'value'      => $item['attribute_value'],
                            'is_default' => $item['is_default'],
                            'image'      => $item['image'],
                        ];
                    })
                    ->toArray();

                return [
                    'attribute_id'     => $attribute['attribute_id'],
                    'attribute'        => $attribute['attribute'],
                    'attribute_slug'   => $attribute['attribute_slug'],
                    'attribute_values' => $values,
                ];
            });
    }

    // get all variants price and sku with attribute value ids
    /**
     * @return mixed
     */
    public function getVariantsPriceAndSkuAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return [];
        }

        if (!$this->relationLoaded('variants')) {
            $this->loadMissing('variants');
        }

        if ($this->variants->isEmpty()) {
            return [];
        }

        $this->loadMissing([
            'variants.options.attributeValue',
            'variants.manageStocks',
        ]);

        $variantsPriceAndSku = [];

        foreach ($this->variants as $variant) {
            $options = $variant->options;

            $attribute_value_ids = $options->pluck('attribute_value_id')->toArray();
            $variant_images      = $options->pluck('attribute_image_url')->toArray();

            // Combine attribute_value_id => image
            $attribute_images = [];
            foreach ($attribute_value_ids as $index => $valueId) {
                $attribute_images[$valueId] = $variant_images[$index] ?? null;
            }

            $variantsPriceAndSku[$variant->id] = [
                'price'                              => $variant->price,
                'currency_price'                     => currency($variant->price),
                'sku'                                => $variant->sku,
                'discount'                           => $variant->discount ?? null,
                'discounted_currency_discount_price' => currency($variant?->discount?->discounted_price ?? $variant->price),
                'discounted_currency_price'          => currency($variant?->discount?->price ?? $variant->price),
                'stock'                              => optional($variant->manageStocks)->sum('quantity'),
                'attribute_value_ids'                => $attribute_value_ids,
                'attribute_images'                   => $attribute_images,
            ];
        }

        return $variantsPriceAndSku;
    }

    /**
     * @return mixed
     */
    public function getSelectableVariantsAttribute()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return [];
        }

        if (!$this->relationLoaded('variants')) {
            $this->loadMissing('variants');
        }

        if ($this->variants->isEmpty()) {
            return [];
        }

        $this->loadMissing([
            'variants.options',
        ]);

        $variants = [];

        foreach ($this->variants as $variant) {
            $options = $variant->options;

            $attribute_value_ids = $options->pluck('attribute_value_id')->toArray();

            // Get just one image to represent the variant
            $image = $options->pluck('attribute_image_url')->filter()->first();

            $variants[] = [
                'attribute_value_ids'                => $attribute_value_ids,
                'sku'                                => $variant->sku,
                'price'                              => $variant->price,
                'stock_qty'                          => $variant->stock_qty,
                'currency_price'                     => currency($variant->price),
                'discount'                           => $variant->discount ?? null,
                'discounted_currency_discount_price' => currency($variant?->discount?->discounted_price ?? $variant->price),
                'discounted_currency_price'          => currency($variant?->discount?->price ?? $variant->price),
                'is_default'                         => $variant->is_default,
                'image'                              => $image,
            ];
        }

        return $variants;
    }

    /**
     * @return mixed
     */
    public function getVariantsWithAttributes()
    {
        if ($this->is_flash_deal_active || !$this->has_variant) {
            return [];
        }

        if (!$this->relationLoaded('variants')) {
            $this->loadMissing('variants');
        }

        if ($this->variants->isEmpty()) {
            return [];
        }

        $this->loadMissing([
            'variants.options.attributeValue.attribute',
        ]);

        $variantsWithAttributes = [];

        foreach ($this->variants as $variant) {

            foreach ($variant->options as $variantOption) {
                $attributeValue = $variantOption->attributeValue;
                $attribute      = $attributeValue->attribute;

                $variantsWithAttributes[$variant->id][] = [
                    'attribute'      => $attribute->name,
                    'attribute_slug' => $attribute->slug,
                    'value'          => $attributeValue->name,
                    'value_id'       => $attributeValue->id,
                ];
            }
        }

        return $variantsWithAttributes;
    }

    /**
     * @param $value
     */
    public function getImagesAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     */
    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = json_encode($value);
    }

    /**
     * @param $value
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = json_encode($value);
    }

    /**
     * @param $value
     */
    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = json_encode($value);
    }

    /**
     * @return mixed
     */
    private function isOfferValid($offerStart, $offerEnd): bool
    {
        $now = Carbon::now();

        if ($offerStart == null) {
            $offerStart = $now;
        } else {
            if ($now->lt(Carbon::parse($offerStart))) {
                return false;
            }
        }

        return $offerStart && $offerEnd && $now->between(Carbon::parse($offerStart), Carbon::parse($offerEnd));
    }

    /**
     * @param float $price
     */
    private function calculateDiscountedPrice($price, $discountType, $discountAmount): float
    {
        if ($discountType == 'fixed') {
            $discountedPrice = (float) $discountAmount;
        } elseif ($discountType == 'percentage') {
            $discountedPrice = $price - ($price * ((float) $discountAmount / 100));
        }

        return round($discountedPrice, precision: 2);
    }

    /**
     * @return mixed
     */
    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_tax');
    }

    /**
     * @return mixed
     */
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'product_id', 'id');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(ProductTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @return mixed
     */
    public function getTranslation($code): ?ProductTranslation
    {
        return $this->translations->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(ProductTranslation::class, 'product_id');
    }

    /**
     * @return mixed
     */
    public function labels(): ?BelongsToMany
    {
        return $this->belongsToMany(ProductLabel::class, 'product_label_product');
    }

    /**
     * @return mixed
     */
    public function variantImage(): ?HasMany
    {
        return $this->hasMany(AttributeImage::class, 'product_id');
    }

    /**
     * @return mixed
     */
    public function categories(): ?BelongsToMany
    {
        return $this->belongsToMany(Category::class, ProductCategory::class, 'product_id', 'category_id');
    }

    /**
     * @return mixed
     */
    public function brand(): ?BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * @return mixed
     */
    public function tags(): ?BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    /**
     * @return mixed
     */
    public function unit(): ?BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id', 'id');
    }

    /**
     * @return mixed
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_id', 'id');
    }

    /**
     * @return mixed
     */
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            OrderDetails::class,
            'product_id',
            'id',
            'id',
            'order_id'
        );
    }

    /**
     * @return mixed
     */
    public function relatedProducts(): ?HasMany
    {
        return $this->hasMany(RelatedProduct::class, 'product_id', 'id');
    }

    /**
     * @return mixed
     */
    public function manageStocks(): ?HasOne
    {
        return $this->hasOne(Stock::class)->whereNull('type');
    }

    /**
     * @return mixed
     */
    public function variants(): ?HasMany
    {
        return $this->hasMany(Variant::class, 'product_id', 'id')->orderByDesc('is_default');
    }

    /**
     * @return mixed
     */
    public function reviews(): ?HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id');
    }

    /**
     * @return mixed
     */
    public function productReturnPolicy(): ?BelongsTo
    {
        return $this->belongsTo(ProductTos::class, 'return_policy_id');
    }

    /**
     * @return mixed
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    /**
     * @return mixed
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // scope is_approved
    /**
     * @param  $query
     * @return mixed
     */
    public function scopeIsApproved($query)
    {
        return $query->where('is_approved', 1);
    }

    /**
     * @param  $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * @param  $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where(['status' => 1, 'is_approved' => 1]);
    }

    protected static function booted()
    {
        static::addGlobalScope(new WebsiteProductScope());
    }
}
