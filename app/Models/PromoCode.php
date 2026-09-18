<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoCode extends Model
{
    protected $fillable = [
        'group_name',
        'code',
        'amount',
        'prefix',
        'event_id',
        'is_used',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function eventTicketTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            EventTicketType::class,
            'promo_code_event_ticket_type', // name of pivot table
            'promo_code_id',
            'event_ticket_type_id'
        );
    }
}