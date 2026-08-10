<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * @return mixed
     */
    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'id', 'vendor_id');
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
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
