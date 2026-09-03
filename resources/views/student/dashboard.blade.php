@extends('layouts.app')

@section('title', 'Student Dashboard - EventSphere')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; margin-bottom: 0.35rem;">Student Dashboard</h1>
            <p style="color: var(--text-muted); font-size: 0.92rem;">Manage your event registrations, check-in QR passes, certificates, and saved media</p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button data-modal-target="profileModal" class="btn btn-secondary btn-sm"><i class="fa-solid fa-user-gear"></i> Edit Profile</button>
            <form action="{{ route('student.notifications.read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">
                    <i class="fa-regular fa-bell"></i> Mark Notifications Read ({{ $unreadNotificationsCount }})
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Quick Overview Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Registered Events</span>
            <i class="fa-solid fa-calendar-check" style="font-size: 1.25rem; color: var(--primary);"></i>
        </div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--text-main);">{{ $registrations->where('status', 'registered')->count() }}</div>
    </div>

    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">On Waitlist</span>
            <i class="fa-solid fa-clock-rotate-left" style="font-size: 1.25rem; color: var(--warning);"></i>
        </div>
        <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;">{{ $registrations->where('status', 'waitlisted')->count() }}</div>
    </div>

    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Attended Events</span>
            <i class="fa-solid fa-award" style="font-size: 1.25rem; color: var(--success);"></i>
        </div>
        <div style="font-size: 2rem; font-weight: 800; color: #34d399;">{{ $registrations->where('status', 'attended')->count() }}</div>
    </div>

    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Certificates Earned</span>
            <i class="fa-solid fa-certificate" style="font-size: 1.25rem; color: var(--secondary);"></i>
        </div>
        <div style="font-size: 2rem; font-weight: 800; color: #a78bfa;">{{ $certificates->count() }}</div>
    </div>
</div>

