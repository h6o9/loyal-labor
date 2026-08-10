<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Order\app\Http\Enums\PaymentStatus;

class OrderPaymentDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payable_amount_without_rate',
        'payable_amount',
        'payable_currency',
        'paid_amount',
        'transaction_id',
        'payment_details',
        'payment_method',
        'payment_status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'payment_status' => PaymentStatus::class,
    ];

    /**
     * @return mixed
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_payment_details_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
