<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $table = 'product_translations';

    protected $fillable = [
        'product_id',
        'lang_code',
        'name',
        'description',
        'short_description',
        'seo_title',
        'seo_description',
    ];

    public $timestamps = false;

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
