@extends('layouts.app')

@section('title', 'Contact Us - EventSphere')

@section('content')
<div style="max-width: 900px; margin: 2rem auto;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
        <div>
            <span style="color: var(--primary); font-weight: 700; font-size: 0.82rem; text-transform: uppercase;">Get In Touch</span>
            <h1 style="font-size: 1.85rem; margin-top: 0.35rem; margin-bottom: 0.75rem;">Contact EventSphere Support</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.75rem;">
                Have questions regarding event registrations, certificate issuance, or faculty organizer access? Fill out the inquiry form or reach out directly to the campus technical committee.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-surface); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <i class="fa-solid fa-location-dot" style="font-size: 1.25rem; color: var(--primary);"></i>
                    <div>
                        <strong style="color: var(--text-main); display: block;">Campus Address</strong>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Main Administrative Building, Room 204</span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-surface); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <i class="fa-solid fa-envelope" style="font-size: 1.25rem; color: var(--accent);"></i>
                    <div>
                        <strong style="color: var(--text-main); display: block;">Email Support</strong>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">events@eventsphere.edu</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-soft);">
            <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem;">Send a Message</h3>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="Alex Rivera" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="alex@eventsphere.edu" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Subject *</label>
                    <input type="text" name="subject" class="form-control" placeholder="Inquiry about Technical Fest 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Message *</label>
                    <textarea name="message" class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
