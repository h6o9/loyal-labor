<?php

namespace Modules\Frontend\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Modules\Frontend\app\Enums\ManageThemeEnum;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['home_id', 'name', 'global_content', 'status'];

    /**
     * @var array
     */
    protected $casts = [
        'global_content' => 'array',
    ];

    /**
     * @var array
     */
    protected $with = ['translation'];

    /**
     * @param $value
     */
    public function getGlobalContentAttribute($value): ?object
    {
        return json_decode($value);
    }

    /**
     * @return mixed
     */
    public function getContentAttribute(): ?object
    {
        return $this->translation?->content;
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(SectionTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @return mixed
     */
    public function getTranslation($code): ?SectionTranslation
    {
        return $this->hasOne(SectionTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(SectionTranslation::class, 'section_id');
    }

    /**
     * @return mixed
     */
    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class, 'home_id');
    }

    /**
     * @return mixed
     */
    public static function getByName(string $section_name, string | int $theme_name = 1)
    {
        if ($theme_name == 1) {
            $theme_name = config('services.theme');
        }

        $home = Home::firstOrCreate(['slug' => $theme_name]);

        $section = self::firstOrCreate(['name' => $section_name, 'home_id' => $home->id]);

        return $section;
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
