<div class="container">
    <a href="{{ url('/?login=true') }}" class="back-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Go back</a>
    <div class="card">
        <div class="content">
            <div class="header">
                <h2>SGRMS</h2>
            </div>
            <div class="content-body">
                <div class="left">
                    <img src="{{ asset('images/icons/email-pending.png') }}" alt="Email Pending Icon" class="icon">
                </div>
                <div class="right">
                    <h2 class="heading">Please confirm your email address</h2>
                    <p class="subheading">
                        Verify your email to activate your account, stay connected, and recover it if needed. Click the
                        link we sent to:

                    </p>
                    <p class="email">
                        <a href="https://mail.google.com/mail/u/0/#inbox"
                            style="color:#1ea7ff;text-decoration:underline;">
                            {{ session('registered_email') }}
                        </a>
                    </p>
                    <p class="note">If you didn't receive the email, please check your spam folder.</p>
                </div>
            </div>
            <div class="footer">
                <div class="resendEmail">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <p class="resendNote">Didn’t receive the email?</p>
                        <a href="#" class="resend">Resend Email</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background-color: #ffffff;
        font-family: 'Poppins', sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    a,
    button {
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }

    .container {
        border-radius: 10px;
        padding: 2rem;
        width: 100%;
        max-width: 650px;
        position: relative;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: #003060;
        font-size: 16px;
        font-weight: 520;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .back-btn svg {
        width: 20px;
        height: 20px;
    }

    .back-btn:hover {
        color: #0080ff;
    }

    .back-btn:active {
        opacity: 0.8;
        /* subtle feedback when clicked */
    }

    .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: left;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .content {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .header {
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: center;
        padding: 1rem 0;
        /* background: linear-gradient(to right, rgb(21, 163, 255), rgb(0, 48, 96)); */
        background: rgb(0, 48, 96)
    }

    .header h2 {
        color: #ffffff;
        font-size: 28px;
        font-weight: 600;
    }

    .content-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        text-align: center;
    }

    .left {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .icon {
        width: 200px;
        max-width: 100%;
    }

    .right {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .heading {
        color: #003060;
        font-weight: 600;
    }

    .subheading {
        font-size: 16px;
        color: #555555;
        line-height: 1.5;
        margin: .8rem 0;
    }

    .email {
        font-size: 18px;
        color: #1ea7ff;
        margin-bottom: 1rem;
    }

    .note {
        font-size: 15px;
        color: #888888;
        margin-bottom: .5rem;
    }

    .footer {
        background-color: #f9f9f9;
        padding: 1rem;
        border-top: 1px solid #eee;
    }

    .resendEmail form {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
        width: 100%;
    }

    .resendNote {
        font-size: 16px;
        color: #555555;
    }

    .resend {
        color: #1ea7ff;
        padding: 8px 12px;
        font-size: 16px;
    }

    .resend:hover {
        color: #0080ff;
    }
</style>
