<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'is_required'];

    protected $appends = ['name'];

    public function getNameAttribute(): ?string
    {
        return $this->translation?->name;
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function translation(): ?HasOne
    {
        return $this->hasOne(AttributeTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?AttributeTranslation
    {
        return $this->hasOne(AttributeTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany
    {
        return $this->hasMany(AttributeTranslation::class, 'attribute_id');
    }
}
