document.addEventListener("DOMContentLoaded", function () {
    // SIDEBAR ACTIVE LINK TOGGLE
    const sidebarLinks = document.querySelectorAll('#sidebar .side-menu.top li a');
    sidebarLinks.forEach(link => {
        const li = link.parentElement;
        link.addEventListener('click', () => {
            sidebarLinks.forEach(l => l.parentElement.classList.remove('active'));
            li.classList.add('active');
        });
    });

    // TOGGLE SIDEBAR SHOW/HIDE
    const sidebarToggleButton = document.querySelector('#content nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');
    sidebarToggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hide');
    });



    // NAVBAR SEARCH TOGGLE (Mobile)
    const searchButton = document.querySelector('#content nav form .form-input button');
    const searchButtonIcon = searchButton.querySelector('.bx');
    const searchForm = document.querySelector('#content nav form');

    searchButton.addEventListener('click', function (e) {
        if (window.innerWidth < 576) {
            e.preventDefault();
            searchForm.classList.toggle('show');
            if (searchForm.classList.contains('show')) {
                searchButtonIcon.classList.replace('bx-search', 'bx-x');
            } else {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
            }
        }
    });



    // DARK MODE TOGGLE
    const switchMode = document.getElementById('switch-mode');



    // Load saved mode
    if (localStorage.getItem('mode') === 'dark') {
        switchMode.checked = true;
        document.body.classList.add('dark');
    }

    switchMode.addEventListener('change', function () {
        document.body.classList.toggle('dark', this.checked);
        localStorage.setItem('mode', this.checked ? 'dark' : 'light');
    });

});
