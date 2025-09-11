<div class="container">
    <div class="card">
        <div class="content">
            <div class="header">
                <div class="left">
                    <img src="{{ asset('images/icons/email-pending.png') }}" alt="Email Pending Icon" class="icon">
                </div>
                <div class="right">
                    <h2 class="heading">ACCOUNT ACTIVATION</h2>
                    <p class="subheading">To ensures you receive updates, stay connected, and recover your account if
                        needed.
                        Please verify your email address by clicking the link we sent to:
                    </p>
                    <p class="email">
                        <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank"
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
                        <a href="#" class="resend">Resend Email Link</a>
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
        background-color: #daedff;
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
        max-width: 900px;
    }

    .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: left;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .content {
        display: flex;
        flex-direction: column;
    }

    .left {
        display: flex;
        width: 40%;
        justify-content: center;
        align-self: center;
    }

    .icon {
        width: 350px;
    }

    .right {
        display: flex;
        flex-direction: column;
        align-self: center;
        gap: 0.5rem;
        padding: 1rem 1rem 1rem 0;
        margin-left: -20px;

    }

    .header,
    .footer,
    form {
        display: flex;
        flex-direction: row;
    }

    .header {
        padding: 1rem 1rem 0 1rem;
    }

    .heading {
        font-size: 38px;
        color: #1ea7ff;
        text-transform: uppercase;
        text-align: center;
    }

    .subheading {
        font-size: 18px;
        color: #555555;
        text-align: center;
        letter-spacing: .5px;
        line-height: 1.4;
        margin: 1rem 0;
    }

    .email {
        font-size: 18px;
        color: #1ea7ff;
        letter-spacing: 1.5px;
        text-align: center;
        margin-bottom: 1rem;
        cursor: pointer;
    }

    .note {
        font-size: 16px;
        color: #888888;
        text-align: center;
        letter-spacing: .5px;
    }

    .footer {
        background-color: #f9f9f9;
        padding: 1rem;
    }

    .resendEmail {
        display: flex;
        width: 100%;
        justify-content: flex-end;
        align-items: center;
        padding: 0 1.5rem;
    }

    .resendNote {
        font-size: 16px;
        color: #555555;
        align-self: center;
        margin-right: 10px;
    }

    .resend {
        color: #1ea7ff;
        padding: 8px 18px;
        cursor: pointer;
        font-size: 16px;
    }

    .resend:hover {
        color: #0080ff;
    }
</style>
