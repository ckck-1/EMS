<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Conference',
                'description' => 'Professional conferences and summits',
                'color' => '#007bff'
            ],
            [
                'name' => 'Workshop',
                'description' => 'Hands-on learning workshops',
                'color' => '#28a745'
            ],
            [
                'name' => 'Meetup',
                'description' => 'Casual networking meetups',
                'color' => '#ffc107'
            ],
            [
                'name' => 'Seminar',
                'description' => 'Educational seminars and presentations',
                'color' => '#17a2b8'
            ],
            [
                'name' => 'Networking',
                'description' => 'Professional networking events',
                'color' => '#6f42c1'
            ],
            [
                'name' => 'Social',
                'description' => 'Social gatherings and parties',
                'color' => '#e83e8c'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}