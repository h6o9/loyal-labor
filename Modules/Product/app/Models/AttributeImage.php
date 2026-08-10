<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'image',
    ];

    /**
     * @var string
     */
    protected $table = 'attribute_images';

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
