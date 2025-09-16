@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-primary text-white p-4 rounded-3 mb-4">
                <h1 class="display-4 mb-3">
                    <i class="fas fa-calendar-alt me-3"></i>Discover Amazing Events
                </h1>
                <p class="lead mb-0">Connect with your community and join exciting events happening around you.</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('home') }}" class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search Events</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by title or description...">
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="row">
            @foreach($events as $event)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top event-image" alt="{{ $event->title }}">
                        @else
                            <div class="card-img-top event-image bg-primary d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-calendar-alt fa-3x"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="category-badge" style="background-color: {{ $event->category->color }}">
                                    {{ $event->category->name }}
                                </span>
                                @if($event->isFull())
                                    <span class="badge bg-warning ms-2">Full</span>
                                @endif
                            </div>
                            
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="mt-auto">
                                <div class="row text-muted small mb-3">
                                    <div class="col-6">
                                        <i class="fas fa-calendar me-1"></i>{{ $event->date->format('M j, Y') }}
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-clock me-1"></i>{{ $event->time->format('g:i A') }}
                                    </div>
                                    <div class="col-12 mt-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                    </div>
                                    <div class="col-12 mt-1">
                                        <i class="fas fa-user me-1"></i>by {{ $event->organizer->name }}
                                    </div>
                                    <div class="col-12 mt-1">
                                        <i class="fas fa-users me-1"></i>{{ $event->confirmed_attendees_count }} 
                                        @if($event->max_attendees)
                                            / {{ $event->max_attendees }}
                                        @endif
                                        attendees
                                    </div>
                                </div>
                                
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">No Events Found</h3>
                    <p class="text-muted">There are no upcoming events matching your criteria.</p>
                    @auth
                        @can('viewOrganizer')
                            <a href="{{ route('events.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create First Event
                            </a>
                        @endcan
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>
@endsection