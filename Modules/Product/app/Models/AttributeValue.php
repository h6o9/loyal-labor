<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'attribute_values';

    /**
     * @var array
     */
    protected $fillable = ['has_thumbnail', 'thumbnail', 'attribute_id', 'order'];

    /**
     * @var array
     */
    protected $appends = ['name'];

    /**
     * @return mixed
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * @return mixed
     */
    public function getNameAttribute(): ?string
    {
        return $this->translation?->name;
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(AttributeValueTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getTranslation($code): ?AttributeValueTranslation
    {
        return $this->hasOne(AttributeValueTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(AttributeValueTranslation::class, 'attribute_value_id');
    }
}
