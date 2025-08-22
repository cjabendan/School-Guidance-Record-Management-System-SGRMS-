@extends('layouts.reg')
@section('content')
<div class="container" style="max-width: 400px; margin: 60px auto;">
    <div class="card" style="padding: 40px 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        <h2 style="text-align:center; margin-bottom: 20px;">Account Verification</h2>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('verify.code.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="input" required placeholder="Enter your email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" name="verification_code" id="verification_code" class="input" required placeholder="Enter the code from your email">
            </div>
            <button type="submit" class="button-submit" style="width:100%;">Verify Account</button>
        </form>
    </div>
</div>
@endsection
