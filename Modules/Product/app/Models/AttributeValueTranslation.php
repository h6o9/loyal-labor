<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttributeValueTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'attribute_value_id',
        'lang_code',
        'name',
    ];

    public function attributeValue(): ?HasOne
    {
        return $this->hasOne(AttributeValue::class, 'id', 'attribute_value_id');
    }
}
