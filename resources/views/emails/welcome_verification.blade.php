<div class="container">
    <h1>Welcome to SGRMS, {{ $user->first_name }}!</h1>
    <p>Your account has been created using your email (<strong>{{ $user->email }}</strong>).</p>
    <p>To activate your account, please click the link below:</p>
    <div class="verifBox">
        <a href="{{ $activationLink }}">Activate My Account</a>
    </div>
    <p><strong>Note:</strong> This link is valid for only 2 hours.</p>
    <p>If you did not request this account, please ignore this email.</p>
    <p>Best regards,<br>The SGRMS Team</p>
</div>
