<?php

namespace Modules\Crm\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_leads';

    protected $fillable = [
        'contact_id',
        'title',
        'description',
        'status',
        'estimated_value',
        'expected_close_date',
        'assigned_to',
        'source',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'expected_close_date' => 'date',
    ];

    /**
     * Get the contact associated with the lead.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the user assigned to this lead.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the deals associated with this lead.
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * Get all activities for this lead.
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }
}
