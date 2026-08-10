<?php

namespace Modules\GlobalSetting\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('SeoSetting');
        });

        static::created(function () {
            Cache::forget('SeoSetting');
        });

        static::updated(function () {
            Cache::forget('SeoSetting');
        });

        static::deleted(function () {
            Cache::forget('SeoSetting');
        });
    }
}
