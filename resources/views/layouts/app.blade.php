<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="description" content="EventSphere - Centralized College Event Information & Management Platform">
    <title>@yield('title', 'EventSphere - College Event Management')</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="{{ asset('css/eventsphere.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>

    <!-- Glassmorphism Navigation Bar -->
    <nav class="navbar" style="position: relative;">
        <div class="nav-container">

            <a href="{{ route('home') }}" class="brand-logo">
                <i class="fa-solid fa-graduation-cap" style="color: #6366f1;"></i>
                <span>EventSphere</span>
            </a>

            <!-- Navigation Links with Complete Icons -->
            <ul class="nav-links" id="mainNavLinks">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Events</a></li>
                <li><a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}"><i class="fa-solid fa-photo-film"></i> Gallery</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fa-solid fa-envelope"></i> Contact</a></li>
                <li><a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}"><i class="fa-solid fa-circle-question"></i> FAQs</a></li>
            </ul>

            <!-- Auth & User Controls -->
            <div class="nav-auth-group">
                @auth
                    <div class="user-badge" title="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})">
                        <span class="role-pill role-{{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                        <span class="user-badge-name">{{ Auth::user()->name }}</span>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary" style="padding: 0.38rem 0.75rem; font-size: 0.8rem;">
                            <i class="fa-solid fa-shield-halved"></i> Admin Portal
                        </a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="btn btn-sm btn-primary" style="padding: 0.38rem 0.75rem; font-size: 0.8rem;">
                            <i class="fa-solid fa-briefcase"></i> Organizer Portal
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-primary" style="padding: 0.38rem 0.75rem; font-size: 0.8rem;">
                            <i class="fa-solid fa-user-graduate"></i> My Portal
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline" style="padding: 0.38rem 0.6rem; border-color: rgba(255,255,255,0.15); color: #9ca3af;" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-secondary" style="padding: 0.4rem 0.9rem; font-size: 0.82rem;">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary" style="padding: 0.4rem 0.9rem; font-size: 0.82rem;">Sign Up</a>
                @endauth

                <!-- Mobile Navigation Toggle Button -->
                <button class="mobile-menu-btn" id="mobileNavToggle" aria-label="Toggle Navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-auto-dismiss">
                <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error alert-auto-dismiss">
                <span><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-auto-dismiss">
                <span><i class="fa-solid fa-circle-exclamation"></i> {{ session('warning') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-auto-dismiss">
                <span><i class="fa-solid fa-circle-info"></i> {{ session('info') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div>
                <div class="brand-logo" style="margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-graduation-cap" style="color: #6366f1;"></i>
                    <span>EventSphere</span>
                </div>
                <p style="max-width: 320px; font-size: 0.88rem; color: #9ca3af;">
                    The ultimate centralized college event management, registration, and attendance ecosystem.
                </p>
            </div>

            <div>
                <h4 style="font-size: 1rem; margin-bottom: 1rem; color: #ffffff;">Quick Links</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                    <li><a href="{{ route('events.index') }}">Browse Events</a></li>
                    <li><a href="{{ route('gallery.index') }}">Media Gallery</a></li>
                    <li><a href="{{ route('about') }}">About EventSphere</a></li>
                    <li><a href="{{ route('faq') }}">Frequently Asked Questions</a></li>
                </ul>
            </div>

            <div>
                <h4 style="font-size: 1rem; margin-bottom: 1rem; color: #ffffff;">Support & Contact</h4>
                <p style="font-size: 0.88rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-envelope"></i> support@eventsphere.edu</p>
                <p style="font-size: 0.88rem;"><i class="fa-solid fa-phone"></i> +1 (800) 555-EVENT</p>
            </div>
        </div>
        <div style="max-width: 1280px; margin: 2rem auto 0; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center; font-size: 0.85rem;">
            &copy; {{ date('Y') }} EventSphere College Event System. All rights reserved.
        </div>
    </footer>

    <!-- JavaScript Dependencies -->
    <script src="{{ asset('js/eventsphere.js') }}"></script>
    @yield('scripts')
</body>
</html>
