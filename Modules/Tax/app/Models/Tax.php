<?php

namespace Modules\Tax\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Product\app\Models\Product;

class Tax extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['slug', 'percentage', 'status'];

    // make a accessor for translation
    /**
     * @return mixed
     */
    public function getTitleAttribute(): ?string
    {
        return $this->translation->title;
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tax');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(TaxTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @return mixed
     */
    public function getTranslation($code): ?TaxTranslation
    {
        return $this->hasOne(TaxTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(TaxTranslation::class, 'tax_id');
    }
}
