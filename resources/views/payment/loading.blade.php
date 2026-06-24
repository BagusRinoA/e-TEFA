@extends('layouts.app')

@section('title', 'Processing Payment - e-TEFA Kompeni')

@section('content')
    <div class="payment-page">
        <div class="payment-card">
            <div class="payment-icon payment-icon--loading">
                <svg class="h-10 w-10" style="animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="payment-title">Processing Payment</h1>
            <p class="payment-desc">Please wait while we process your payment...</p>

            <div class="payment-detail-box">
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#eff6ff;border-radius:0.5rem">
                        <svg style="width:1.25rem;height:1.25rem;color:#2563eb;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span style="font-size:0.875rem;color:#1d4ed8">Order created successfully</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#eff6ff;border-radius:0.5rem">
                        <svg style="width:1.25rem;height:1.25rem;color:#2563eb;flex-shrink:0;animation:spin 1s linear infinite" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 8v4m6-6h-4m-8 0H2"></path>
                        </svg>
                        <span style="font-size:0.875rem;color:#1d4ed8">Loading payment gateway...</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#f9fafb;border-radius:0.5rem">
                        <svg style="width:1.25rem;height:1.25rem;color:#9ca3af;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span style="font-size:0.875rem;color:#6b7280">Awaiting payment completion</span>
                    </div>
                </div>
            </div>

            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:0.75rem;padding:1rem;margin-bottom:1.5rem">
                <p style="font-size:0.875rem;color:#92400e">
                    <strong>Do not close this page.</strong> You will be redirected to the payment page automatically.
                </p>
            </div>

            <a href="{{ route('cart.index') }}" class="btn-outline" style="width:100%;justify-content:center">Back to Checkout</a>
        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
@endsection