<!-- Registrations & Passes Section -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 3rem; box-shadow: var(--shadow-soft);">
    <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">My Event Registrations & QR Passes</h3>

    @if($registrations->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Attendance</th>
                        <th>QR Pass</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                        @php
                            $startDate = $reg->event->start_date->copy()->startOfDay();
                            $endDate = ($reg->event->end_date ? $reg->event->end_date->copy() : $reg->event->start_date->copy())->endOfDay();
                            $today = now()->startOfDay();
                            $isEventDay = now()->gte($startDate) && now()->lte($endDate);
                            $isUpcoming = $today->lt($startDate);
                            $isPassed = now()->gt($endDate);
                            $hasAttended = ($reg->status === 'attended') || isset($attendances[$reg->event_id]);
                        @endphp
                        <tr>
                            <td>
                                <strong><a href="{{ route('events.show', $reg->event->slug) }}">{{ $reg->event->title }}</a></strong>
                            </td>
                            <td><span class="category-badge" style="position:static;">{{ $reg->event->category->name }}</span></td>
                            <td>{{ $reg->event->start_date->format('M d, Y • h:i A') }}</td>
                            <td>{{ $reg->event->venue }}</td>
                            <td>
                                @if($reg->status === 'registered')
                                    <span style="color: #34d399; font-weight: 700;"><i class="fa-solid fa-circle-check"></i> Registered</span>
                                @elseif($reg->status === 'waitlisted')
                                    <span style="color: #fbbf24; font-weight: 700;"><i class="fa-solid fa-clock"></i> Waitlisted</span>
                                @elseif($reg->status === 'attended')
                                    <span style="color: #60a5fa; font-weight: 700;"><i class="fa-solid fa-user-check"></i> Attended</span>
                                @else
                                    <span style="color: #f87171;"><i class="fa-solid fa-ban"></i> Cancelled</span>
                                @endif
                            </td>
                            <td>
                                @if($hasAttended)
                                    <span style="color: #34d399; font-weight: 700; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(52, 211, 153, 0.12); padding: 0.3rem 0.65rem; border-radius: 9999px; border: 1px solid rgba(52, 211, 153, 0.25);">
                                        <i class="fa-solid fa-circle-check"></i> Attended
                                        @if(isset($attendances[$reg->event_id]))
                                            <span style="font-size: 0.72rem; color: #a7f3d0; font-weight: normal;">({{ $attendances[$reg->event_id]->checked_in_at->format('h:i A') }})</span>
                                        @endif
                                    </span>
                                @elseif($reg->status === 'registered')
                                    @if($isEventDay)
                                        <form action="{{ route('student.events.attendance', $reg->event->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; background: linear-gradient(135deg, #10b981, #059669); border: none; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);" onclick="return confirm('Mark your attendance for {{ addslashes($reg->event->title) }} today?');">
                                                <i class="fa-solid fa-user-check"></i> Mark Attendance
                                            </button>
                                        </form>
                                    @elseif($isUpcoming)
                                        <span style="color: #94a3b8; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(255,255,255,0.04); padding: 0.25rem 0.55rem; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08);" title="Opens on event day: {{ $reg->event->start_date->format('M d, Y') }}">
                                            <i class="fa-regular fa-calendar"></i> Opens {{ $reg->event->start_date->format('M d') }}
                                        </span>
                                    @else
                                        <span style="color: #f87171; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 0.3rem; background: rgba(239, 68, 68, 0.08); padding: 0.25rem 0.55rem; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.2);">
                                            <i class="fa-solid fa-clock-rotate-left"></i> Event Ended
                                        </span>
                                    @endif
                                @elseif($reg->status === 'waitlisted')
                                    <span style="color: #fbbf24; font-size: 0.78rem;">On Waitlist</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.78rem;">-</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($reg->status, ['registered', 'attended']))
                                    <button data-modal-target="qrModal-{{ $reg->id }}" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-qrcode" style="color: var(--primary);"></i> View Pass
                                    </button>

                                    <!-- QR Pass Modal -->
                                    <div id="qrModal-{{ $reg->id }}" class="modal-backdrop">
                                        <div class="modal-card" style="text-align: center;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                                                <h3 style="font-size: 1.2rem;">Event Check-in QR Pass</h3>
                                                <button data-modal-close style="background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
                                            </div>

                                            <div style="background: #ffffff; padding: 1.5rem; border-radius: var(--radius-md); display: inline-block; margin-bottom: 1rem;">
                                                <!-- Visual QR Code Box -->
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($reg->qr_code_token) }}" alt="QR Code" style="width: 180px; height: 180px;">
                                            </div>

                                            <p style="font-weight: 700; color: var(--primary); font-size: 1.1rem; margin-bottom: 0.3rem;">{{ $reg->qr_code_token }}</p>
                                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Present this QR code to event staff on entry or mark your attendance on the event day.</p>
                                            
                                            @if($hasAttended)
                                                <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); padding: 0.75rem; margin-bottom: 1rem; color: #34d399; font-weight: 600; font-size: 0.85rem;">
                                                    <i class="fa-solid fa-circle-check"></i> Attendance Confirmed for this Event
                                                </div>
                                            @elseif($isEventDay && $reg->status === 'registered')
                                                <div style="margin-bottom: 1rem; padding: 0.75rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: var(--radius-md);">
                                                    <p style="font-size: 0.82rem; color: #34d399; margin-bottom: 0.5rem; font-weight: 600;">Today is the event day!</p>
                                                    <form action="{{ route('student.events.attendance', $reg->event->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" style="width: 100%; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                                                            <i class="fa-solid fa-user-check"></i> Mark My Attendance Today
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div style="margin-bottom: 1rem; font-size: 0.78rem; color: var(--text-muted);">
                                                    <i class="fa-solid fa-info-circle"></i> Attendance can be self-marked on event day: <strong>{{ $reg->event->start_date->format('M d, Y') }}</strong>
                                                </div>
                                            @endif

                                            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; text-align: left; font-size: 0.85rem;">
                                                <p><strong>Student:</strong> {{ Auth::user()->name }} ({{ Auth::user()->enrolment_number }})</p>
                                                <p><strong>Event:</strong> {{ $reg->event->title }}</p>
                                                <p><strong>Venue:</strong> {{ $reg->event->venue }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($reg->status, ['registered', 'waitlisted']))
                                    <form action="{{ route('events.register.cancel', $reg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel registration?');">Cancel</button>
                                    </form>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 2rem;">You haven't registered for any events yet. <a href="{{ route('events.index') }}">Browse Events Now</a></p>
    @endif
</div>

<!-- Certificates Section -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 3rem; box-shadow: var(--shadow-soft);">
    <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">My E-Certificates</h3>

    @if($certificates->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($certificates as $cert)
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <i class="fa-solid fa-award" style="font-size: 2rem; color: var(--secondary);"></i>
                        <div>
                            <h4 style="font-size: 1.05rem;">{{ $cert->event->title }}</h4>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Ref ID: {{ $cert->certificate_number }}</span>
                        </div>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Issued on {{ $cert->issued_at->format('M d, Y') }}</p>

                    <a href="{{ route('student.certificate.download', $cert->id) }}" target="_blank" class="btn btn-primary btn-sm" style="width: 100%;">
                        <i class="fa-solid fa-download"></i> View & Print E-Certificate
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No certificates issued yet. Attend registered events and complete attendance to receive certificates!</p>
    @endif
</div>

<!-- Profile Edit Modal -->
<div id="profileModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            <h3 style="font-size: 1.25rem;"><i class="fa-solid fa-user-gear" style="color: var(--primary);"></i> Update Student Profile</h3>
            <button data-modal-close style="background: none; border: none; color: var(--text-muted); font-size: 1.25rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ Auth::user()->department }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Enrolment Number</label>
                    <input type="text" name="enrolment_number" class="form-control" value="{{ Auth::user()->enrolment_number }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">New Password (Optional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" data-modal-close class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Profile Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
