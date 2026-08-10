<?php

namespace Modules\Order\app\Models;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Http\Enums\PaymentStatus;

class OrderStatusChangeHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'from_status',
        'to_status',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'from_status_enum',
        'to_status_enum',
    ];

    /**
     * @return string
     */
    public function getToStatusEnumAttribute()
    {
        return $this->type == 'order_status' ? OrderStatus::tryFrom($this->to_status) : PaymentStatus::tryFrom($this->to_status);
    }

    /**
     * @return mixed
     */
    public function getFromStatusEnumAttribute()
    {
        return $this->type == 'order_status' ? OrderStatus::tryFrom($this->from_status) : PaymentStatus::tryFrom($this->from_status);
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
    public function changedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'changed_by_id');
    }

    /**
     * @return mixed
     */
    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by_id');
    }
}
