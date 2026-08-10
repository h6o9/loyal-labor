<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttributeTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'attribute_id',
        'lang_code',
        'name',
    ];

    public function attribute(): ?HasOne
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }
}
