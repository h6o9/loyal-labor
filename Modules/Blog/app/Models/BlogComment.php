<?php

namespace Modules\Blog\app\Models;

use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model
{
    use HasFactory, GlobalActiveScopeTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'comment',
        'name',
        'parent_id',
        'user_id',
        'blog_id',
        'email',
        'image',
        'is_admin',
        'status',
    ];

    /**
     * @return mixed
     */
    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function post(): ?BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    /**
     * @return mixed
     */
    public function parent(): ?BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id')->where('status', 1);
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('status', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAll($id)
    {
        return $this->where('blog_id', $id)->get();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithNested($query)
    {
        return $query->with(['children' => function ($query) {
            $query->withNested();
        }]);
    }
}
