<?php

namespace Modules\Crm\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'status',
        'lead_score',
        'last_contact',
        'assigned_to',
        'notes',
    ];

    protected $casts = [
        'tags' => 'array',
        'lead_score' => 'integer',
        'last_contact' => 'date',
    ];

    /**
     * Get the formatted tags for this contact.
     *
     * @return array
     */
    public function getTagsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        return is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Set the tags for this contact.
     *
     * @param  array|string  $value
     * @return void
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = is_array($value) ? json_encode($value) : $value;
    }
}
