<?php

namespace Modules\BasicPayment\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentGateway extends Model
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
            Cache::forget('payment_setting');
        });

        static::created(function () {
            Cache::forget('payment_setting');
        });

        static::updated(function () {
            Cache::forget('payment_setting');
        });

        static::deleted(function () {
            Cache::forget('payment_setting');
        });
    }
}
