@extends('layouts.app')

@section('title', 'Login - e-TEFA Kompeni')

@section('content')
    <div class="auth-page animate-fadeIn">
        <div class="auth-card">
            <div class="auth-header animate-fadeInUp" style="animation-delay:0.1s">
                <div class="auth-logo">
                    <img src="{{ asset('images/login.jpg.jpeg') }}" alt="e-TEFA Logo">
                </div>
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-subtitle">Sign in to your e-TEFA Kompeni account</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert-error animate-slideInDown">
                    <div class="auth-alert-inner">
                        <svg class="h-5 w-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="auth-alert-success animate-slideInDown">
                    <div class="auth-alert-inner">
                        <svg class="h-5 w-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.2s">
                    <label for="username" class="auth-label">Username</label>
                    <input id="username" name="username" type="text" placeholder="Enter your username"
                        value="{{ old('username') }}" required class="auth-input">
                </div>
                <div class="auth-field animate-fadeInUp" style="animation-delay:0.3s">
                    <label for="password" class="auth-label">Password</label>
                    <div class="password-wrapper">
                        <input id="password" name="password" type="password" placeholder="Enter your password" required class="auth-input">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Show Password">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="auth-btn animate-fadeInUp" style="animation-delay:0.4s">
                    Sign In
                </button>
            </form>

            <div class="auth-footer animate-fadeInUp" style="animation-delay:0.5s">
                Don't have an account?
                <a href="{{ route('register') }}" class="auth-link">Register here</a>
            </div>
        </div>
    </div>
@endsection
