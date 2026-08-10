<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'path',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /**
     * @var string
     */
    protected function getUrlAttribute()
    {
        return asset($this->path);
    }
}
