<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin that performed the activity.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the subject of the activity.
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Log an activity
     */
    public static function log(string $action, $subject = null, string $description = null, array $properties = [])
    {
        $admin = auth('admin')->user();
        
        if (!$admin) {
            return null;
        }

        return static::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
        ]);
    }

    /**
     * Scope to get activities by a specific admin
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope to get activities by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to get activities within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get badge class for action
     */
    public function getActionBadgeClass()
    {
        $badgeClasses = [
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'login' => 'login',
            'logout' => 'logout',
        ];

        return $badgeClasses[$this->action] ?? 'unknown';
    }
}
