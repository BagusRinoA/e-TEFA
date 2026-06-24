@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
    <div class="admin-page">
        <div class="admin-container">
            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ $user->full_name ?? $user->username }}</h1>
                    <p class="admin-page-subtitle">Registered user details and preferences.</p>
                </div>
                <a href="{{ route('admin.users') }}" class="admin-back-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="admin-form-card">
                    <h2 class="text-xl font-semibold mb-4" style="color:var(--color-foreground)">Profile</h2>

                    <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                        <div class="admin-user-avatar" style="width: 6rem; height: 6rem; font-size: 2rem;">
                            @if ($user->profile_photo)
                                <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://via.placeholder.com/120x120?text=No+Photo' }}"
                                    alt="{{ $user->full_name }}" class="w-full h-full object-cover" style="border-radius: 9999px;">
                            @else
                                {{ strtoupper(substr($user->username, 0, 2)) }}
                            @endif
                        </div>

                        <div class="space-y-3 text-sm text-muted-foreground">
                            <p><span class="font-semibold text-foreground">Username:</span> {{ $user->username }}</p>
                            <p><span class="font-semibold text-foreground">Email:</span> {{ $user->email }}</p>
                            <p><span class="font-semibold text-foreground">Role:</span> <span class="status-badge {{ $user->role === 'admin' || $user->role === 'superadmin' ? 'role-admin' : 'role-user' }}">{{ ucfirst($user->role) }}</span></p>
                            <p><span class="font-semibold text-foreground">Joined:</span>
                                {{ $user->created_at->format('d M Y') }}</p>
                            <p><span class="font-semibold text-foreground">Email Notifications:</span>
                                {{ $user->email_notifications ? 'Enabled' : 'Disabled' }}</p>
                            <p><span class="font-semibold text-foreground">Forum Notifications:</span>
                                {{ $user->forum_notifications ? 'Enabled' : 'Disabled' }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-form-card">
                    <h2 class="text-xl font-semibold mb-4" style="color:var(--color-foreground)">About</h2>
                    <p class="text-sm text-muted-foreground">{{ $user->bio ?? 'No bio available.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
