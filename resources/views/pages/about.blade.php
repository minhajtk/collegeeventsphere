@extends('layouts.app')

@section('title', 'About Us - EventSphere')

@section('content')
<div style="max-width: 900px; margin: 2rem auto;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span style="color: var(--primary); font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">Our Mission & Vision</span>
        <h1 style="font-size: 1.85rem; margin-top: 0.35rem; margin-bottom: 0.75rem;">About EventSphere System</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 680px; margin: 0 auto; line-height: 1.6;">
            EventSphere was developed to solve the challenges of traditional, manual college event management. It serves as a unified digital ecosystem connecting students, faculty organizers, and campus administration.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 4rem;">
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem;">
            <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-bullhorn" style="font-size: 1.25rem; color: var(--primary);"></i>
            </div>
            <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Effective Communication</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">Eliminates missed noticeboard updates by delivering real-time announcements, venue alerts, and email/dashboard notifications.</p>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem;">
            <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-users-viewfinder" style="font-size: 1.25rem; color: var(--success);"></i>
            </div>
            <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Dynamic Seating & Waitlists</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">Automatically enforces venue seating capacity rules and promotes waitlisted students when seats free up.</p>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem;">
            <div style="width: 50px; height: 50px; background: rgba(139, 92, 246, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-qrcode" style="font-size: 1.25rem; color: var(--secondary);"></i>
            </div>
            <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Paperless QR Attendance</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">Generates unique encrypted QR passes for registered students, scanned seamlessly by staff on event day.</p>
        </div>
    </div>
</div>
@endsection
