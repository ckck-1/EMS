<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Rsvp;
use Illuminate\Database\Seeder;

class RsvpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendees = User::where('role', User::ROLE_ATTENDEE)->get();
        $events = Event::all();

        // Create random RSVPs for events
        foreach ($events as $event) {
            // Randomly select 20-70% of attendees to RSVP to each event
            $rsvpCount = rand(
                (int)($attendees->count() * 0.2),
                (int)($attendees->count() * 0.7)
            );

            $selectedAttendees = $attendees->random($rsvpCount);

            foreach ($selectedAttendees as $attendee) {
                Rsvp::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'status' => Rsvp::STATUS_CONFIRMED,
                ]);
            }
        }
    }
}