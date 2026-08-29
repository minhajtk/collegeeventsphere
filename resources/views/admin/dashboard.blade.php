@extends('layouts.portal')

@section('title', 'Admin Dashboard - EventSphere')

@section('content')
<!-- Top Bar with Breadcrumbs & Actions -->
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Admin Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Control Center</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;">System Control Center</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Real-time campus metrics, event approvals, user role management & report exports
        </p>
    </div>

    <div style="display: flex; gap: 0.65rem; flex-wrap: wrap;">
        <button data-modal-target="systemAnnouncementModal" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-bullhorn"></i> Broadcast Announcement
        </button>
        <a href="{{ route('admin.events.pending') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-clock" style="color: var(--warning);"></i> Pending Review ({{ $pendingEventsCount }})
        </a>
    </div>
</div>

<!-- System Metrics KPI Cards -->
<div class="portal-metric-grid">
    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Total Users</span>
            <div class="portal-metric-icon" style="background: rgba(255, 255, 255, 0.1); color: #ffffff;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="portal-metric-value">{{ $totalUsers }}</div>
        <div class="portal-metric-sub">{{ $studentsCount }} Students • {{ $organizersCount }} Organizers</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Total Events</span>
            <div class="portal-metric-icon" style="background: rgba(168, 85, 247, 0.15); color: var(--secondary);">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--secondary);">{{ $totalEvents }}</div>
        <div class="portal-metric-sub">{{ $approvedEventsCount }} Approved • {{ $pendingEventsCount }} Pending</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Registrations</span>
            <div class="portal-metric-icon" style="background: rgba(59, 130, 246, 0.15); color: var(--accent);">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--accent);">{{ $totalRegistrations }}</div>
        <div class="portal-metric-sub">Active campus participant tickets</div>
    </div>

    <div class="portal-metric-card">
        <div class="portal-metric-header">
            <span class="portal-metric-label">Certificates Issued</span>
            <div class="portal-metric-icon" style="background: rgba(34, 197, 94, 0.15); color: var(--success);">
                <i class="fa-solid fa-certificate"></i>
            </div>
        </div>
        <div class="portal-metric-value" style="color: var(--success);">{{ $totalCertificates }}</div>
        <div class="portal-metric-sub">Verified attendance certificates</div>
    </div>
</div>

<!-- Pending Proposals & CSV Export Cards -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Pending Event Proposals -->
    <div class="portal-card">
        <div class="portal-card-header">
            <h3 style="font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-clock" style="color: var(--warning);"></i> Pending Event Proposals
            </h3>
            <a href="{{ route('admin.events.pending') }}" class="btn btn-outline btn-sm" style="font-size: 0.78rem;">View All Pending</a>
        </div>

        @if($recentPendingEvents->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @foreach($recentPendingEvents as $pEvent)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); padding: 1rem 1.15rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); flex-wrap: wrap; gap: 0.75rem;">
                        <div>
                            <strong style="color: var(--text-main); font-size: 0.98rem; display: block; font-family: var(--font-sub);">{{ $pEvent->title }}</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Organized by {{ $pEvent->organizer->name }} • {{ $pEvent->organizing_department }}</span>
                        </div>

                        <div style="display: flex; gap: 0.45rem;">
                            <form action="{{ route('admin.events.approve', $pEvent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" style="padding: 0.35rem 0.75rem;"><i class="fa-solid fa-check"></i> Approve</button>
                            </form>

                            <button data-modal-target="rejectModal-{{ $pEvent->id }}" class="btn btn-sm btn-danger" style="padding: 0.35rem 0.75rem;"><i class="fa-solid fa-xmark"></i> Reject</button>

                            <!-- Reject Reason Modal -->
                            <div id="rejectModal-{{ $pEvent->id }}" class="modal-backdrop">
                                <div class="modal-card">
                                    <h3 style="font-size: 1.15rem; margin-bottom: 0.85rem;">Reject Event Proposal</h3>
                                    <form action="{{ route('admin.events.reject', $pEvent->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">Rejection Reason / Feedback</label>
                                            <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Specify why proposal was rejected..."></textarea>
                                        </div>
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Confirm Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted); font-family: var(--font-sub); font-size: 0.9rem;">
                <i class="fa-regular fa-circle-check" style="font-size: 2rem; color: var(--success); margin-bottom: 0.6rem; display: block;"></i>
                No pending event proposals awaiting review.
            </div>
        @endif
    </div>

    <!-- Export System Reports -->
    <div class="portal-card">
        <div class="portal-card-header">
            <h3 style="font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-file-export" style="color: var(--accent);"></i> System CSV Reports
            </h3>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="{{ route('admin.reports.export', 'participation') }}" class="btn btn-secondary" style="justify-content: flex-start; font-size: 0.85rem;">
                <i class="fa-solid fa-file-csv" style="color: var(--success); font-size: 1.1rem;"></i> Export Registrations CSV
            </a>

            <a href="{{ route('admin.reports.export', 'feedback') }}" class="btn btn-secondary" style="justify-content: flex-start; font-size: 0.85rem;">
                <i class="fa-solid fa-file-csv" style="color: var(--warning); font-size: 1.1rem;"></i> Export Feedback CSV
            </a>

            <a href="{{ route('admin.reports.export', 'certificates') }}" class="btn btn-secondary" style="justify-content: flex-start; font-size: 0.85rem;">
                <i class="fa-solid fa-file-csv" style="color: var(--secondary); font-size: 1.1rem;"></i> Export Certificates CSV
            </a>
        </div>
    </div>
</div>

<!-- Broadcast Announcement Modal -->
<div id="systemAnnouncementModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.65rem;">
            <h3 style="font-size: 1.15rem;"><i class="fa-solid fa-bullhorn" style="color: #ffffff;"></i> Broadcast Campus Announcement</h3>
            <button data-modal-close style="background: none; border: none; color: var(--text-muted); font-size: 1.1rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.announcements.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Announcement Title *</label>
                <input type="text" name="title" class="form-control" placeholder="Annual Campus Fest Schedule Announcement" required>
            </div>

            <div class="form-group">
                <label class="form-label">Target Audience Role *</label>
                <select name="target_role" class="form-select" required>
                    <option value="all">All Registered Users (Students & Organizers)</option>
                    <option value="student">Students Only</option>
                    <option value="organizer">Organizers Only</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Message Content *</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Broadcast message content..." required></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem;">
                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Send Broadcast</button>
            </div>
        </form>
    </div>
</div>
@endsection
