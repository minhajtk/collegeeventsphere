@extends('layouts.app')

@section('title', 'Sign In - EventSphere')

@section('content')
<div class="auth-wrapper" style="max-width: 480px;">
    <div class="auth-card">
        <!-- Auth Header -->
        <div class="auth-header">
            <div class="auth-icon-avatar" style="background: rgba(255, 255, 255, 0.1); color: #ffffff;">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 style="font-size: 1.65rem; margin-bottom: 0.35rem;">Welcome Back</h1>
            <p style="color: var(--text-muted); font-size: 0.88rem;">Sign in to your EventSphere account to continue</p>
        </div>

        <!-- Organizer Sign-up Callout -->
        <div class="auth-callout" style="background: rgba(59, 130, 246, 0.08); border-color: rgba(59, 130, 246, 0.25); color: #bfdbfe;">
            <i class="fa-solid fa-bullhorn" style="color: var(--accent);"></i>
            <div>
                <strong style="color: #ffffff; display: block; margin-bottom: 0.15rem;">Want to organize campus events?</strong>
                Faculty coordinators & club heads can <a href="{{ route('register') }}" style="color: #60a5fa; text-decoration: underline; font-weight: 700;">Sign up as an Organizer</a> to publish events & scan attendance.
            </div>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="login">Email Address or Username</label>
                <div class="input-icon-wrapper">
                    <i class="fa-regular fa-user prefix-icon"></i>
                    <input type="text" name="login" id="login" class="form-control" value="{{ old('login') }}" placeholder="student@eventsphere.edu or username" required autofocus>
                </div>
                @error('login')
                    <span style="color: #f87171; font-size: 0.78rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                    <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
                </div>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock prefix-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="password-toggle-btn" onclick="togglePassword('password', this)" title="Show/Hide Password">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted); cursor: pointer;">
                    <input type="checkbox" name="remember" style="accent-color: #ffffff;"> Remember my session
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In to Portal
            </button>
        </form>


        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-muted);">
            Don't have an account yet? <a href="{{ route('register') }}" style="font-weight: 700; color: #ffffff;">Create an Account</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
