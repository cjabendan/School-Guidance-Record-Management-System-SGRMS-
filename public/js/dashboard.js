  document.addEventListener('DOMContentLoaded', function() {
        const profilingLink = document.getElementById('profiling-link');
        const profilingSubmenu = document.getElementById('profiling-submenu');
        profilingLink.addEventListener('click', function(e) {
            e.preventDefault();
            profilingSubmenu.classList.toggle('active');
        });
    });