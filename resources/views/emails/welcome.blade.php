<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Arculus</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
<style>
     body {
      font-family: Roboto, Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 540px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      padding: 40px 30px;
      border: #202124 1px solid;
      color: #333;
    }
    .logo {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      color: #fbbf24;
      margin-bottom: 30px;
      letter-spacing: 1px;
    }
    h1 {
      text-align: center;
      font-size: 20px;
      color: #202124;
      margin-bottom: 24px;
    }
    p {
      font-size: 14px;
      line-height: 1.6;
      color: #202124;
      margin-bottom: 16px;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #70757a;
      margin-top: 30px;
      border-top: 1px solid #ddd;
      padding-top: 20px;
    }
</style>
</head>
<body>
    <div class="container">

        <h1>Welcome to SGRMS, {{ $user->first_name }}!</h1>
        <p>Your account has just been successfully created using your email (<strong>{{ $user->email }}</strong>).</p>


        <p>You can log in to your account using the button below:</p>
        <p>
            <a href="{{ route('auto-login', ['token' => $user->login_token]) }}"
               style="display: inline-block; padding: 12px 24px; background-color: #fbbf24; color: #000000; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Verify Email
            </a>
        </p>
        <p><strong>Note:</strong> This link is valid for only 2 hours.</p>
        <p>Best regards,<br>The Arculus Team</p>
</body>
</html>
