<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * User roles constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_ORGANIZER = 'organizer';
    const ROLE_ATTENDEE = 'attendee';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is organizer
     */
    public function isOrganizer(): bool
    {
        return $this->role === self::ROLE_ORGANIZER;
    }

    /**
     * Check if user is attendee
     */
    public function isAttendee(): bool
    {
        return $this->role === self::ROLE_ATTENDEE;
    }

    /**
     * Events created by this user (for organizers)
     */
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Events this user has RSVP'd to
     */
    public function rsvpEvents()
    {
        return $this->belongsToMany(Event::class, 'rsvps')
            ->withTimestamps()
            ->withPivot('status');
    }

    /**
     * RSVPs made by this user
     */
    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }
}