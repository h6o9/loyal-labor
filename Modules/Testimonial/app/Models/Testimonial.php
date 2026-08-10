<?php

namespace Modules\Testimonial\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Testimonial extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'image',
        'rating',
        'status',
    ];

    /**
     * @return mixed
     */
    public function getNameAttribute(): ?string
    {
        return $this->translation->name;
    }

    /**
     * @return mixed
     */
    public function getDesignationAttribute(): ?string
    {
        return $this->translation->designation;
    }

    /**
     * @return mixed
     */
    public function getCommentAttribute(): ?string
    {
        return $this->translation->comment;
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(TestimonialTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getTranslation($code): ?TestimonialTranslation
    {
        return $this->hasOne(TestimonialTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(TestimonialTranslation::class, 'testimonial_id');
    }
}
