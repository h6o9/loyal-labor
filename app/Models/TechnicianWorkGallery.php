<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianWorkGallery extends Model
{
    protected $guarded = [];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
