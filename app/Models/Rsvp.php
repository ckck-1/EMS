<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    use HasFactory;

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];

    /**
     * RSVP belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RSVP belongs to an event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if RSVP is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if RSVP is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if RSVP is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}