<?php

namespace Modules\GlobalSetting\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CustomPagination extends Model
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
            Cache::forget('CustomPagination');
        });

        static::created(function () {
            Cache::forget('CustomPagination');
        });

        static::updated(function () {
            Cache::forget('CustomPagination');
        });

        static::deleted(function () {
            Cache::forget('CustomPagination');
        });
    }
}
