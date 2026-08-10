<?php

namespace Modules\Product\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Brand extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * @var string
     */
    protected $table = 'brands';

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'is_featured',
        'icon',
        'image',
        'status',
    ];

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
    public function getDescriptionAttribute(): ?string
    {
        $this->loadMissing('translation');
        return $this->translation->description;
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
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(BrandTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?BrandTranslation
    {
        return $this->hasOne(BrandTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(BrandTranslation::class, 'brand_id');
    }
}
