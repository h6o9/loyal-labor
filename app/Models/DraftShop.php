<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftShop extends Model
{
    protected $guarded = [];
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}