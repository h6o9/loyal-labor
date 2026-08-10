<?php

namespace Modules\Product\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Product\app\Models\Scopes\WebsiteProductScope;

class Category extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'parent_id',
        'status',
        'image',
        'icon',
        'type',
        'icon',
        'position',
        'is_searchable',
        'is_featured',
        'is_top',
        'is_popular',
        'is_trending',
    ];

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @return mixed
     */
    public function getNameAttribute(): ?string
    {
        $this->loadMissing('translation');

        return $this->translation->name;
    }

    /**
     * @return mixed
     */
    public function getSeoTitleAttribute(): ?string
    {
        $this->loadMissing('translation');
        return $this->translation->seo_title;
    }

    /**
     * @return mixed
     */
    public function getSeoDescriptionAttribute(): ?string
    {
        $this->loadMissing('translation');
        return $this->translation->seo_description;
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductCategory::class, 'category_id', 'id', 'id', 'product_id');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(CategoryTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?CategoryTranslation
    {
        return $this->hasOne(CategoryTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
    }

    /**
     * @return mixed
     */
    public function getDepthAttribute()
    {
        $depth  = 0;
        $parent = $this->parents;

        while ($parent?->id) {

            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    /**
     * @return mixed
     */
    public function parents()
    {
        return $this->belongsTo(Category::class, 'parent_id')
            ->with('parent');
    }

    protected static function booted()
    {
        static::addGlobalScope(new WebsiteProductScope());
    }
}
