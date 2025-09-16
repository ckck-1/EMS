@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-calendar-check me-2"></i>My Events</h1>
            <p class="text-muted mb-0">Manage all your organized events</p>
        </div>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create New Event
        </a>
    </div>

    @if($events->count() > 0)
        <div class="row">
            @foreach($events as $event)
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="category-badge" style="background-color: {{ $event->category->color }}">
                                        {{ $event->category->name }}
                                    </span>
                                    @if($event->isPast())
                                        <span class="badge bg-secondary ms-2">Past</span>
                                    @elseif($event->isFull())
                                        <span class="badge bg-warning ms-2">Full</span>
                                    @else
                                        <span class="badge bg-success ms-2">Open</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('events.show', $event) }}">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('events.attendees', $event) }}">
                                            <i class="fas fa-users me-1"></i>Attendees ({{ $event->confirmed_attendees_count }})
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to cancel this event?')">
                                                    <i class="fas fa-trash me-1"></i>Cancel Event
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="row text-sm text-muted">
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
                                    <i class="fas fa-users me-1"></i>{{ $event->confirmed_attendees_count }} 
                                    @if($event->max_attendees)
                                        / {{ $event->max_attendees }}
                                    @endif
                                    attendees
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-calendar-plus fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">No Events Yet</h3>
                    <p class="text-muted">You haven't created any events yet. Start by creating your first event!</p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Event
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection