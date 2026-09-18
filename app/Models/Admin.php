<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role', // Added role here
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship: The events assigned to this admin.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'admin_event');
    }

    /**
     * Role Helper: Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Role Helper: Check if user is Event Admin
     */
    public function isEventAdmin(): bool
    {
        return $this->role === 'event_admin';
    }

    /**
     * Role Helper: Check if user is Finance Admin
     */
    public function isFinanceAdmin(): bool
    {
        return $this->role === 'finance_admin';
    }
    
    public function isSupporter() {
        return $this->role === 'supporter';
    }
}