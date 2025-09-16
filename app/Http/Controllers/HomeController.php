<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer'])
            ->upcoming()
            ->orderBy('date', 'asc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $events = $query->paginate(12);
        $categories = Category::all();

        return view('home', compact('events', 'categories'));
    }

    /**
     * Show past events
     */
    public function pastEvents(Request $request)
    {
        $query = Event::with(['category', 'organizer'])
            ->past()
            ->orderBy('date', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $events = $query->paginate(12);
        $categories = Category::all();

        return view('events.past', compact('events', 'categories'));
    }
}