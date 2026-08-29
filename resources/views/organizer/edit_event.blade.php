@extends('layouts.portal')

@section('title', 'Edit Event - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Organizer Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <a href="{{ route('organizer.dashboard') }}" style="color: var(--text-muted);">Events</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Edit Event</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-pen-to-square" style="color: var(--secondary);"></i> Edit Event Details</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Updating schedules or venues automatically keeps all registered participants informed
        </p>
    </div>
</div>

<div class="portal-card" style="max-width: 880px;">
    <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Event Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Organizing Department *</label>
                <input type="text" name="organizing_department" class="form-control" value="{{ old('organizing_department', $event->organizing_department) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Event Description & Rules *</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $event->description) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Venue / Hall Location *</label>
                <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Maximum Seat Limit / Capacity *</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $event->capacity) }}" min="1" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Start Date & Time *</label>
                <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">End Date & Time *</label>
                <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Registration Cutoff *</label>
                <input type="datetime-local" name="registration_deadline" class="form-control" value="{{ old('registration_deadline', $event->registration_deadline->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Hashtags / Promotional Tags</label>
            <input type="text" name="hashtags" class="form-control" value="{{ old('hashtags', $event->hashtags) }}">
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
            <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Update Event</button>
        </div>
    </form>
</div>
@endsection
