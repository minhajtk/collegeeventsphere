<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Bookmark;
use App\Models\SavedMedia;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Active & Past Registrations
        $registrations = Registration::with(['event.category', 'event.organizer'])
            ->where('user_id', $user->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        // Attendance Records
        $attendances = Attendance::where('user_id', $user->id)
            ->get()
            ->keyBy('event_id');

        // Certificates
        $certificates = Certificate::with('event')
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->get();

        // Bookmarks
        $bookmarks = Bookmark::with(['event.category'])
            ->where('user_id', $user->id)
            ->get();

        // Saved Media
        $savedMedia = SavedMedia::with(['media'])
            ->where('user_id', $user->id)
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Unread Notifications Count
        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('student.dashboard', compact(
            'registrations',
            'attendances',
            'certificates',
            'bookmarks',
            'savedMedia',
            'notifications',
            'unreadNotificationsCount'
        ));
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Notifications marked as read.');
    }

    public function downloadCertificate($certificateId)
    {
        $user = Auth::user();
        $certificate = Certificate::with('event')
            ->where('id', $certificateId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Generates clean downloadable HTML/PDF Certificate content
        $html = view('student.certificate_template', compact('certificate', 'user'))->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'inline; filename="Certificate-' . $certificate->certificate_number . '.html"',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'enrolment_number' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'department' => $validated['department'],
            'enrolment_number' => $validated['enrolment_number'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function markAttendance(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        // Verify registration
        $registration = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return back()->with('error', 'You are not registered for this event.');
        }

        if ($registration->status === 'cancelled') {
            return back()->with('error', 'Your registration for this event was cancelled.');
        }

        if ($registration->status === 'waitlisted') {
            return back()->with('error', 'You are currently on the waitlist. Attendance can only be marked for confirmed registrations.');
        }

        // Validate event-day window: Attendance can ONLY be marked on the event day
        $today = now()->startOfDay();
        $startDate = $event->start_date->copy()->startOfDay();
        $endDate = ($event->end_date ? $event->end_date->copy() : $event->start_date->copy())->endOfDay();

        if ($today->lt($startDate)) {
            return back()->with('error', "Attendance opens only on the event day ({$event->start_date->format('M d, Y')}). Please mark your attendance on the day of the event.");
        }

        if (now()->gt($endDate)) {
            return back()->with('error', 'The event has concluded and the attendance marking window is closed.');
        }

        // Check if already attended
        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($alreadyAttended || $registration->status === 'attended') {
            return back()->with('info', 'Your attendance has already been recorded for this event.');
        }

        // Record Attendance
        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'checked_in_by' => $user->id,
            'checked_in_at' => now(),
        ]);

        // Update registration status
        $registration->update(['status' => 'attended']);

        // Notification for student
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Attendance Marked Successfully!',
            'message' => "Your attendance for '{$event->title}' has been recorded. Once the organizer issues certificates, it will appear under My E-Certificates.",
            'type' => 'attendance',
        ]);

        return back()->with('success', "Attendance successfully recorded for '{$event->title}'! Your organizer can now issue your e-certificate.");
    }
}
