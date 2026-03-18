@extends('layouts.auth')

@section('title', 'Reset Password - SGRMS')

@section('content')
    @include('components.loader')

    <div class="auth-container">
        <div class="auth-content">
            <div class="auth-icon-wrapper">
                <i class="fas fa-key auth-icon"></i>
            </div>
            <h2 class="auth-header">Reset your password</h2>
            <p class="auth-subheader">
                Create a new password for your account.
            </p>

            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

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
                           value="{{ old('email', $email) }}"
                           class="auth-input"
                           required
                           autocomplete="email">
                </div>

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="password" class="auth-label">New password</label>
                        @error('password')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <input id="password"
                           type="password"
                           name="password"
                           class="auth-input"
                           required
                           autocomplete="new-password">
                </div>

                <div class="auth-flex">
                    <div class="auth-label-row">
                        <label for="password_confirmation" class="auth-label">Confirm new password</label>
                    </div>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           class="auth-input"
                           required
                           autocomplete="new-password">
                </div>

                <button type="submit" class="auth-button">
                    Reset password
                </button>

                <p class="auth-subheader" style="margin-top: 0.75rem;">
                    Remembered your password?
                    <a href="{{ url('/?login=true') }}" class="auth-link">Back to login</a>
                </p>
            </form>
        </div>
    </div>
@endsection

