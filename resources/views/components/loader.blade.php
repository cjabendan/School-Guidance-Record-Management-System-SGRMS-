<div id="loader-overlay" style="display:none;">
    <div class="dot-spinner">
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
        <div class="dot-spinner__dot"></div>
    </div>

</div>


<style>
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(3px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s;
    }

    .dot-spinner {
        --uib-size: 2.8rem;
        --uib-speed: .9s;
        --uib-color: #1ea7ff;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: var(--uib-size);
        width: var(--uib-size);
    }

    .dot-spinner__dot {
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 100%;
        width: 100%;
    }

    .dot-spinner__dot::before {
        content: '';
        height: 20%;
        width: 20%;
        border-radius: 50%;
        background-color: var(--uib-color);
        transform: scale(0);
        opacity: 0.5;
        animation: pulse0112 calc(var(--uib-speed) * 1.111) ease-in-out infinite;
        box-shadow: 0 0 20px rgba(18, 31, 53, 0.3);
    }

    .dot-spinner__dot:nth-child(2) {
        transform: rotate(45deg);
    }

    .dot-spinner__dot:nth-child(2)::before {
        animation-delay: calc(var(--uib-speed) * -0.875);
    }

    .dot-spinner__dot:nth-child(3) {
        transform: rotate(90deg);
    }

    .dot-spinner__dot:nth-child(3)::before {
        animation-delay: calc(var(--uib-speed) * -0.75);
    }

    .dot-spinner__dot:nth-child(4) {
        transform: rotate(135deg);
    }

    .dot-spinner__dot:nth-child(4)::before {
        animation-delay: calc(var(--uib-speed) * -0.625);
    }

    .dot-spinner__dot:nth-child(5) {
        transform: rotate(180deg);
    }

    .dot-spinner__dot:nth-child(5)::before {
        animation-delay: calc(var(--uib-speed) * -0.5);
    }

    .dot-spinner__dot:nth-child(6) {
        transform: rotate(225deg);
    }

    .dot-spinner__dot:nth-child(6)::before {
        animation-delay: calc(var(--uib-speed) * -0.375);
    }

    .dot-spinner__dot:nth-child(7) {
        transform: rotate(270deg);
    }

    .dot-spinner__dot:nth-child(7)::before {
        animation-delay: calc(var(--uib-speed) * -0.25);
    }

    .dot-spinner__dot:nth-child(8) {
        transform: rotate(315deg);
    }

    .dot-spinner__dot:nth-child(8)::before {
        animation-delay: calc(var(--uib-speed) * -0.125);
    }

    @keyframes pulse0112 {

        0%,
        100% {
            transform: scale(0);
            opacity: 0.5;
        }

        50% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<script>
    // Show loader overlay
    function showLoader() {
        document.getElementById('loader-overlay').style.display = 'flex';
    }
    // Hide loader overlay
    function hideLoader() {
        document.getElementById('loader-overlay').style.display = 'none';
    }

    // Attach to login form submit
    document.addEventListener('DOMContentLoaded', function() {
        var loginForm = document.querySelector('.modal-right form');
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                showLoader();
            });
        }
    });
</script>
