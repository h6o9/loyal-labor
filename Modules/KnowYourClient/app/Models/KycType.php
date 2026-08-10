<?php

namespace Modules\KnowYourClient\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return mixed
     */
    public function kycApplications()
    {
        return $this->hasMany(KycInformation::class, 'kyc_type_id');
    }
}
