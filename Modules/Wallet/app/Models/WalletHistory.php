<?php

namespace Modules\Wallet\app\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Product;

class WalletHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['transaction_type'];

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
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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

    /**
     * @return mixed
     */

    public function withdrawRequest()
    {
        return $this->belongsTo(WithdrawRequest::class);
    }
}
