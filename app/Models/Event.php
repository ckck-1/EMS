<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'organizer_id',
        'date',
        'time',
        'location',
        'image',
        'max_attendees',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    /**
     * Event belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Event belongs to an organizer (User)
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Users who have RSVP'd to this event
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'rsvps')
            ->withTimestamps()
            ->withPivot('status');
    }

    /**
     * RSVPs for this event
     */
    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->date >= now()->toDateString();
    }

    /**
     * Check if event is past
     */
    public function isPast(): bool
    {
        return $this->date < now()->toDateString();
    }

    /**
     * Get confirmed attendees count
     */
    public function getConfirmedAttendeesCountAttribute()
    {
        return $this->rsvps()->where('status', 'confirmed')->count();
    }

    /**
     * Check if event is full
     */
    public function isFull(): bool
    {
        if (!$this->max_attendees) {
            return false;
        }

        return $this->confirmed_attendees_count >= $this->max_attendees;
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString());
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
}