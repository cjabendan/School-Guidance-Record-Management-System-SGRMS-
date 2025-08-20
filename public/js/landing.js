const btns = document.querySelectorAll(".nav-btn");
const slides = document.querySelectorAll(".video-slide");
const contents = document.querySelectorAll(".content");

let currentSlide = 0;

// Function to show a specific slide
function sliderNav(manual) {
    // Reset all videos
    slides.forEach((slide) => {
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

    // Activate selected slide
    btns[manual].classList.add("active");
    slides[manual].classList.add("active");
    contents[manual].classList.add("active");

    // Play the current video
    slides[manual].play();
}

// Function to auto-slide every 6 seconds
function autoSlide() {
    currentSlide++;
    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }
    sliderNav(currentSlide);
}

// Start auto-sliding every 6 seconds
setInterval(autoSlide, 10000);

// Also allow manual navigation
btns.forEach((btn, index) => {
    btn.addEventListener("click", () => {
        currentSlide = index;
        sliderNav(index);
    });
});

function openAddModal() {
    document.getElementById("login-modal").style.display = "block";
}

// Initially set up the first slide
document.addEventListener("DOMContentLoaded", () => {
    sliderNav(0);
});

function openLoginModal() {
    document.querySelector(".login-modal").classList.add("show");
}
function closeLoginModal() {
    document.querySelector(".login-modal").classList.remove("show");
}
window.onclick = function (event) {
    const modal = document.querySelector(".login-modal");
    if (event.target === modal) {
        closeLoginModal();
    }
};
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

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }
  if (getQueryParam('login') === 'true') {
    openLoginModal('login-modal');
  }
