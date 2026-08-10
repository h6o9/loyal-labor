<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductLabel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'status',
    ];

    /**
     * @var array
     */
    protected $with = ['translation'];

    /**
     * @var array
     */
    // protected $appends = ['name'];

    /**
     * @return mixed
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => optional($this->loadMissing('translation')->translation)?->name,
        );
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_label_product');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(ProductLabelTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @return mixed
     */
    public function getTranslation($code): ?ProductLabelTranslation
    {
        return $this->hasOne(ProductLabelTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(ProductLabelTranslation::class, 'product_label_id');
    }
}
