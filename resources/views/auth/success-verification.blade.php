<div class="container">
    <div class="card">
        <div class="content">


            <img src="{{ asset('images/icons/email-verify.png') }}" alt="Email Verify Icon" class="icon">

            <h2 class="heading">ACCOUNT ACTIVATED</h2>
            <p class="subheading"> Your email has been successfully verified! You can now log in and access your
                account.
            </p>
            <a href="{{ url('/?login=true') }}" class="btn">Go to Login</a>

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
        padding: 1.5rem;
        width: 100%;
        max-width: 750px;
    }

    .card {
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        border-radius: 10px;
        padding: 1rem 2rem 2rem 2rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }


    .icon {
        width: 250px;
        margin-top: -20px;
    }

    .heading {
        font-size: 38px;
        color: #1ea7ff;
        text-transform: uppercase;
        text-align: center;
        margin-top: -10px;
    }

    .subheading {
        font-size: 18px;
        color: #555555;
        text-align: center;
        letter-spacing: .5px;
        line-height: 1.4;
        margin: 1rem 0;
        padding: 0 2rem;
    }

    .btn {
        color: #ffffff;
        background-color: #1ea7ff;
        padding: 10px 30px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0080ff;
    }
</style>
