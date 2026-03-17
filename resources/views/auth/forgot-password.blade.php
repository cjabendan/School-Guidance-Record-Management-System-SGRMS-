@extends('layouts.auth')

@section('title', 'Forgot Password - SGRMS')

@section('content')
    @include('components.loader')

    <div class="auth-container">
        <div class="auth-content">
            <div class="auth-icon-wrapper">
                <i class="fas fa-unlock-alt auth-icon"></i>
            </div>
            <h2 class="auth-header">Forgot your password?</h2>
            <p class="auth-subheader">
                Enter your email address and we will send you a link to reset your password.
            </p>

            @if (session('status'))
                <p class="auth-subheader" style="color: #16a34a; font-weight: 500;">
                    {{ session('status') }}
                </p>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="email" class="auth-label">Email address</label>
                        @error('email')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="auth-input"
                           required
                           autofocus
                           autocomplete="email">
                </div>

                <button type="submit" class="auth-button">
                    Send reset link
                </button>

                <p class="auth-subheader" style="margin-top: 0.75rem;">
                    Remembered your password?
                    <a href="{{ url('/?login=true') }}" class="auth-link">Back to login</a>
                </p>
            </form>
        </div>
    </div>
@endsection

