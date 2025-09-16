<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Rsvp;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $this->authorize('viewAdmin');

        // Statistics
        $totalEvents = Event::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalRsvps = Rsvp::where('status', 'confirmed')->count();
        $upcomingEvents = Event::upcoming()->count();
        $pastEvents = Event::past()->count();

        // Recent events
        $recentEvents = Event::with(['category', 'organizer'])
            ->latest()
            ->take(5)
            ->get();

        // Popular categories
        $popularCategories = Category::withCount('events')
            ->orderBy('events_count', 'desc')
            ->take(5)
            ->get();

        // Monthly event statistics (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'events' => Event::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'rsvps' => Rsvp::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'confirmed')
                    ->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalUsers',
            'totalCategories',
            'totalRsvps',
            'upcomingEvents',
            'pastEvents',
            'recentEvents',
            'popularCategories',
            'monthlyStats'
        ));
    }
}