@extends('layouts.app')

@section('title', 'Profile Settings - e-TEFA Kompeni')

@section('content')
    <div class="user-dash-page">
        <div class="user-dash-container">
            <div class="user-dash-layout">
                @if (Auth::user()->isAdmin())
                    @include('admin.partials.sidebar')
                @else
                    @include('dashboard.partials.sidebar')
                @endif

                <div class="user-dash-main">
                    <div>
                        <h1 class="user-dash-title">Profile Settings</h1>
                        <p class="user-dash-subtitle">Manage your account settings and preferences</p>
                    </div>

                    @if (session('success'))
                        <div class="auth-alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="auth-alert-error">
                            <ul style="list-style:disc;padding-left:1.25rem;font-size:0.875rem">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="display:flex;flex-direction:column;gap:1.5rem">
                        {{-- Profile Information --}}
                        <div class="profile-card">
                            <h2 class="profile-card-title">Profile Information</h2>
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:1.5rem">Update your personal information and public profile</p>

                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf @method('PUT')

                                <div class="profile-avatar-section">
                                    <div class="profile-avatar" id="profileAvatarWrap">
                                        @if (Auth::user()->profile_photo)
                                            <img id="profile-photo-preview" src="{{ Storage::url(Auth::user()->profile_photo) . '?t=' . time() }}" alt="Profile">
                                        @else
                                            <div class="profile-avatar-placeholder" id="profile-photo-initials">
                                                {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" class="btn-primary" style="margin-bottom:0.5rem"
                                            onclick="document.getElementById('photo').click()">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Change Photo
                                        </button>
                                        <input id="photo" name="profile_photo" type="file" accept="image/*"
                                            style="display:none" onchange="handleProfilePhotoChange(event)">
                                        <p id="selected-photo-name" style="font-size:0.875rem;color:var(--color-muted-foreground)">JPG, PNG or GIF. Max size 5MB</p>
                                        @error('profile_photo')
                                            <p style="font-size:0.875rem;color:var(--color-destructive);margin-top:0.25rem">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div style="display:grid;gap:1rem;grid-template-columns:1fr 1fr;margin-bottom:1rem">
                                    <div class="profile-field">
                                        <label for="full_name" class="profile-label">Full Name</label>
                                        <input id="full_name" name="full_name" type="text"
                                            value="{{ old('full_name', Auth::user()->full_name) }}" class="profile-input">
                                    </div>
                                    <div class="profile-field">
                                        <label for="username" class="profile-label">Username</label>
                                        <input id="username" name="username" type="text"
                                            value="{{ old('username', Auth::user()->username) }}" class="profile-input">
                                    </div>
                                </div>

                                <div class="profile-field">
                                    <label for="email" class="profile-label">Email Address</label>
                                    <input id="email" name="email" type="email"
                                        value="{{ old('email', Auth::user()->email) }}" class="profile-input">
                                </div>

                                <div class="profile-field">
                                    <label for="bio" class="profile-label">Bio</label>
                                    <textarea id="bio" name="bio" rows="4" class="profile-input"
                                        placeholder="Tell us a bit about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                </div>

                                <div class="profile-actions">
                                    <button type="submit" class="profile-save-btn">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        {{-- Change Password --}}
                        <div class="profile-card">
                            <h2 class="profile-card-title">Change Password</h2>
                            <p style="font-size:0.875rem;color:var(--color-muted-foreground);margin-bottom:1.5rem">Update your password to keep your account secure</p>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf @method('PUT')
                                <div class="profile-field">
                                    <label for="current_password" class="profile-label">Current Password</label>
                                    <div class="password-wrapper">
                                        <input id="current_password" name="current_password" type="password" class="profile-input">
                                        <button type="button" class="password-toggle" onclick="togglePassword('current_password', this)" title="Show Password">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="profile-field">
                                    <label for="password" class="profile-label">New Password</label>
                                    <div class="password-wrapper">
                                        <input id="password" name="password" type="password" class="profile-input">
                                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Show Password">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="profile-field">
                                    <label for="password_confirmation" class="profile-label">Confirm New Password</label>
                                    <div class="password-wrapper">
                                        <input id="password_confirmation" name="password_confirmation" type="password" class="profile-input">
                                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)" title="Show Password">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="profile-actions">
                                    <button type="submit" class="profile-save-btn">Change Password</button>
                                </div>
                            </form>
                        </div>

                        {{-- Danger Zone --}}
                        <div class="profile-card" style="background:#fef2f2;border-color:#fecaca">
                            <h2 style="font-size:1.125rem;font-weight:700;color:#b91c1c;margin-bottom:0.5rem">Danger Zone</h2>
                            <p style="font-size:0.875rem;color:#dc2626;margin-bottom:1.5rem">Irreversible actions that affect your account</p>
                            <form method="POST" action="{{ route('profile.destroy') }}"
                                onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:0.5rem 1.5rem;border-radius:0.5rem;background:#dc2626;color:#fff;font-weight:600;border:none;cursor:pointer">Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleProfilePhotoChange(event) {
            const file = event.target.files[0];
            document.getElementById('selected-photo-name').textContent = file ? file.name : 'JPG, PNG or GIF. Max size 5MB';
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    let preview = document.getElementById('profile-photo-preview');
                    if (!preview) {
                        const wrap = document.getElementById('profileAvatarWrap');
                        wrap.innerHTML = '';
                        preview = document.createElement('img');
                        preview.id = 'profile-photo-preview';
                        preview.style.cssText = 'width:100%;height:100%;object-fit:cover';
                        wrap.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
