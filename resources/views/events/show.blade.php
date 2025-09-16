@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" style="height: 300px; object-fit: cover;" alt="{{ $event->title }}">
                @endif
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="category-badge" style="background-color: {{ $event->category->color }}">
                                {{ $event->category->name }}
                            </span>
                            @if($event->isPast())
                                <span class="badge bg-secondary ms-2">Past Event</span>
                            @elseif($event->isFull())
                                <span class="badge bg-warning ms-2">Full</span>
                            @endif
                        </div>
                        
                        @auth
                            @can('update', $event)
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i> Manage
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                            <i class="fas fa-edit me-1"></i>Edit Event
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('events.attendees', $event) }}">
                                            <i class="fas fa-users me-1"></i>View Attendees
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to cancel this event? All attendees will be notified.')">
                                                    <i class="fas fa-trash me-1"></i>Cancel Event
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endcan
                        @endauth
                    </div>
                    
                    <h1 class="card-title">{{ $event->title }}</h1>
                    <p class="text-muted mb-4">Organized by {{ $event->organizer->name }}</p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar text-primary me-2"></i>Date & Time</h6>
                            <p>{{ $event->date->format('l, F j, Y') }} at {{ $event->time->format('g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Location</h6>
                            <p>{{ $event->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-users text-primary me-2"></i>Attendees</h6>
                            <p>{{ $event->confirmed_attendees_count }} 
                                @if($event->max_attendees)
                                    / {{ $event->max_attendees }}
                                @endif
                                confirmed
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-tag text-primary me-2"></i>Category</h6>
                            <p>{{ $event->category->name }}</p>
                        </div>
                    </div>
                    
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Description</h6>
                    <div class="mb-4">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- RSVP Card -->
            @auth
                <div class="card mb-4">
                    <div class="card-body text-center">
                        @if($event->isPast())
                            <h5 class="text-muted">This event has ended</h5>
                        @elseif($userRsvp)
                            <h5 class="text-success">
                                <i class="fas fa-check-circle me-2"></i>You're attending!
                            </h5>
                            <p class="text-muted">RSVP'd on {{ $userRsvp->created_at->format('M j, Y') }}</p>
                            <form method="POST" action="{{ route('events.rsvp.cancel', $event) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-1"></i>Cancel RSVP
                                </button>
                            </form>
                        @elseif($event->isFull())
                            <h5 class="text-warning">
                                <i class="fas fa-users me-2"></i>Event is Full
                            </h5>
                            <p class="text-muted">This event has reached its maximum capacity.</p>
                        @else
                            <h5>Join this event</h5>
                            <p class="text-muted">RSVP to secure your spot</p>
                            <form method="POST" action="{{ route('events.rsvp', $event) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-plus me-1"></i>RSVP Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5>Join this event</h5>
                        <p class="text-muted">Please login to RSVP</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-1"></i>Login to RSVP
                        </a>
                    </div>
                </div>
            @endguest
            
            <!-- Event Details Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info me-2"></i>Event Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <small class="text-muted">Created</small><br>
                            <span>{{ $event->created_at->format('M j, Y') }}</span>
                        </div>
                        @if($event->updated_at != $event->created_at)
                            <div class="col-12">
                                <small class="text-muted">Last Updated</small><br>
                                <span>{{ $event->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection