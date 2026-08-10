<?php

namespace Modules\GlobalSetting\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'value',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('setting');
        });

        static::created(function () {
            Cache::forget('setting');
        });

        static::updated(function () {
            Cache::forget('setting');
        });

        static::deleted(function () {
            Cache::forget('setting');
        });
    }
}
