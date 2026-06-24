@extends('layouts.app')

@section('title', 'Register - e-TEFA Kompeni')

@section('content')
    <div class="auth-page animate-fadeIn">
        <div class="auth-card">
            <div class="auth-header animate-fadeInUp" style="animation-delay:0.1s">
                <div class="auth-logo">
                    <img src="{{ asset('images/login.jpg.jpeg') }}" alt="e-TEFA Logo">
                </div>
                <h2 class="auth-title">Create Account</h2>
                <p class="auth-subtitle">Join e-TEFA Kompeni community today</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert-error animate-slideInDown">
                    <div class="auth-alert-inner">
                        <svg class="h-5 w-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <ul style="margin:0;padding-left:0;list-style:none;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.2s">
                    <label for="username" class="auth-label">Username</label>
                    <input id="username" name="username" type="text" placeholder="Choose a username"
                        value="{{ old('username') }}" required class="auth-input">
                </div>
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.3s">
                    <label for="email" class="auth-label">Email</label>
                    <input id="email" name="email" type="email" placeholder="your@email.com"
                        value="{{ old('email') }}" required class="auth-input">
                </div>
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.4s">
                    <label for="password" class="auth-label">Password</label>
                    <div class="password-wrapper">
                        <input id="password" name="password" type="password" placeholder="Create a password" required class="auth-input">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Show Password">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.5s">
                    <label for="password_confirmation" class="auth-label">Confirm Password</label>
                    <div class="password-wrapper">
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm your password" required class="auth-input">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)" title="Show Password">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="auth-btn animate-fadeInUp" style="animation-delay:0.6s">
                    Create Account
                </button>
            </form>

            <div class="auth-footer animate-fadeInUp" style="animation-delay:0.7s">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link">Sign in here</a>
            </div>
        </div>
    </div>
@endsection
