<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SGRMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Roboto, Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 540px; margin: 40px auto; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 40px 30px; border: #202124 1px solid; color: #333; }
        h1 { text-align: center; font-size: 20px; color: #202124; margin-bottom: 24px; }
        p { font-size: 14px; line-height: 1.6; color: #202124; margin-bottom: 16px; }
        .code-box { background: #fbbf24; color: #202124; font-size: 22px; font-weight: bold; letter-spacing: 2px; padding: 12px 0; text-align: center; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #70757a; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to SGRMS, {{ $user->first_name }}!</h1>
        <p>Your account has been created using your email (<strong>{{ $user->email }}</strong>).</p>
        <p>To activate your account, please enter the following verification code in the activation page:</p>
        <div class="code-box">{{ $user->verification_code }}</div>
        <p><strong>Note:</strong> This code is valid for only 2 hours.</p>
        <p>If you did not request this account, please ignore this email.</p>
        <p>Best regards,<br>The SGRMS Team</p>
    </div>
</body>
</html>
