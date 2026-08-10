<?php

namespace Modules\KnowYourClient\app\Models;

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\KnowYourClient\app\Enums\KYCStatusEnum;

class KycInformation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kyc_type_id',
        'user_id',
        'vendor_id',
        'admin_id',
        'message',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'status'      => KYCStatusEnum::class,
    ];

    /**
     * @return mixed
     */
    public function type()
    {
        return $this->belongsTo(KycType::class, 'kyc_type_id');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function shop()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * @return mixed
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
