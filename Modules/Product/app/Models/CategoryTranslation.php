<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'lang_code',
        'name',
        'seo_title',
        'seo_description',
    ];

    /**
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
