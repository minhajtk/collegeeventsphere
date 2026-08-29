@extends('layouts.portal')

@section('title', 'Create Proposal - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Organizer Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <a href="{{ route('organizer.dashboard') }}" style="color: var(--text-muted);">Events</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Create Proposal</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-calendar-plus" style="color: var(--success);"></i> Create Event Proposal</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Submit your event proposal for admin review before publishing on the campus portal
        </p>
    </div>
</div>

<div class="portal-card" style="max-width: 880px;">
    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Event Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. CodeStorm 2026 Hackathon" required>
            @error('title') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Organizing Department *</label>
                <input type="text" name="organizing_department" class="form-control" value="{{ old('organizing_department', Auth::user()->department) }}" placeholder="Computer Science & Engineering" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Event Description & Rules *</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Provide details regarding schedule, eligibility, prize breakdown, guidelines, etc." required>{{ old('description') }}</textarea>
            @error('description') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Venue / Hall Location *</label>
                <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="Auditorium Hall B / Tech Lab 3" required>
            </div>

            <div class="form-group">
                <label class="form-label">Maximum Seat Limit / Capacity *</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 100) }}" min="1" required>
                <span style="font-size: 0.75rem; color: var(--text-muted);">Enforces seat limits and triggers automated waitlists when filled.</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Start Date & Time *</label>
                <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">End Date & Time *</label>
                <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Registration Cutoff *</label>
                <input type="datetime-local" name="registration_deadline" class="form-control" value="{{ old('registration_deadline') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Hashtags / Promotional Tags</label>
            <input type="text" name="hashtags" class="form-control" value="{{ old('hashtags') }}" placeholder="#Hackathon2026 #EventSphere #CodingFest">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
            <div class="form-group">
                <label class="form-label">Banner Image (JPG, PNG, WEBP)</label>
                <input type="file" name="banner_image" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Official Rulebook PDF</label>
                <input type="file" name="rulebook_file" class="form-control">
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
            <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Submit Proposal</button>
        </div>
    </form>
</div>
@endsection
