<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $guarded = [];
	protected $table = 'districts';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cities()
    {
        return $this->hasMany(DistrictCity::class, 'district_id');
    }
}
