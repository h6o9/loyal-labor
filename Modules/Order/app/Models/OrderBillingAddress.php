<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBillingAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id'];

    /**
     * @var array
     */
    protected $appends = ['full_address'];

    /**
     * @return mixed
     */
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]));
    }

    public function order()
    {
        $this->belongsTo(Order::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
