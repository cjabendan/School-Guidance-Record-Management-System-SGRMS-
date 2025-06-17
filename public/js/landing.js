const btns = document.querySelectorAll(".nav-btn");
const slides = document.querySelectorAll(".video-slide");
const contents = document.querySelectorAll(".content");

let currentSlide = 0;

var sliderNav = function(manual){
    // Reset all videos
    slides.forEach((slide, index) => {
        slide.pause();
        slide.currentTime = 0;
        slide.classList.remove("active");
    });

    contents.forEach((content) => {
        content.classList.remove("active");
    });

    btns.forEach((btn) => {
        btn.classList.remove("active");
    });

    // Activate current slide
    btns[manual].classList.add("active");
    slides[manual].classList.add("active");
    contents[manual].classList.add("active");

    // Play the active video
    slides[manual].play();
}

// Function to transition to next slide
function autoSlide() {
    currentSlide++;
    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }
    sliderNav(currentSlide);
}

// Add event listeners to videos to trigger next slide when they end
slides.forEach((slide, index) => {
    slide.addEventListener('ended', () => {
        autoSlide();
    });
});

// Initial navigation button click event listeners
btns.forEach((btn, i) => {
    btn.addEventListener("click", () => {
        currentSlide = i;
        sliderNav(i);
    });
});

function openAddModal() {
    document.getElementById("login-modal").style.display = "block";
}



// Initially set up the first slide
document.addEventListener('DOMContentLoaded', () => {
    sliderNav(0);
});

 function openLoginModal() {
        document.querySelector('.login-modal').classList.add('show');
    }
    function closeLoginModal() {
        document.querySelector('.login-modal').classList.remove('show');
    }
    window.onclick = function(event) {
        const modal = document.querySelector('.login-modal');
        if (event.target === modal) {
            closeLoginModal();
        }
    }
    function togglePassword() {
        const passwordInput = document.getElementById("login-password");
        const icon = document.getElementById("togglePasswordIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
