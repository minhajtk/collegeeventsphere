@extends('layouts.app')

@section('title', 'FAQs - EventSphere')

@section('content')
<div style="max-width: 850px; margin: 2rem auto;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span style="color: var(--primary); font-weight: 700; font-size: 0.82rem; text-transform: uppercase;">Common Questions</span>
        <h1 style="font-size: 1.85rem; margin-top: 0.35rem; margin-bottom: 0.75rem;">Frequently Asked Questions</h1>
        <p style="color: var(--text-muted); font-size: 0.92rem;">Find quick answers regarding event browsing, registration, QR passes, and certificates</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; color: var(--text-main); margin-bottom: 0.5rem;">Can I view upcoming college events without creating an account?</h3>
            <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                Yes! Unregistered visitors can freely browse all upcoming, ongoing, and past events, view category filters, read event details, and check media gallery uploads without logging in. You will only be prompted to log in when attempting actions such as event registration or certificate downloads.
            </p>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; color: var(--text-main); margin-bottom: 0.5rem;">How does the Automatic Waitlist Promotion work?</h3>
            <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                When an event reaches its venue capacity, additional registrants are automatically placed on a waitlist with position numbers (#1, #2, etc.). If a registered student cancels their registration prior to the cutoff date, the highest-priority waitlisted student is automatically promoted to registered status and receives a notification with their new QR pass.
            </p>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; color: var(--text-main); margin-bottom: 0.5rem;">How do I check in on the day of the event?</h3>
            <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                Log into your student dashboard and click <strong>"View Pass"</strong> under your registered event. Present the generated QR token code to event staff, who will scan it using the Organizer Attendance tool to verify your entry.
            </p>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; color: var(--text-main); margin-bottom: 0.5rem;">When can I download my participation e-certificate?</h3>
            <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                E-certificates are issued after the event concludes for participants who have had their attendance verified by organizers. Once issued, certificates can be viewed and printed directly from your student dashboard.
            </p>
        </div>
    </div>
</div>
@endsection
