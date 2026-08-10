<?php

namespace Modules\PageBuilder\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizablePageTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customizeable_page_id',
        'lang_code',
        'title',
        'description',
    ];

    public function customizeablePage()
    {
        return $this->belongsTo(CustomizeablePage::class, 'customizeable_page_id');
    }
}
