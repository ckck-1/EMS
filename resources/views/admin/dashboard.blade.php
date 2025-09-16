@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
            <p class="text-muted mb-0">System overview and statistics</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <div class="h4 mb-0">{{ $totalEvents }}</div>
                            <div class="text-muted">Total Events</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <div class="h4 mb-0">{{ $totalUsers }}</div>
                            <div class="text-muted">Total Users</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <div class="h4 mb-0">{{ $upcomingEvents }}</div>
                            <div class="text-muted">Upcoming Events</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-ticket-alt fa-2x text-info"></i>
                        </div>
                        <div class="ms-3">
                            <div class="h4 mb-0">{{ $totalRsvps }}</div>
                            <div class="text-muted">Total RSVPs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Events -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Recent Events</h5>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentEvents->count() > 0)
                        @foreach($recentEvents as $event)
                            <div class="d-flex align-items-center mb-3 @if(!$loop->last) border-bottom pb-3 @endif">
                                <div class="flex-shrink-0">
                                    <span class="category-badge" style="background-color: {{ $event->category->color }}">
                                        {{ $event->category->name }}
                                    </span>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">
                                        By {{ $event->organizer->name }} • {{ $event->date->format('M j, Y') }}
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-light text-dark">{{ $event->confirmed_attendees_count }} RSVPs</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No events found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Popular Categories</h5>
                </div>
                <div class="card-body">
                    @if($popularCategories->count() > 0)
                        @foreach($popularCategories as $category)
                            <div class="d-flex align-items-center mb-3 @if(!$loop->last) border-bottom pb-2 @endif">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle" 
                                         style="width: 20px; height: 20px; background-color: {{ $category->color }}"></div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="fw-medium">{{ $category->name }}</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-light text-dark">{{ $category->events_count }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No categories found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Events Created</th>
                                    <th>RSVPs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ $stat['month'] }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $stat['events'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $stat['rsvps'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection