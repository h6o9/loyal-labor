<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'variant_id',
        'attribute_id',
        'attribute_value_id',
    ];

    /**
     * @var array
     */
    protected $appends = ['attribute_image_url'];

    public function getAttributeImageUrlAttribute()
    {
        $image = AttributeImage::where('attribute_id', $this->attribute_id)
            ->where('attribute_value_id', $this->attribute_value_id)
            ->whereHas('product', function ($query) {
                $query->where('id', $this->variant->product_id ?? null);
            })
            ->value('image');

        return asset($image);
    }

    /**
     * @return mixed
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * @return mixed
     */
    public function attributeImage()
    {
        return $this->hasOne(AttributeImage::class, null, null, null)->where(function ($query) {
            $query->whereColumn('attribute_images.attribute_id', 'variant_options.attribute_id')
                ->whereColumn('attribute_images.attribute_value_id', 'variant_options.attribute_value_id')
                ->whereColumn('attribute_images.product_id', 'variants.product_id');
        });
    }

    /**
     * @return mixed
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * @return mixed
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
