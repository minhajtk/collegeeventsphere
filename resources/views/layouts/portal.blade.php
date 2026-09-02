<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="description" content="EventSphere - Management Portal">
    <title>@yield('title', 'EventSphere Management Portal')</title>

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="{{ asset('css/eventsphere.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>

<!-- Portal Sidebar Overlay (mobile) -->
<div class="portal-sidebar-overlay" id="portalSidebarOverlay"></div>

<div class="portal-layout">
    <!-- Sleek Admin Sidebar -->
    <aside class="portal-sidebar" id="portalSidebar">
        <!-- Mobile Sidebar Close Button -->
        <button class="mobile-sidebar-close" id="portalSidebarClose" aria-label="Close Sidebar" style="display: none; position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.08); border: 1px solid var(--border-color); color: #ffffff; border-radius: var(--radius-sm); padding: 0.35rem 0.55rem; cursor: pointer; font-size: 0.95rem;">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Sidebar Brand Header -->
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="brand-logo" style="font-size: 1.3rem;">
                <i class="fa-solid fa-graduation-cap" style="color: #ffffff;"></i>
                <span>EventSphere</span>
            </a>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.5rem;">
                <span class="role-pill role-{{ Auth::user()->role }}" style="font-size: 0.65rem;">
                    {{ Auth::user()->isAdmin() ? 'Super Admin' : 'Event Organizer' }}
                </span>
                <span style="font-size: 0.72rem; color: #4ade80; font-family: var(--font-sub); display: flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 7px; height: 7px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Online
                </span>
            </div>
        </div>

        <!-- Sidebar User Profile Card -->
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div style="overflow: hidden; flex: 1;">
                <div style="font-family: var(--font-sub); font-weight: 700; font-size: 0.88rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #ffffff;">
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->department ?? Auth::user()->email }}
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav style="flex: 1; overflow-y: auto; padding-right: 0.25rem;">
            <ul class="sidebar-menu">
                @if(Auth::user()->isAdmin())
                    <li class="sidebar-item-label">Control Center</li>

                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-gauge-high" style="color: #ffffff;"></i> Dashboard
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.pending') }}" class="sidebar-link {{ request()->routeIs('admin.events.pending') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-clock" style="color: #eab308;"></i> Pending Proposals
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-users-gear" style="color: #3b82f6;"></i> User Management
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.content') }}" class="sidebar-link {{ request()->routeIs('admin.content') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-shield-halved" style="color: #a855f7;"></i> Moderation Center
                            </div>
                        </a>
                    </li>

                    <li class="sidebar-item-label">Reports & Exports</li>
                    <li>
                        <a href="{{ route('admin.reports.export', 'participation') }}" class="sidebar-link">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-file-csv" style="color: #22c55e;"></i> Registrations CSV
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.export', 'feedback') }}" class="sidebar-link">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-file-csv" style="color: #eab308;"></i> Feedbacks CSV
                            </div>
                        </a>
                    </li>

                @elseif(Auth::user()->isOrganizer())
                    <li class="sidebar-item-label">Organizer Panel</li>

                    <li>
                        <a href="{{ route('organizer.dashboard') }}" class="sidebar-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-chart-pie" style="color: #ffffff;"></i> Overview & Events
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.events.create') }}" class="sidebar-link {{ request()->routeIs('organizer.events.create') ? 'active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="fa-solid fa-plus-circle" style="color: #22c55e;"></i> Create Event Proposal
                            </div>
                        </a>
                    </li>
                @endif

                <li class="sidebar-item-label">Public Website</li>
                <li>
                    <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                        <div class="sidebar-link-content">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="color: #9ca3af;"></i> View Public Site
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}" class="sidebar-link">
                        <div class="sidebar-link-content">
                            <i class="fa-solid fa-calendar-days" style="color: #9ca3af;"></i> All Campus Events
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gallery.index') }}" class="sidebar-link">
                        <div class="sidebar-link-content">
                            <i class="fa-solid fa-photo-film" style="color: #9ca3af;"></i> Media Gallery
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div style="padding-top: 1rem; border-top: 1px solid var(--border-color); margin-top: auto;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="width: 100%; font-size: 0.82rem; justify-content: center; border-color: rgba(255,255,255,0.15);">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="portal-content">
        <!-- Mobile Portal Topbar -->
        <div class="portal-mobile-topbar" id="portalMobileTopbar" style="display: none;">
            <a href="{{ route('home') }}" class="brand-logo" style="font-size: 1.1rem;">
                <i class="fa-solid fa-graduation-cap" style="color: #6366f1;"></i>
                <span>EventSphere</span>
            </a>
            <button id="portalSidebarToggle" aria-label="Open Sidebar" style="background: rgba(255,255,255,0.08); border: 1px solid var(--border-color); color: #ffffff; border-radius: var(--radius-sm); padding: 0.4rem 0.7rem; cursor: pointer; font-size: 1rem;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <!-- Flash Alerts -->
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

        @yield('content')
    </main>
</div>

<!-- JavaScript Dependencies -->
<script src="{{ asset('js/eventsphere.js') }}"></script>
@yield('scripts')
<script>
// Portal Mobile Sidebar Toggle
(function() {
    var sidebar = document.getElementById('portalSidebar');
    var overlay = document.getElementById('portalSidebarOverlay');
    var toggleBtn = document.getElementById('portalSidebarToggle');
    var closeBtn = document.getElementById('portalSidebarClose');
    var mobileTopbar = document.getElementById('portalMobileTopbar');

    function isMobile() { return window.innerWidth <= 768; }

    function showMobileUI() {
        if (mobileTopbar) mobileTopbar.style.display = 'flex';
        if (closeBtn) closeBtn.style.display = 'inline-flex';
    }
    function hideMobileUI() {
        if (mobileTopbar) mobileTopbar.style.display = 'none';
        if (closeBtn) closeBtn.style.display = 'none';
    }

    function openSidebar() {
        if (sidebar) sidebar.classList.add('mobile-open');
        if (overlay) overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (overlay) overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (isMobile()) showMobileUI();
    window.addEventListener('resize', function() {
        if (isMobile()) { showMobileUI(); closeSidebar(); }
        else { hideMobileUI(); closeSidebar(); }
    });

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
})();
</script>
</body>
</html>
