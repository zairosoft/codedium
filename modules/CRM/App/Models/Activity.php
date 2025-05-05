<?php

namespace Modules\Crm\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_activities';

    protected $fillable = [
        'type',
        'title',
        'description',
        'due_date',
        'completed_at',
        'status',
        'assigned_to',
        'priority',
        'activityable_id',
        'activityable_type',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user assigned to this activity.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the parent model (contact, lead, or deal).
     */
    public function activityable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include pending activities.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include completed activities.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope a query to only include cancelled activities.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'Cancelled');
    }

    /**
     * Scope a query to only include overdue activities.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'Pending')
            ->where('due_date', '<', now());
    }

    /**
     * Scope a query to only include upcoming activities.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'Pending')
            ->where('due_date', '>=', now());
    }

    /**
     * Scope a query to only include today's activities.
     */
    public function scopeToday($query)
    {
        return $query->where('status', 'Pending')
            ->whereDate('due_date', today());
    }

    /**
     * Mark the activity as completed.
     */
    public function complete()
    {
        $this->completed_at = now();
        $this->status = 'Completed';
        $this->save();

        return $this;
    }

    /**
     * Mark the activity as cancelled.
     */
    public function cancel()
    {
        $this->status = 'Cancelled';
        $this->save();

        return $this;
    }
}
