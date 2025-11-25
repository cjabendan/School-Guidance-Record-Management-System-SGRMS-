<div class="container">
    <div class="card">
        <div class="content">
            <div class="body">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 214 214" class="check-circle">
                    <g fill="none" stroke="currentColor" stroke-width="2">

                        <circle class="semi-transparent" fill="#ffffff" opacity="0.15" cx="107" cy="107"
                            r="72"></circle>

                        <circle class="colored" fill="#ffffff" cx="107" cy="107" r="72" opacity="1">
                        </circle>

                        <circle class="colored" fill="#ffffff" cx="107" cy="107" r="72" opacity="1">
                        </circle>

                        <polyline stroke="#003060" stroke-width="10" points="73.5,107.8 93.7,127.9 142.2,79.4"
                            style="stroke-dasharray: 50%, 50%; stroke-dashoffset: 100%" />
                    </g>
                </svg>

                <h2 class="heading">Email Verified!</h2>

            </div>
        </div>
        <div class="content-body">
            <p class="subheading">
                Your email has been successfully verified. You can now log in to your account.
                You will be redirected to login page in <span id="count">5</span> seconds.
            </p>
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
        max-width: 600px;
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

    .body {
        display: flex;
        width: 100%;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem 0;
        background: #003060
    }

    .body h2 {
        color: #ffffff;
        font-size: 28px;
        font-weight: 600;
    }

    .content-body {
        display: flex;

        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        text-align: center;
    }

    .icon {
        width: 200px;
        max-width: 100%;
    }

    .heading {
        color: #003060;
        font-weight: 600;
        margin: .8rem 0;
    }

    .subheading {
        font-size: 16px;
        color: #555555;
        line-height: 1.5;
        margin: .8rem 0;
    }

    svg.check-circle {
        color: #ffffff;
        width: 130px;
        height: auto;
    }

    svg.check-circle polyline,
    svg.check-circle circle.semi-transparent,
    svg.check-circle circle.colored {
        animation-play-state: paused;
    }

    @keyframes checkmark {
        0% {
            stroke-dashoffset: 50%;
        }

        100% {
            stroke-dashoffset: 0px;
        }
    }

    svg.check-circle polyline {
        animation: checkmark 0.5s cubic-bezier(0.55, 0.2, 0.71, -0.04) 0.7s backwards;
    }

    @keyframes grow-circle {
        0% {
            r: 0;
        }
    }

    @keyframes grow-circle-bigger {
        50% {
            opacity: 0.11;
        }

        100% {
            r: 100;
            opacity: 0;
        }
    }

    svg.check-circle circle.semi-transparent {
        animation: grow-circle 0.45s cubic-bezier(0.66, 0.23, 0.51, 1.23) backwards,
            grow-circle-bigger 0.9s linear 1.1s forwards;
    }

    svg.check-circle circle.colored {
        animation: grow-circle 0.5s cubic-bezier(0.66, 0.23, 0.51, 1.23) 0.25s backwards;
    }

    @keyframes popup {
        0% {
            opacity: 0;
            transform: scale(0.7);
        }

        60% {
            transform: scale(1.05);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .container {
        animation: popup 0.45s ease-out forwards;
    }
</style>


<script>
    let seconds = 5;
    const countdownDisplay = document.getElementById("count");
    const redirectUrl = "<?php echo e(url('/?login=true')); ?>";

    const interval = setInterval(() => {
        seconds--;
        countdownDisplay.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = redirectUrl;
        }
    }, 1000);

    setTimeout(() => {

        // ⭐ START CHECKMARK ANIMATION by removing stroke-dashoffset
        document.querySelector(".check-circle polyline").style.animationPlayState = "running";
        document.querySelector(".check-circle circle.semi-transparent").style.animationPlayState = "running";
        document.querySelector(".check-circle circle.colored").style.animationPlayState = "running";

        // ⭐ START COUNTDOWN
        let seconds = 5;
        const countdownDisplay = document.getElementById("count");
        const redirectUrl = "<?php echo e(url('/?login=true')); ?>";

        const interval = setInterval(() => {
            seconds--;
            countdownDisplay.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = redirectUrl;
            }
        }, 1000);

    }, 500);
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/auth/success-verification.blade.php ENDPATH**/ ?>