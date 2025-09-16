<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer']);

        // Filter by upcoming/past
        if ($request->has('filter')) {
            if ($request->filter === 'upcoming') {
                $query->upcoming();
            } elseif ($request->filter === 'past') {
                $query->past();
            }
        }

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $events = $query->orderBy('date', 'asc')->paginate(20);

        return EventResource::collection($events);
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['category', 'organizer', 'rsvps']);
        return new EventResource($event);
    }

    /**
     * RSVP to an event
     */
    public function rsvp(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if user already has an RSVP
        $existingRsvp = $event->rsvps()->where('user_id', $user->id)->first();

        if ($existingRsvp) {
            return response()->json([
                'message' => 'You have already RSVP\'d to this event.'
            ], 422);
        }

        // Check if event is full
        if ($event->isFull()) {
            return response()->json([
                'message' => 'This event is full.'
            ], 422);
        }

        // Check if event is in the past
        if ($event->isPast()) {
            return response()->json([
                'message' => 'Cannot RSVP to past events.'
            ], 422);
        }

        $rsvp = Rsvp::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => Rsvp::STATUS_CONFIRMED,
        ]);

        return response()->json([
            'message' => 'Successfully RSVP\'d to the event!',
            'rsvp_id' => $rsvp->id
        ], 201);
    }

    /**
     * Cancel RSVP to an event
     */
    public function cancelRsvp(Request $request, Event $event)
    {
        $user = Auth::user();

        $rsvp = $event->rsvps()->where('user_id', $user->id)->first();

        if (!$rsvp) {
            return response()->json([
                'message' => 'You have not RSVP\'d to this event.'
            ], 422);
        }

        $rsvp->delete();

        return response()->json([
            'message' => 'RSVP cancelled successfully!'
        ]);
    }

    /**
     * Get user's RSVPs
     */
    public function myRsvps(Request $request)
    {
        $user = Auth::user();
        $rsvps = $user->rsvpEvents()
            ->with(['category', 'organizer'])
            ->orderBy('date', 'asc')
            ->paginate(20);

        return EventResource::collection($rsvps);
    }
}