<?php

namespace Modules\Crm\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_deals';

    protected $fillable = [
        'contact_id',
        'lead_id',
        'title',
        'description',
        'stage',
        'amount',
        'expected_close_date',
        'actual_close_date',
        'probability',
        'assigned_to',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
    ];

    /**
     * Get the contact associated with the deal.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the lead associated with the deal.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user assigned to this deal.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all activities for this deal.
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    /**
     * Determine if the deal is closed.
     */
    public function isClosed()
    {
        return in_array($this->stage, ['Closed Won', 'Closed Lost']);
    }

    /**
     * Determine if the deal is won.
     */
    public function isWon()
    {
        return $this->stage === 'Closed Won';
    }

    /**
     * Determine if the deal is lost.
     */
    public function isLost()
    {
        return $this->stage === 'Closed Lost';
    }
}
