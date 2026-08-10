<?php

namespace Modules\Frontend\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Frontend\app\Enums\ManageThemeEnum;

class Home extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['slug'];

    /**
     * @return mixed
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'home_id');
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
