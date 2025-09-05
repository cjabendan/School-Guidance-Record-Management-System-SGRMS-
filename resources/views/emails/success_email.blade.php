
@if (!empty($user->is_signup))
    <div class="container">
        <h1>Congratulations, {{ $user->first_name }}!</h1>
        <p>Your email has been successfully verified and your SGRMS account is now active.</p>
        <p>You can now log in and access your account.</p>
        <p>Best regards,<br>The SGRMS Team</p>
    </div>
@else
    <div class="container">
        <h2>Welcome, {{ $user->first_name }} {{ $user->last_name }}!</h2>
        <p>Your parent account has been successfully created and is now active.</p>
        <p>You can now log in using your email: <strong>{{ $user->email }}</strong></p>
        <p>If you have any questions, please contact the school guidance office.</p>
        <br>
        <p>Thank you!</p>
    </div>
@endif