<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOrganizer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(?User $user, Event $event): bool
    {
        return true; // Events are public
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->isOrganizer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->isAdmin() || ($user->isOrganizer() && $event->organizer_id === $user->id);
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->isAdmin() || ($user->isOrganizer() && $event->organizer_id === $user->id);
    }

    /**
     * Determine whether the user can RSVP to the event.
     */
    public function rsvp(User $user, Event $event): bool
    {
        return !$event->isPast() && !$event->isFull();
    }

    /**
     * Determine whether the user can view attendees.
     */
    public function viewAttendees(User $user, Event $event): bool
    {
        return $user->isAdmin() || ($user->isOrganizer() && $event->organizer_id === $user->id);
    }
}