<?php

namespace Modules\GlobalSetting\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['css', 'header_javascript', 'body_javascript', 'footer_javascript'];
}
