@extends('layouts.app')

@section('title', 'Join EventSphere - Create Account')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Auth Header -->
        <div class="auth-header">
            <div class="auth-icon-avatar" style="background: rgba(168, 85, 247, 0.15); color: var(--secondary);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 style="font-size: 1.65rem; margin-bottom: 0.35rem;">Create Your Account</h1>
            <p style="color: var(--text-muted); font-size: 0.88rem;">Join EventSphere to participate in or coordinate college events</p>
        </div>

        <!-- Role Selector Segmented Controls -->
        <div class="auth-role-tabs" id="roleSelector">
            <div class="auth-role-tab {{ old('role', 'student') === 'student' ? 'active' : '' }}" onclick="selectRole('student')">
                <i class="fa-solid fa-user-graduate"></i> Student Participant
            </div>
            <div class="auth-role-tab {{ old('role') === 'organizer' ? 'active' : '' }}" onclick="selectRole('organizer')">
                <i class="fa-solid fa-briefcase"></i> Event Organizer
            </div>
        </div>

        <!-- Dynamic Organizer Information Callout -->
        <div class="auth-callout" id="organizerInfoBox" style="{{ old('role') === 'organizer' ? '' : 'display: none;' }}">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <strong style="color: #ffffff; display: block; margin-bottom: 0.2rem;">Join as an Event Organizer</strong>
                Faculty coordinators, department heads, and student club presidents can submit event proposals, manage live registrations, verify attendee QR tokens, and issue digital certificates.
            </div>
        </div>

        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'student') }}">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                <div class="form-group">
                    <label class="form-label" for="name">Full Name *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-user prefix-icon"></i>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Alex Rivera" required autofocus>
                    </div>
                    @error('name') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-at prefix-icon"></i>
                        <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="alexrivera" required>
                    </div>
                    @error('username') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                <div class="form-group">
                    <label class="form-label" for="email">Institutional Email *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-envelope prefix-icon"></i>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="alex@eventsphere.edu" required>
                    </div>
                    @error('email') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-phone prefix-icon"></i>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 555-0192">
                    </div>
                    @error('phone') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                <div class="form-group">
                    <label class="form-label" for="department">Department / Branch *</label>
                    <select name="department" id="department" class="form-select" required>
                        <option value="">Select Department</option>
                        <option value="Computer Science & Engineering" {{ old('department') == 'Computer Science & Engineering' ? 'selected' : '' }}>Computer Science & Engineering</option>
                        <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                        <option value="Electronics & Communication" {{ old('department') == 'Electronics & Communication' ? 'selected' : '' }}>Electronics & Communication</option>
                        <option value="Mechanical Engineering" {{ old('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                        <option value="Business Administration" {{ old('department') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                        <option value="Arts & Humanities" {{ old('department') == 'Arts & Humanities' ? 'selected' : '' }}>Arts & Humanities</option>
                        <option value="Student Affairs & Clubs" {{ old('department') == 'Student Affairs & Clubs' ? 'selected' : '' }}>Student Affairs & Clubs</option>
                    </select>
                    @error('department') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="enrolment_number" id="idFieldLabel">Enrolment / Roll No *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-id-card prefix-icon"></i>
                        <input type="text" name="enrolment_number" id="enrolment_number" class="form-control" value="{{ old('enrolment_number') }}" placeholder="EN20261092" required>
                    </div>
                    @error('enrolment_number') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem;">
                <div class="form-group">
                    <label class="form-label" for="password">Password *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-lock prefix-icon"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Min. 8 chars" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password', this)" title="Show/Hide Password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    @error('password') <span style="color:#f87171; font-size:0.78rem; margin-top:0.25rem; display:block;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password *</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-shield-check prefix-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation', this)" title="Show/Hide Password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn" style="width: 100%; margin-top: 0.5rem;">
                <i class="fa-solid fa-arrow-right"></i> <span id="submitBtnText">Register as Student</span>
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="font-weight: 700; color: #ffffff;">Sign In Here</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectRole(role) {
        const tabs = document.querySelectorAll('.auth-role-tab');
        const roleInput = document.getElementById('roleInput');
        const infoBox = document.getElementById('organizerInfoBox');
        const idLabel = document.getElementById('idFieldLabel');
        const idInput = document.getElementById('enrolment_number');
        const submitText = document.getElementById('submitBtnText');

        tabs.forEach(tab => tab.classList.remove('active'));

        if (role === 'organizer') {
            tabs[1].classList.add('active');
            roleInput.value = 'organizer';
            infoBox.style.display = 'flex';
            idLabel.innerText = 'Faculty / Staff / Club ID *';
            idInput.placeholder = 'FAC-CS-0042';
            submitText.innerText = 'Register as Event Organizer';
        } else {
            tabs[0].classList.add('active');
            roleInput.value = 'student';
            infoBox.style.display = 'none';
            idLabel.innerText = 'Enrolment / Roll No *';
            idInput.placeholder = 'EN20261092';
            submitText.innerText = 'Register as Student';
        }
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Initialize state on page load (if validation failed and old input kept)
    document.addEventListener('DOMContentLoaded', function() {
        const currentRole = document.getElementById('roleInput').value;
        if (currentRole === 'organizer') {
            selectRole('organizer');
        }
    });
</script>
@endsection
