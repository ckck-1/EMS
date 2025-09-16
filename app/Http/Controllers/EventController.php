<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Category;
use App\Models\Rsvp;
use App\Notifications\EventUpdatedNotification;
use App\Notifications\EventCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events for the current organizer
     */
    public function index()
    {
        $this->authorize('viewAny', Event::class);

        $events = Auth::user()->organizedEvents()
            ->with(['category'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created event
     */
    public function store(StoreEventRequest $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validated();
        $validated['organizer_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['category', 'organizer', 'rsvps.user']);
        
        $userRsvp = null;
        if (Auth::check()) {
            $userRsvp = $event->rsvps()->where('user_id', Auth::id())->first();
        }

        return view('events.show', compact('event', 'userRsvp'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified event
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        // Notify all attendees about the update
        $attendees = $event->attendees()->get();
        foreach ($attendees as $attendee) {
            $attendee->notify(new EventUpdatedNotification($event));
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        // Notify all attendees about the cancellation
        $attendees = $event->attendees()->get();
        foreach ($attendees as $attendee) {
            $attendee->notify(new EventCancelledNotification($event));
        }

        // Delete image
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event cancelled successfully!');
    }

    /**
     * RSVP to an event
     */
    public function rsvp(Request $request, Event $event)
    {
        $this->authorize('rsvp', $event);

        $user = Auth::user();

        // Check if user already has an RSVP
        $existingRsvp = $event->rsvps()->where('user_id', $user->id)->first();

        if ($existingRsvp) {
            return back()->with('error', 'You have already RSVP\'d to this event.');
        }

        // Check if event is full
        if ($event->isFull()) {
            return back()->with('error', 'This event is full.');
        }

        // Check if event is in the past
        if ($event->isPast()) {
            return back()->with('error', 'Cannot RSVP to past events.');
        }

        Rsvp::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => Rsvp::STATUS_CONFIRMED,
        ]);

        return back()->with('success', 'Successfully RSVP\'d to the event!');
    }

    /**
     * Cancel RSVP to an event
     */
    public function cancelRsvp(Request $request, Event $event)
    {
        $user = Auth::user();

        $rsvp = $event->rsvps()->where('user_id', $user->id)->first();

        if (!$rsvp) {
            return back()->with('error', 'You have not RSVP\'d to this event.');
        }

        $rsvp->delete();

        return back()->with('success', 'RSVP cancelled successfully!');
    }

    /**
     * Show attendees for an event (organizers only)
     */
    public function attendees(Event $event)
    {
        $this->authorize('viewAttendees', $event);

        $attendees = $event->rsvps()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('events.attendees', compact('event', 'attendees'));
    }
}