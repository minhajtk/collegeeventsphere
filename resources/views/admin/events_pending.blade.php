@extends('layouts.portal')

@section('title', 'Pending Proposals - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Admin Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Pending Proposals</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-clock" style="color: var(--warning);"></i> Pending Event Proposals</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Review event proposals submitted by college organizers before publishing live
        </p>
    </div>
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;">Proposals Awaiting Approval ({{ $pendingEvents->count() }})</h3>
    </div>

    @if($pendingEvents->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @foreach($pendingEvents as $event)
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.35rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.75rem;">
                        <div>
                            <span class="category-badge" style="margin-bottom: 0.4rem;"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                            <h3 style="font-size: 1.25rem; margin-top: 0.35rem;">{{ $event->title }}</h3>
                            <p style="font-size: 0.82rem; color: var(--text-muted); font-family: var(--font-sub);">Submitted by <strong>{{ $event->organizer->name }}</strong> • {{ $event->organizing_department }}</p>
                        </div>

                        <div style="display: flex; gap: 0.45rem;">
                            <form action="{{ route('admin.events.approve', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i> Approve & Publish</button>
                            </form>

                            <button data-modal-target="rejectModal-{{ $event->id }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-xmark"></i> Reject</button>
                        </div>
                    </div>

                    <p style="color: var(--text-main); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">
                        {{ $event->description }}
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0.75rem; background: var(--bg-surface); padding: 0.85rem 1rem; border-radius: var(--radius-sm); font-size: 0.82rem; font-family: var(--font-sub);">
                        <div><strong style="color: var(--text-muted);">Venue:</strong> {{ $event->venue }}</div>
                        <div><strong style="color: var(--text-muted);">Capacity:</strong> {{ $event->capacity }} Seats</div>
                        <div><strong style="color: var(--text-muted);">Start Date:</strong> {{ $event->start_date->format('M d, Y • h:i A') }}</div>
                        <div><strong style="color: var(--text-muted);">Registration Deadline:</strong> {{ $event->registration_deadline->format('M d, Y • h:i A') }}</div>
                    </div>

                    <!-- Reject Reason Modal -->
                    <div id="rejectModal-{{ $event->id }}" class="modal-backdrop">
                        <div class="modal-card">
                            <h3 style="font-size: 1.15rem; margin-bottom: 0.85rem;">Reject Event Proposal</h3>
                            <form action="{{ route('admin.events.reject', $event->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Rejection Reason / Feedback *</label>
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
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1.5rem;">
            <i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: var(--success); margin-bottom: 0.75rem; display: block;"></i>
            <h3 style="color: var(--text-muted); font-family: var(--font-sub); font-size: 1.1rem;">All caught up! No pending proposals awaiting review.</h3>
        </div>
    @endif
</div>
@endsection
