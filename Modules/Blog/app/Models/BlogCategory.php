<?php

namespace Modules\Blog\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlogCategory extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * @var array
     */
    protected $fillable = ['slug', 'status'];

    /**
     * @return mixed
     */
    public function getTitleAttribute(): ?string
    {
        return $this->translation->title;
    }

    /**
     * @return mixed
     */
    public function getShortDescriptionAttribute(): ?string
    {
        return $this->translation->short_description;
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(BlogCategoryTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getTranslation($code): ?BlogCategoryTranslation
    {
        return $this->hasOne(BlogCategoryTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(BlogCategoryTranslation::class, 'blog_category_id');
    }

    /**
     * @return mixed
     */
    public function posts()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
