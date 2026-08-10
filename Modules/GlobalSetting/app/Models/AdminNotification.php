<?php

namespace Modules\GlobalSetting\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdminNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'message',
        'link',
        'type',
        'is_read',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('admin-notifications');
        });

        static::created(function () {
            Cache::forget('admin-notifications');
        });

        static::updated(function () {
            Cache::forget('admin-notifications');
        });

        static::deleted(function () {
            Cache::forget('admin-notifications');
        });
    }
}
