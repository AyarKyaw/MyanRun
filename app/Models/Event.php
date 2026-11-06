<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- Add this import

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'name',
        'image_path',
        'date',
        'is_active',
        'location',
        'video_url', 
        'description', 
        'early_bird_limit', 
        'allow_athlete_edit', 
        'allow_edit_required', 
        'allow_edit_personal', 
        'allow_edit_contact', 
        'allow_edit_participant',
    ];

    /**
     * Relationship: The admins assigned to this event.
     */
    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'admin_event');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event', 'name'); 
    }

    public function ticketTypes()
    {
        return $this->hasMany(EventTicketType::class);
    }

    public function agents()
    {
        return $this->belongsToMany(\App\Models\Agent::class, 'agent_event');
    }

    protected $casts = [
        'date' => 'date',

        'allow_athlete_edit' => 'boolean', 
        'allow_edit_required' => 'boolean', 
        'allow_edit_personal' => 'boolean', 
        'allow_edit_contact' => 'boolean', 
        'allow_edit_participant' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(Ticket::class, 'event_id'); 
    }

    public function eventTicketTypes(): HasMany
    {
        return $this->hasMany(EventTicketType::class, 'event_id');
    }
}