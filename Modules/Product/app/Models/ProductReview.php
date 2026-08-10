<?php

namespace Modules\Product\app\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'order_details_id',
        'vendor_id',
        'rating',
        'review',
        'status',
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
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
    public function seller()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return mixed
     */
    public function orderDetails()
    {
        return $this->belongsTo(OrderDetails::class);
    }
}
