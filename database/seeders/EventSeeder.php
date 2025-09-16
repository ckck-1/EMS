<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizers = User::where('role', User::ROLE_ORGANIZER)->get();
        $categories = Category::all();

        $events = [
            [
                'title' => 'Laravel Developer Conference 2024',
                'description' => 'Join us for the biggest Laravel conference of the year featuring talks from core team members and industry experts.',
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'time' => '09:00:00',
                'location' => 'Tech Convention Center, San Francisco',
                'max_attendees' => 500,
            ],
            [
                'title' => 'Vue.js Workshop',
                'description' => 'A hands-on workshop covering Vue.js 3 composition API and best practices.',
                'date' => Carbon::now()->addDays(15)->toDateString(),
                'time' => '14:00:00',
                'location' => 'CodeHub, New York',
                'max_attendees' => 50,
            ],
            [
                'title' => 'PHP Meetup Monthly',
                'description' => 'Monthly PHP meetup with lightning talks and networking.',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'time' => '18:30:00',
                'location' => 'DevSpace, Austin',
                'max_attendees' => 100,
            ],
            [
                'title' => 'Database Optimization Seminar',
                'description' => 'Learn advanced database optimization techniques for high-traffic applications.',
                'date' => Carbon::now()->addDays(45)->toDateString(),
                'time' => '10:00:00',
                'location' => 'University Tech Center, Seattle',
                'max_attendees' => 200,
            ],
            [
                'title' => 'Startup Networking Event',
                'description' => 'Connect with fellow entrepreneurs and investors in the tech startup scene.',
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'time' => '19:00:00',
                'location' => 'Innovation Hub, Boston',
                'max_attendees' => 150,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, [
                'organizer_id' => $organizers->random()->id,
                'category_id' => $categories->random()->id,
            ]));
        }

        // Create some past events
        $pastEvents = [
            [
                'title' => 'React Developer Meetup',
                'description' => 'Monthly React meetup with talks about hooks and performance optimization.',
                'date' => Carbon::now()->subDays(10)->toDateString(),
                'time' => '18:00:00',
                'location' => 'TechSpace, Denver',
                'max_attendees' => 80,
            ],
            [
                'title' => 'DevOps Workshop',
                'description' => 'Workshop on Docker, Kubernetes, and CI/CD best practices.',
                'date' => Carbon::now()->subDays(25)->toDateString(),
                'time' => '09:00:00',
                'location' => 'Cloud Center, Portland',
                'max_attendees' => 60,
            ],
        ];

        foreach ($pastEvents as $eventData) {
            Event::create(array_merge($eventData, [
                'organizer_id' => $organizers->random()->id,
                'category_id' => $categories->random()->id,
            ]));
        }
    }
}