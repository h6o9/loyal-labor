<?php

namespace Modules\Frontend\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Modules\Frontend\app\Enums\ManageThemeEnum;
use Modules\Language\app\Models\Language;

class SectionTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['section_id', 'lang_code', 'content'];

    /**
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * @return mixed
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * @return mixed
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_code', 'code');
    }

    /**
     * Accessor to decode JSON content when retrieving.
     */
    public function getContentAttribute($value): ?object
    {
        return json_decode($value);
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::cleanCache();
        });

        static::created(function () {
            self::cleanCache();
        });

        static::updated(function () {
            self::cleanCache();
        });

        static::deleted(function () {
            self::cleanCache();
        });
    }

    private static function cleanCache()
    {
        foreach (allLanguages() as $language) {
            foreach (ManageThemeEnum::cases() as $theme) {
                Cache::forget($theme->value . '_home_section_data_' . $language->code);
            }
        }
    }
}
