<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Stock\app\Models\Stock;

class Variant extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'price',
        'offer_price',
        'offer_price_type',
        'offer_price_start',
        'offer_price_end',
        'is_default',
        'status',
        'image',
        'sku',
    ];

    /**
     * @var array
     */
    // protected $appends = ['stock_qty', 'attribute_ids', 'attribute_and_value_ids', 'attributes'];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed
     */
    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    /**
     * @return mixed
     */
    public function getDiscountAttribute(): object
    {
        $price                = (float) $this->price;
        $discountedPrice      = $price;
        $isDiscounted         = false;
        $discountedPercent    = 0;
        $variantPrice         = $price;
        $variantDiscount      = $this->offer_price;
        $variantDiscountType  = $this->offer_price_type;
        $variantDiscountEnd   = $this->offer_price_end;
        $variantDiscountStart = $this->offer_price_start;

        $discountAmount = $this->offer_price;
        $discountType   = $this->offer_price_type;
        $offerStart     = $this->offer_price_start;
        $offerEnd       = $this->offer_price_end;

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

            if ($isDiscounted) {
                $discountedPercent = round((($price - $discountedPrice) / $price) * 100);
            }
        }

        return (object) [
            'is_discounted'    => (bool) $isDiscounted,
            'price'            => $price,
            'discounted_price' => $discountedPrice,
            'discount_percent' => $discountedPercent,
            'original'         => [
                'price'           => $variantPrice,
                'discount_amount' => $variantDiscount,
                'discount_type'   => $variantDiscountType,
                'offer_start'     => $variantDiscountStart,
                'offer_end'       => $variantDiscountEnd,
            ],
        ];
    }

    /**
     * @return mixed
     */
    private function isOfferValid($offerStart, $offerEnd): bool
    {
        $now = Carbon::now();

        if ($offerStart == null) {
            $offerStart == now();
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

        if ($discountType === 'fixed' && $discountAmount !== 0) {
            $discountedPrice = (float) $discountAmount;
        } elseif ($discountType === 'percentage') {
            $discountedPrice = $price - ($price * ((float) $discountAmount / 100));
        }

        return round($discountedPrice, precision: 2);
    }

    /**
     * @return mixed
     */
    public function manageStocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'product_id')->where('type', 'variant');
    }

    /**
     * @return int
     */
    public function getStockQtyAttribute()
    {
        if (optional($this->product)->stock_status === 'out_of_stock') {
            return 0;
        }

        // Use the loaded relationship if available
        $stocks = $this->relationLoaded('manageStocks')
        ? $this->manageStocks
        : $this->manageStocks()->get();

        $stock = $stocks->firstWhere('sku', $this->sku);

        // fallback to any variant row if SKU not found (behaviour you already had)
        if (!$stock) {
            $stock = $stocks->firstWhere('type', 'variant');
        }

        return optional($stock)->quantity ?? 0;
    }

    /**
     * @return mixed
     */
    public function optionValues()
    {
        return $this->hasManyThrough(AttributeValue::class, VariantOption::class, 'variant_id', 'id', 'id', 'attribute_value_id');
    }

    /**
     * @return mixed
     */
    public function attributeImages()
    {
        return $this->hasMany(AttributeImage::class, 'product_id', 'product_id')
            ->whereIn('attribute_value_id', $this->optionValues->pluck('id'));
    }

    /**
     * @return mixed
     */
    public function getAttributeIdsAttribute()
    {
        return $this->options->pluck('attributeValue.attribute_id')->toArray();
    }

    /**
     * @return mixed
     */
    public function attributes()
    {
        // get attributes and values of this variant
        return $this->options->map(function ($option) {
            return $option->attributeValue->attribute->name . ': ' . $option->attributeValue->name;
        })->implode(', ');
    }

    // get attribute and attribute value ids
    /**
     * @return mixed
     */
    public function getAttributeAndValueIdsAttribute()
    {
        return $this->options->map(function ($option) {
            return [
                'attribute_id'       => $option->attributeValue->attribute_id,
                'attribute'          => $option->attributeValue->attribute->name,
                'attribute_value_id' => $option->attribute_value_id,
                'attribute_value'    => $option->attributeValue->name,
            ];
        });
    }

    /**
     * @return mixed
     */
    public function getAttributesAttribute()
    {
        return $this->options->map(function ($option) {
            return [
                'attribute_id'       => $option->attributeValue->attribute_id,
                'attribute'          => $option->attributeValue->attribute->name,
                'attribute_value_id' => $option->attribute_value_id,
                'attribute_value'    => $option->attributeValue->name,
            ];
        });
    }
}
