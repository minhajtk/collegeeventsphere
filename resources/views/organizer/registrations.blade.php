@extends('layouts.portal')

@section('title', 'Manage Registrations - ' . $event->title)

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Organizer Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <a href="{{ route('organizer.dashboard') }}" style="color: var(--text-muted);">Events</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Participant Roster</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;">Participant Roster</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Event: <strong style="color: #ffffff;">{{ $event->title }}</strong>
        </p>
    </div>

    <div style="display: flex; gap: 0.65rem; flex-wrap: wrap;">
        <a href="{{ route('organizer.events.scanner', $event->id) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-qrcode"></i> Launch QR Scanner
        </a>
        <button data-modal-target="announcementModal" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-bullhorn"></i> Send Announcement
        </button>
        <form action="{{ route('organizer.events.certificates.issue', $event->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Issue e-certificates to all participants with marked attendance?');">
                <i class="fa-solid fa-certificate" style="color: var(--secondary);"></i> Issue E-Certificates ({{ $registrations->where('status', 'attended')->count() }} Eligible)
            </button>
        </form>
    </div>
</div>

<!-- Stats Bar -->
<div class="portal-metric-grid">
    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Registered Students</span>
            <div class="portal-metric-icon" style="background: rgba(255, 255, 255, 0.1); color: #ffffff;">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
        <div class="portal-metric-value">{{ $registrations->where('status', 'registered')->count() }}</div>
        <div class="portal-metric-sub">Confirmed pass holders</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Attended Check-ins</span>
            <div class="portal-metric-icon" style="background: rgba(34, 197, 94, 0.15); color: var(--success);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--success);">{{ $registrations->where('status', 'attended')->count() }}</div>
        <div class="portal-metric-sub">Marked / Verified Attendance</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">On Waitlist</span>
            <div class="portal-metric-icon" style="background: rgba(234, 179, 8, 0.15); color: var(--warning);">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--warning);">{{ $registrations->where('status', 'waitlisted')->count() }}</div>
        <div class="portal-metric-sub">Awaiting cancellation promotions</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Available Slots</span>
            <div class="portal-metric-icon" style="background: rgba(59, 130, 246, 0.15); color: var(--accent);">
                <i class="fa-solid fa-chair"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--accent);">{{ $event->available_slots }} / {{ $event->capacity }}</div>
        <div class="portal-metric-sub">Remaining seat capacity</div>
    </div>
</div>

<!-- Participants Table -->
<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;">Participant Roster ({{ $registrations->count() }})</h3>
    </div>

    @if($registrations->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Enrolment No</th>
                        <th>Department</th>
                        <th>Registration Status</th>
                        <th>QR Pass Token</th>
                        <th>Attendance</th>
                        <th>Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                        <tr>
                            <td>
                                <strong>{{ $reg->user->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-sub);">{{ $reg->user->email }}</div>
                            </td>
                            <td><code style="font-size: 0.8rem; background: rgba(255,255,255,0.06); padding: 0.15rem 0.4rem; border-radius: 4px;">{{ $reg->user->enrolment_number }}</code></td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $reg->user->department }}</td>
                            <td>
                                @if($reg->status === 'registered')
                                    <span class="status-pill status-active">Registered</span>
                                @elseif($reg->status === 'attended')
                                    <span class="status-pill status-completed">Attended</span>
                                @elseif($reg->status === 'waitlisted')
                                    <span class="status-pill status-waitlist">Waitlisted</span>
                                @else
                                    <span class="status-pill status-rejected">Cancelled</span>
                                @endif
                            </td>
                            <td><code style="font-size: 0.78rem; color: #a1a1aa;">{{ $reg->qr_code_token }}</code></td>
                            <td>
                                @if(isset($attendances[$reg->user_id]) || $reg->status === 'attended')
                                    <span style="color: var(--success); font-weight: 600; font-size: 0.82rem;">
                                        <i class="fa-solid fa-circle-check"></i> {{ isset($attendances[$reg->user_id]) ? $attendances[$reg->user_id]->checked_in_at->format('h:i A') : 'Attended' }}
                                    </span>
                                @else
                                    <span style="color: var(--text-dim); font-size: 0.82rem;">Not Marked</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($certificates[$reg->user_id]))
                                    <span style="color: var(--secondary); font-weight: 600; font-size: 0.82rem;">
                                        <i class="fa-solid fa-award"></i> Issued
                                    </span>
                                @elseif($reg->status === 'attended' || isset($attendances[$reg->user_id]))
                                    <span style="color: var(--accent); font-size: 0.82rem; font-weight: 600;">
                                        <i class="fa-solid fa-circle-check"></i> Ready to Issue
                                    </span>
                                @else
                                    <span style="color: var(--text-dim); font-size: 0.8rem;" title="Student must mark attendance before certificate is issued">
                                        <i class="fa-solid fa-lock"></i> Requires Attendance
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 2rem; font-family: var(--font-sub); font-size: 0.88rem;">No registrants for this event yet.</p>
    @endif
</div>

<!-- Send Announcement Modal -->
<div id="announcementModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.65rem;">
            <h3 style="font-size: 1.15rem;"><i class="fa-solid fa-bullhorn" style="color: var(--warning);"></i> Send Participant Announcement</h3>
            <button data-modal-close style="background: none; border: none; color: var(--text-muted); font-size: 1.1rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('organizer.events.announcement', $event->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Announcement Title *</label>
                <input type="text" name="title" class="form-control" placeholder="Reporting Venue & Timing Update" required>
            </div>

            <div class="form-group">
                <label class="form-label">Message Content *</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Type announcement message to be broadcast to all registered students..." required></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem;">
                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Send Announcement</button>
            </div>
        </form>
    </div>
</div>
@endsection
