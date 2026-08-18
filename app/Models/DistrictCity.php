<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictCity extends Model
{
    protected $table = 'district_cities';

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
