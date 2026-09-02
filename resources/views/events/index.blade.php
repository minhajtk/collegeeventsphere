@extends('layouts.app')

@section('title', 'Browse Events - EventSphere')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.85rem; margin-bottom: 0.35rem;">College Events Directory</h1>
    <p style="color: var(--text-muted); font-size: 0.92rem;">Filter and discover academic competitions, cultural shows, tech symposiums, and sports tournaments</p>
</div>

<!-- Filters Bar -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2.5rem; box-shadow: var(--shadow-soft);">
    <form action="{{ route('events.index') }}" method="GET">
        <div class="filter-form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <label class="form-label" style="font-size: 0.82rem;">Search Keywords</label>
                <input type="text" name="search" class="form-control" placeholder="Search title, venue, or dept..." value="{{ request('search') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.82rem;">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.82rem;">Organizing Department</label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.82rem;">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.82rem;">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
        </div>

        <div class="filter-bar-actions">
            <!-- Status Tabs -->
            <div class="event-filter-tabs">
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'all'])) }}" class="btn btn-sm {{ $tab === 'all' ? 'btn-primary' : 'btn-secondary' }}">All Events</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'upcoming'])) }}" class="btn btn-sm {{ $tab === 'upcoming' ? 'btn-primary' : 'btn-secondary' }}">Upcoming</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'ongoing'])) }}" class="btn btn-sm {{ $tab === 'ongoing' ? 'btn-primary' : 'btn-secondary' }}">Ongoing</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'past'])) }}" class="btn btn-sm {{ $tab === 'past' ? 'btn-primary' : 'btn-secondary' }}">Past Events</a>
            </div>

            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Reset Filters</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<!-- Event Grid -->
@if($events->count() > 0)
    <div class="event-grid">
        @foreach($events as $event)
            <div class="event-card">
                <div class="event-card-img" style="background-image: url('{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80' }}'); background-size: cover; background-position: center;">
                    <span class="category-badge"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                    @if($event->isFull())
                        <span class="slot-badge full">Waitlist Active</span>
                    @else
                        <span class="slot-badge">{{ $event->available_slots }} Slots Left</span>
                    @endif
                </div>

                <div class="event-card-body">
                    <h3 class="event-card-title"><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h3>
                    <p style="color: var(--text-muted); font-size: 0.88rem; line-height: 1.5; margin-bottom: 1rem;">
                        {{ Str::limit(strip_tags($event->description), 90) }}
                    </p>

                    <div class="event-meta">
                        <div class="meta-item"><i class="fa-regular fa-calendar-check" style="color: var(--primary);"></i> {{ $event->start_date->format('M d, Y • h:i A') }}</div>
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

                    <div style="margin-top: 1.25rem; display: flex; gap: 0.5rem;">
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                            View Event & Register
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 3rem;">
        {{ $events->links() }}
    </div>
@else
    <div style="text-align: center; padding: 5rem 2rem; background: var(--bg-surface); border: 1px dashed var(--border-color); border-radius: var(--radius-lg);">
        <i class="fa-solid fa-magnifying-glass" style="font-size: 3rem; color: var(--text-dim); margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-muted);">No events found matching your filter criteria</h3>
        <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 0.5rem;">Try resetting your filters or adjusting your date range.</p>
        <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm" style="margin-top: 1rem;">Reset Filters</a>
    </div>
@endif
@endsection
