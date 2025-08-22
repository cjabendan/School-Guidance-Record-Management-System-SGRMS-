@extends('layouts.app')
@section('content')
<div class="container" style="max-width: 500px; margin: 60px auto; text-align: center;">
    <div class="card" style="padding: 40px 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #16a34a; margin-bottom: 20px;">Account Activated!</h2>
        <p style="font-size: 16px; margin-bottom: 30px;">Your account has been successfully activated. You can now access your dashboard.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-success" style="padding: 12px 30px; font-size: 16px; border-radius: 5px; background: #16a34a; color: #fff; text-decoration: none;">Go to Dashboard</a>
    </div>
</div>
@endsection
