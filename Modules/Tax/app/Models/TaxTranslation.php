<?php

namespace Modules\Tax\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'blog_id',
        'lang_code',
        'title',
    ];

    public function tax(): ?BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
