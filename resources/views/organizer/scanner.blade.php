@extends('layouts.portal')

@section('title', 'QR Attendance Scanner - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Organizer Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <a href="{{ route('organizer.events.registrations', $event->id) }}" style="color: var(--text-muted);">Roster</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Attendance Scanner</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-qrcode" style="color: #ffffff;"></i> QR Attendance Scanner</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Event: <strong style="color: #ffffff;">{{ $event->title }}</strong>
        </p>
    </div>

    <a href="{{ route('organizer.events.registrations', $event->id) }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Roster
    </a>
</div>

<!-- Scanner / Manual Code Entry -->
<div class="portal-card" style="max-width: 760px; margin-left: auto; margin-right: auto; text-align: center;">
    <div style="width: 70px; height: 70px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 2px dashed rgba(255,255,255,0.4);">
        <i class="fa-solid fa-expand" style="font-size: 1.75rem; color: #ffffff;"></i>
    </div>
    <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">Scan or Enter QR Pass Token</h3>
    <p style="color: var(--text-muted); font-size: 0.85rem; font-family: var(--font-sub); margin-bottom: 1.5rem;">Enter participant <code>QR-XXXX</code> token from student pass to record attendance</p>

    <form action="{{ route('organizer.events.verify', $event->id) }}" method="POST" style="max-width: 480px; margin: 0 auto;">
        @csrf
        <div class="form-group">
            <div style="display: flex; gap: 0.65rem;">
                <input type="text" name="qr_token" class="form-control" placeholder="QR-XXXXXXXXXXXX-1" required autofocus style="font-family: monospace; font-size: 1rem; text-transform: uppercase;">
                <button type="submit" class="btn btn-primary btn-sm" style="white-space: nowrap; padding: 0.65rem 1.25rem;">
                    <i class="fa-solid fa-check"></i> Verify
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Checked-In Participants Feed -->
<div class="portal-card" style="max-width: 760px; margin-left: auto; margin-right: auto;">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-user-check" style="color: var(--success);"></i> Verified Attendees ({{ $todayAttendances->count() }})
        </h3>
    </div>

    @if($todayAttendances->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
            @foreach($todayAttendances as $attendance)
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); padding: 0.85rem 1.15rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="font-weight: 700; color: var(--text-main); font-size: 0.92rem; font-family: var(--font-sub);">{{ $attendance->user->name }}</span>
                        <span style="font-size: 0.78rem; color: var(--text-muted); margin-left: 0.4rem;">({{ $attendance->user->enrolment_number }} • {{ $attendance->user->department }})</span>
                    </div>
                    <span style="font-size: 0.82rem; color: var(--success); font-weight: 600; font-family: var(--font-sub);">
                        <i class="fa-regular fa-clock"></i> {{ $attendance->checked_in_at->format('h:i:s A') }}
                    </span>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 1.5rem; font-family: var(--font-sub); font-size: 0.88rem;">No participants checked in yet today.</p>
    @endif
</div>
@endsection
