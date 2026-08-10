<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\app\Models\Product;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'wishlists';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'user_id',
    ];

    /**
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
