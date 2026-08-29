@extends('layouts.portal')

@section('title', 'User Management - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Admin Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">User Management</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-users-gear" style="color: var(--accent);"></i> User Management</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Manage accounts, switch roles (student / organizer / admin), suspend/activate accounts & reset passwords
        </p>
    </div>
</div>

<!-- Search & Role Filters -->
<div class="portal-card" style="padding: 1.15rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 240px;" class="input-icon-wrapper">
            <i class="fa-solid fa-magnifying-glass prefix-icon"></i>
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, enrolment, username..." value="{{ request('search') }}">
        </div>
        <div style="width: 170px;">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="organizer" {{ request('role') == 'organizer' ? 'selected' : '' }}>Organizer</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Apply Filter</button>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<!-- Users Table -->
<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;">Registered Users ({{ $users->total() }})</h3>
    </div>

    @if($users->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Enrolment / ID</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 34px; height: 34px; border-radius: 50%; background: #ffffff; color: #000000; display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: var(--font-funky); font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong style="color: #ffffff; font-size: 0.92rem;">{{ $user->name }}</strong>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-sub);">{{ $user->email }} • @ {{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code style="font-size: 0.8rem; background: rgba(255,255,255,0.06); padding: 0.15rem 0.4rem; border-radius: 4px;">{{ $user->enrolment_number ?? '-' }}</code></td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $user->department ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                                    @csrf
                                    <select name="role" class="form-select" style="padding: 0.25rem 0.5rem; font-size: 0.78rem; width: auto;" onchange="this.form.submit()">
                                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="status-pill status-active"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Active</span>
                                @else
                                    <span class="status-pill status-rejected"><i class="fa-solid fa-circle" style="font-size: 0.45rem;"></i> Suspended</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem;">
                                    <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-success' }}" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="Toggle Status">
                                            {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </form>

                                    <button data-modal-target="resetPwdModal-{{ $user->id }}" class="btn btn-sm btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="Reset Password">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                </div>

                                <!-- Password Reset Modal -->
                                <div id="resetPwdModal-{{ $user->id }}" class="modal-backdrop">
                                    <div class="modal-card">
                                        <h3 style="font-size: 1.15rem; margin-bottom: 0.85rem;">Reset Password for {{ $user->name }}</h3>
                                        <form action="{{ route('admin.users.password', $user->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">New Password *</label>
                                                <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Confirm New Password *</label>
                                                <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="Repeat new password">
                                            </div>
                                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1rem;">
                                                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $users->links() }}
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No user accounts found matching your filter criteria.</p>
    @endif
</div>
@endsection
