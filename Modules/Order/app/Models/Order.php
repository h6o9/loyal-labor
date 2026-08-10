<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Product\app\Models\ProductReview;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id'];

    /**
     * @var array
     */
    protected $casts = [
        'order_status' => OrderStatus::class,
    ];

    /**
     * @var array
     */
    protected $with = ['paymentDetails'];

    /**
     * @var array
     */
    protected $appends = [
        'payable_currency',
        'payable_amount_without_rate',
        'paid_amount',
        'transaction_id',
        'payment_method',
        'payment_status',
    ];

    /**
     * @return mixed
     */
    public function getPayableCurrencyAttribute()
    {
        return $this->paymentDetails->payable_currency ?? null;
    }

    /**
     * @return mixed
     */
    public function getPayableAmountWithoutRateAttribute()
    {
        return $this->paymentDetails->payable_amount_without_rate ?? null;
    }

    /**
     * @return mixed
     */
    public function getPayableAmountAttribute()
    {
        return $this->paymentDetails->payable_amount ?? null;
    }

    /**
     * @return mixed
     */
    public function getPaidAmountAttribute()
    {
        return $this->paymentDetails->paid_amount ?? null;
    }

    /**
     * @return mixed
     */
    public function getTransactionIdAttribute()
    {
        return $this->paymentDetails->transaction_id ?? null;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethodAttribute()
    {
        return $this->paymentDetails->payment_method ?? null;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatusAttribute()
    {
        return $this->paymentDetails->payment_status ?? null;
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(OrderDetails::class);
    }

    /**
     * @return mixed
     */
    public function shippingAddress()
    {
        return $this->belongsTo(OrderShippingAddress::class, 'id', 'order_id');
    }

    /**
     * @return mixed
     */
    public function billingAddress()
    {
        return $this->belongsTo(OrderBillingAddress::class, 'id', 'order_id');
    }

    /**
     * @return mixed
     */
    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }

    /**
     * @return mixed
     */
    public function orderStatusHistory()
    {
        return $this->hasMany(OrderStatusChangeHistory::class)->where('type', 'order_status');
    }

    /**
     * @return mixed
     */
    public function paymentStatusHistory()
    {
        return $this->hasMany(OrderStatusChangeHistory::class)->where('type', 'payment_status');
    }

    /**
     * @param $type
     * @param $from_status
     * @param $to_status
     */
    public function addOrderHistory($type, $from_status)
    {
        if (!in_array($type, ['order_status', 'payment_status'])) {
            return;
        }

        try {
            $newOrderStatusHistory              = new OrderStatusChangeHistory();
            $newOrderStatusHistory->order_id    = $this->id;
            $newOrderStatusHistory->type        = $type;
            $newOrderStatusHistory->from_status = $from_status;
            $newOrderStatusHistory->to_status   = $this->$type->value;
            $newOrderStatusHistory->save();
        } catch (\Exception $e) {
            logError(
                'Status History Error, Order id: ' . $this->id . ' - ' . $e->getMessage(),
                $e
            );
        }
    }

    /**
     * @return mixed
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'order_id');
    }

    /**
     * @return mixed
     */
    public function paymentDetails()
    {
        return $this->belongsTo(OrderPaymentDetails::class, 'order_payment_details_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });

        static::created(function ($model) {
            try {
                $newOrderStatusHistory              = new OrderStatusChangeHistory();
                $newOrderStatusHistory->order_id    = $model->id;
                $newOrderStatusHistory->type        = 'order_status';
                $newOrderStatusHistory->from_status = 'pending';
                $newOrderStatusHistory->to_status   = 'pending';
                $newOrderStatusHistory->save();

                $newPaymentStatusHistory              = new OrderStatusChangeHistory();
                $newPaymentStatusHistory->order_id    = $model->id;
                $newPaymentStatusHistory->type        = 'payment_status';
                $newPaymentStatusHistory->from_status = 'pending';
                $newPaymentStatusHistory->to_status   = 'pending';
                $newPaymentStatusHistory->save();
            } catch (\Exception $e) {
                logError(
                    'Status History Error, Order id: ' . $model->id . ' - ' . $e->getMessage(),
                    $e
                );
            }

        });
    }

}
