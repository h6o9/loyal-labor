<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductReview;

class OrderDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'user_id',
        'qty',
        'price',
        'total_price',
        'tax_amount',
        'options',
        'product_name',
        'product_thumbnail',
        'product_sku',
        'is_variant',
        'measurement',
        'weight',
        'commission_rate',
        'commission',
        'is_flash_deal',
    ];

    /**
     * @var string
     */
    protected $table = 'order_details';

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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'id', 'order_details_id');
    }
}
