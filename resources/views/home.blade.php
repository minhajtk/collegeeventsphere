@extends('layouts.app')

@section('title', 'EventSphere - Centralized College Event System')

@section('content')
<!-- Hero Section with Background Images -->
<div class="hero-section">
    <div style="max-width: 760px;">
        <span style="display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.35rem 0.95rem; background: #000000; border: 1px solid var(--border-glow); border-radius: var(--radius-full); font-family: var(--font-sub); font-size: 0.78rem; font-weight: 700; color: #ffffff; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.04em;">
            <i class="fa-solid fa-sparkles" style="color: var(--secondary);"></i> Official Campus Event Portal
        </span>
        <h1 class="hero-title">Experience Campus Events with Real-Time Access</h1>
        <p class="hero-subtitle">
            Say goodbye to scattered notices. EventSphere connects students, faculty, and organizers with instant event schedules, real-time venue seating, automated waitlists, and digital QR passes.
        </p>

        <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
            <a href="{{ route('events.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-compass"></i> Explore Events
            </a>
            <a href="{{ route('gallery.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-photo-film"></i> Media Gallery
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fa-solid fa-user-plus"></i> Join as Organizer / Student
                </a>
            @endguest
        </div>
    </div>
</div>

<!-- Campus Announcements Banner -->
@if(isset($announcements) && $announcements->count() > 0)
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem 1.75rem; margin-bottom: 2.75rem; box-shadow: var(--shadow-glow);">
        <div style="display: flex; align-items: center; gap: 0.65rem; font-family: var(--font-funky); font-weight: 800; color: #ffffff; font-size: 1.05rem; margin-bottom: 0.85rem; text-transform: uppercase;">
            <i class="fa-solid fa-bullhorn" style="color: var(--warning);"></i>
            <span>Campus Announcements & Alerts</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
            @foreach($announcements as $announcement)
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); padding: 0.8rem 1.15rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); flex-wrap: wrap; gap: 0.5rem;">
                    <span style="font-family: var(--font-sub); font-weight: 700; font-size: 0.9rem; color: var(--text-main);">{{ $announcement->title }}</span>
                    <span style="font-size: 0.78rem; color: var(--text-muted); font-family: var(--font-sub);">{{ $announcement->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Upcoming Events Section -->
<div style="margin-bottom: 3.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.75rem;">
        <div>
            <h2 style="font-size: 1.65rem; margin-bottom: 0.25rem;">Upcoming College Events</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; font-family: var(--font-sub);">Browse upcoming technical, cultural, and sports competitions</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    @if($upcomingEvents->count() > 0)
        <div class="event-grid">
            @foreach($upcomingEvents as $event)
                <div class="event-card">
                    <div class="event-card-img" style="background-image: url('{{ $event->banner_url }}'); background-size: cover; background-position: center;">
                        <span class="category-badge"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                        @if($event->isFull())
                            <span class="slot-badge full">Waitlist Active</span>
                        @else
                            <span class="slot-badge">{{ $event->available_slots }} Slots Left</span>
                        @endif
                    </div>

                    <div class="event-card-body">
                        <h3 class="event-card-title"><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.55; margin-bottom: 1rem;">
                            {{ Str::limit(strip_tags($event->description), 85) }}
                        </p>

                        <div class="event-meta">
                            <div class="meta-item"><i class="fa-regular fa-calendar-check" style="color: #ffffff;"></i> {{ $event->start_date->format('M d, Y • h:i A') }}</div>
                            <div class="meta-item"><i class="fa-solid fa-location-dot" style="color: var(--secondary);"></i> {{ $event->venue }}</div>
                            <div class="meta-item"><i class="fa-solid fa-building-user" style="color: var(--accent);"></i> {{ $event->organizing_department ?? 'Campus Authority' }}</div>
                        </div>

                        <div class="capacity-container">
                            <div class="capacity-header">
                                <span>Capacity</span>
                                <span>{{ $event->capacity - $event->available_slots }} / {{ $event->capacity }} Reserved</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: {{ (($event->capacity - $event->available_slots) / max(1, $event->capacity)) * 100 }}%;"></div>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem;">
                            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                                View Details & Register
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1.5rem; background: var(--bg-surface); border: 1px dashed var(--border-color); border-radius: var(--radius-lg);">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 2.5rem; color: var(--text-dim); margin-bottom: 0.75rem; display: block;"></i>
            <h3 style="color: var(--text-muted); font-family: var(--font-sub); font-size: 1.1rem;">No upcoming events currently scheduled</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.35rem;">Check back soon or explore past fests in our media gallery!</p>
        </div>
    @endif
</div>

<!-- Join as an Organizer Feature Callout Section -->
<div style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.12) 0%, rgba(59, 130, 246, 0.08) 100%); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: var(--radius-lg); padding: 2.25rem; margin-bottom: 3.5rem;">
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: center;" class="organizer-callout-grid">
        <div>
            <span style="display: inline-block; padding: 0.25rem 0.75rem; background: rgba(168, 85, 247, 0.2); border-radius: var(--radius-full); font-family: var(--font-sub); font-size: 0.72rem; font-weight: 700; color: #c084fc; text-transform: uppercase; margin-bottom: 0.75rem;">
                <i class="fa-solid fa-briefcase"></i> For Faculty & Student Clubs
            </span>
            <h2 style="font-size: 1.65rem; margin-bottom: 0.6rem; color: #ffffff;">Want to Host Events on EventSphere?</h2>
            <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem;">
                Faculty coordinators, department heads, and club presidents can join as <strong>Event Organizers</strong>. Submit proposals, manage registration caps, scan attendance with our live QR scanner, and issue official e-certificates directly to attendees.
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-user-tie"></i> Register as an Organizer
                </a>
                <a href="{{ route('about') }}" class="btn btn-secondary btn-sm">
                    Learn More
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
            <div style="background: rgba(0,0,0,0.5); padding: 1.15rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.1);">
                <i class="fa-solid fa-file-circle-plus" style="font-size: 1.35rem; color: #4ade80; margin-bottom: 0.4rem; display: block;"></i>
                <h4 style="font-size: 0.92rem; margin-bottom: 0.2rem;">Easy Proposals</h4>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Quick submission & admin approval</p>
            </div>
            <div style="background: rgba(0,0,0,0.5); padding: 1.15rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.1);">
                <i class="fa-solid fa-qrcode" style="font-size: 1.35rem; color: #60a5fa; margin-bottom: 0.4rem; display: block;"></i>
                <h4 style="font-size: 0.92rem; margin-bottom: 0.2rem;">QR Scanning</h4>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Real-time check-in validation</p>
            </div>
            <div style="background: rgba(0,0,0,0.5); padding: 1.15rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.1);">
                <i class="fa-solid fa-users" style="font-size: 1.35rem; color: #facc15; margin-bottom: 0.4rem; display: block;"></i>
                <h4 style="font-size: 0.92rem; margin-bottom: 0.2rem;">Auto Waitlist</h4>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Dynamic seat allocation</p>
            </div>
            <div style="background: rgba(0,0,0,0.5); padding: 1.15rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.1);">
                <i class="fa-solid fa-certificate" style="font-size: 1.35rem; color: #c084fc; margin-bottom: 0.4rem; display: block;"></i>
                <h4 style="font-size: 0.92rem; margin-bottom: 0.2rem;">E-Certificates</h4>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Instant batch generation</p>
            </div>
        </div>
    </div>
</div>

<!-- Event Categories Section -->
<div style="margin-bottom: 3.5rem;">
    <h2 style="font-size: 1.65rem; margin-bottom: 0.25rem;">Explore Event Categories</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; font-family: var(--font-sub); margin-bottom: 1.5rem;">Find fests and workshops tailored to your interests</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.15rem;">
        @foreach($categories as $cat)
            <a href="{{ route('events.index', ['category' => $cat->id]) }}" style="display: flex; align-items: center; gap: 1rem; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.1rem; transition: var(--transition);" onmouseover="this.style.borderColor='#ffffff';" onmouseout="this.style.borderColor='var(--border-color)';">
                <div style="width: 46px; height: 46px; background: #000000; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa-solid fa-{{ $cat->icon ?? 'star' }}" style="font-size: 1.15rem; color: #ffffff;"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.98rem; color: var(--text-main); margin-bottom: 0.15rem;">{{ $cat->name }}</h4>
                    <span style="font-family: var(--font-sub); font-size: 0.78rem; color: var(--text-muted);">{{ $cat->events_count }} Active Events</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
