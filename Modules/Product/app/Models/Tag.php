<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['slug'];

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        return $this->translation->name;
    }

    /**
     * @return mixed
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(TagTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?TagTranslation
    {
        return $this->hasOne(TagTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(TagTranslation::class, 'tag_id');
    }
}
