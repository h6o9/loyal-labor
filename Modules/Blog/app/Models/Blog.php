<?php

namespace Modules\Blog\app\Models;

use App\Models\Admin;
use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Blog extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'blog_category_id',
        'slug',
        'views',
        'show_homepage',
        'is_popular',
        'tags',
        'status',
    ];

    /**
     * @return mixed
     */
    public function getTitleAttribute(): ?string
    {
        $this->loadMissing('translation');

        return $this->translation->title ?? '';
    }

    /**
     * @return mixed
     */
    public function getDescriptionAttribute(): ?string
    {
        $this->loadMissing('translation');

        return $this->translation->description ?? '';
    }

    /**
     * @return mixed
     */
    public function getSeoTitleAttribute(): ?string
    {
        $this->loadMissing('translation');

        return $this->translation->seo_title ?? '';
    }

    /**
     * @return mixed
     */
    public function getSeoDescriptionAttribute(): ?string
    {
        $this->loadMissing('translation');
        return $this->translation->seo_description ?? '';
    }

    /**
     * @return mixed
     */
    public function category(): ?BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * @return mixed
     */
    public function admin(): ?BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(BlogTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?BlogTranslation
    {
        return $this->hasOne(BlogTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(BlogTranslation::class, 'blog_id');
    }

    /**
     * @return mixed
     */
    public function comments(): ?HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    /**
     * @param  $query
     * @return mixed
     */
    public function scopeHomepage($query)
    {
        return $query->where('show_homepage', 1);
    }

    /**
     * @param  $query
     * @return mixed
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }
}
