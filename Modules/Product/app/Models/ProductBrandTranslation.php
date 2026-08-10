<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrandTranslation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_brand_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'product_brand_id', 'lang_code',
    ];

    /**
     * @return mixed
     */
    public function productBrand()
    {
        return $this->belongsTo(Brand::class);
    }
}
