<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BrandTranslation extends Model
{
    use HasFactory;

    protected $table = 'brand_translations';

    protected $fillable = [
        'brand_id',
        'lang_code',
        'name',
        'description',
        'seo_title',
        'seo_description',
    ];

    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
}
