<?php

namespace Modules\Product\app\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $question
 * @property mixed $answer
 * @property int|mixed $status
 * @property int|mixed|null $vendor_id
 * @property mixed $id
 */
class ProductTos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'vendor_id',
        'question',
        'answer',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'return_policy_id');
    }

    /**
     * @return mixed
     */
    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'id', 'vendor_id');
    }
}
