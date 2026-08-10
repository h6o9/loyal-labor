<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TagTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tag_id',
        'lang_code',
        'name',
    ];

    public $timestamps = false;

    public function tag(): ?HasOne
    {
        return $this->hasOne(Tag::class);
    }
}
