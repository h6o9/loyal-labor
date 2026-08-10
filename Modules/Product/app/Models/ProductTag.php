<?php

namespace Modules\Product\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'tag_id',
    ];

    protected $table = 'product_tags';

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
