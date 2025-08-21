document.addEventListener("DOMContentLoaded", function () {
    // SIDEBAR ACTIVE LINK TOGGLE
    const sidebarLinks = document.querySelectorAll("#sidebar .side-menu.top li a");
    sidebarLinks.forEach((link) => {
        const li = link.parentElement;
        link.addEventListener("click", () => {
            sidebarLinks.forEach((l) => l.parentElement.classList.remove("active"));
            li.classList.add("active");
        });
    });

    // TOGGLE SIDEBAR SHOW/HIDE (remembers state)
    const sidebarToggleButton = document.querySelector("#content nav .bx.bx-menu");
    const sidebar = document.getElementById("sidebar");

    // Load saved state on page load
    if (localStorage.getItem("sidebarState") === "hide") {
        sidebar.classList.add("hide");
    }

    sidebarToggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("hide");
        localStorage.setItem(
            "sidebarState",
            sidebar.classList.contains("hide") ? "hide" : "show"
        );
    });

    // NAVBAR SEARCH TOGGLE (Mobile)
    const searchButton = document.querySelector("#content nav form .form-input button");
    const searchButtonIcon = searchButton.querySelector(".bx");
    const searchForm = document.querySelector("#content nav form");

    searchButton.addEventListener("click", function (e) {
        if (window.innerWidth < 576) {
            e.preventDefault();
            searchForm.classList.toggle("show");
            if (searchForm.classList.contains("show")) {
                searchButtonIcon.classList.replace("bx-search", "bx-x");
            } else {
                searchButtonIcon.classList.replace("bx-x", "bx-search");
            }
        }
    });

    // PROFILING SUBMENU BEHAVIOR
    const profilingSubmenu = document.getElementById("profiling-submenu");
    const profilingLink = document.getElementById("profiling-link");

    // If submenu has .active (set by Blade), make it display and rotate the arrow
    if (profilingSubmenu.classList.contains("active")) {
        profilingSubmenu.style.display = "flex";
        profilingLink.querySelector(".bx-chevron-down")?.classList.add("rotate");
    }

    // Toggle submenu when clicking the Profiling link
    profilingLink.addEventListener("click", function (e) {
        e.preventDefault();
        profilingSubmenu.classList.toggle("active");
        profilingSubmenu.style.display =
            profilingSubmenu.classList.contains("active") ? "flex" : "none";
        profilingLink.querySelector(".bx-chevron-down")?.classList.toggle("rotate");
    });
}); 