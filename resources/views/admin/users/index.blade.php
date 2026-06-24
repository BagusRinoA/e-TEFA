@extends('layouts.admin')

@section('title', 'Users - Admin')

@section('admin-content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Manage Users</h1>
            <p class="admin-page-subtitle">Browse and manage registered users.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="admin-alert admin-alert-error">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if (Auth::user()->isSuperAdmin())
        <div class="admin-alert" style="background:#eff6ff;border-color:#bfdbfe;color:#1e40af;margin-bottom:1.5rem">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Anda login sebagai <strong>Superadmin</strong> — anda bisa mengubah role user antara <em>user</em> dan
            <em>admin</em>.
        </div>
    @endif

    {{-- Tabel User --}}
    <div class="admin-table-card">
        <div class="admin-table-card-header">
            <span class="admin-table-card-title">All Users</span>
            <span class="admin-page-subtitle">{{ $users->total() }} total users</span>
        </div>

        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="admin-user-cell">
                                    <div class="admin-user-avatar">
                                        {{ strtoupper(substr($user->full_name ?? ($user->username ?? '?'), 0, 1)) }}
                                    </div>
                                    <span class="font-medium">{{ $user->full_name ?? $user->username }}</span>
                                </div>
                            </td>
                            <td class="td-muted">{{ $user->email }}</td>
                            <td>
                                @if (Auth::user()->isSuperAdmin() && !$user->isSuperAdmin() && $user->id !== Auth::id())
                                    {{-- Dropdown role change untuk superadmin --}}
                                    <form action="{{ route('admin.users.update-role', $user) }}" method="POST"
                                        style="display:inline-flex;align-items:center;gap:0.5rem">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()"
                                            style="font-size:0.8125rem;padding:0.25rem 0.5rem;border:1px solid var(--color-border);border-radius:0.5rem;background:#fff;cursor:pointer;color:var(--color-foreground)">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                        </select>
                                    </form>
                                @else
                                    <span class="status-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </td>
                            <td class="td-muted">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="admin-action-link">
                                    <svg width="13" height="13" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="td-empty">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    class="admin-empty-icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Badge style for superadmin role --}}
    <style>
        .role-superadmin {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .role-admin {
            background: #fef9c3;
            color: #a16207;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .role-user {
            background: #f0fdf4;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endsection
