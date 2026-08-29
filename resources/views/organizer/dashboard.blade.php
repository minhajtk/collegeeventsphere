@extends('layouts.portal')

@section('title', 'Organizer Portal - EventSphere')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Organizer Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Dashboard</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;">Organizer Command Center</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Create proposals, track registrations, verify attendance QR passes & issue digital e-certificates
        </p>
    </div>

    <a href="{{ route('organizer.events.create') }}" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus-circle"></i> Create New Event Proposal
    </a>
</div>

<!-- Stat Cards -->
<div class="portal-metric-grid">
    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Events Created</span>
            <div class="portal-metric-icon" style="background: rgba(255, 255, 255, 0.1); color: #ffffff;">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="portal-metric-value">{{ $totalEvents }}</div>
        <div class="portal-metric-sub">Total proposals submitted</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Approved & Live</span>
            <div class="portal-metric-icon" style="background: rgba(34, 197, 94, 0.15); color: var(--success);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--success);">{{ $approvedEventsCount }}</div>
        <div class="portal-metric-sub">Active public registrations</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Pending Approval</span>
            <div class="portal-metric-icon" style="background: rgba(234, 179, 8, 0.15); color: var(--warning);">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--warning);">{{ $pendingEventsCount }}</div>
        <div class="portal-metric-sub">Awaiting admin review</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Total Registrations</span>
            <div class="portal-metric-icon" style="background: rgba(168, 85, 247, 0.15); color: var(--secondary);">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--secondary);">{{ $totalRegistrations }}</div>
        <div class="portal-metric-sub">Total student pass holders</div>
    </div>
</div>

<!-- Events Table -->
<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;">My Events & Registrations</h3>
        <a href="{{ route('organizer.events.create') }}" class="btn btn-outline btn-sm" style="font-size: 0.78rem;">
            <i class="fa-solid fa-plus"></i> New Event
        </a>
    </div>

    @if($events->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Category</th>
                        <th>Venue & Seating</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>
                                <strong><a href="{{ route('events.show', $event->slug) }}" target="_blank">{{ $event->title }}</a></strong>
                            </td>
                            <td><span class="category-badge"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span></td>
                            <td>
                                <div>{{ $event->venue }}</div>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $event->capacity - $event->available_slots }} / {{ $event->capacity }} Reserved</span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $event->start_date->format('M d, Y • h:i A') }}</td>
                            <td>
                                @if($event->status === 'approved')
                                    <span class="status-pill status-active"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Live Approved</span>
                                @elseif($event->status === 'pending')
                                    <span class="status-pill status-pending"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Pending Review</span>
                                @elseif($event->status === 'rejected')
                                    <span class="status-pill status-rejected"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Rejected</span>
                                @else
                                    <span class="status-pill status-completed">{{ ucfirst($event->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                    <a href="{{ route('organizer.events.registrations', $event->id) }}" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="View Registrants & Attendance">
                                        <i class="fa-solid fa-users"></i> Roster
                                    </a>
                                    <a href="{{ route('organizer.events.scanner', $event->id) }}" class="btn btn-sm btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="Live QR Attendance Scanner">
                                        <i class="fa-solid fa-qrcode"></i> Scan
                                    </a>
                                    <a href="{{ route('organizer.events.edit', $event->id) }}" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="Edit Event Details">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1.5rem;">
            <i class="fa-solid fa-calendar-plus" style="font-size: 2.5rem; color: var(--text-dim); margin-bottom: 0.75rem; display: block;"></i>
            <h3 style="color: var(--text-muted); font-family: var(--font-sub); font-size: 1.05rem;">No events created yet.</h3>
            <p style="color: var(--text-dim); font-size: 0.85rem; margin-top: 0.35rem; margin-bottom: 1.25rem;">Submit your first college event proposal to start collecting registrations.</p>
            <a href="{{ route('organizer.events.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Create Event Proposal</a>
        </div>
    @endif
</div>
@endsection
