<?php

namespace Modules\PageBuilder\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomizeablePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'status',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this?->translation?->title ?? '';
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this?->translation?->description ?? '';
    }

    public function translation(): ?HasOne
    {
        return $this->hasOne(CustomizablePageTranslation::class, 'customizeable_page_id')->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?CustomizablePageTranslation
    {
        return $this->hasOne(CustomizablePageTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany
    {
        return $this->hasMany(CustomizablePageTranslation::class, 'customizeable_page_id');
    }
}
