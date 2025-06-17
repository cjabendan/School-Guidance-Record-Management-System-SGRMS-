document.addEventListener('DOMContentLoaded', function () {
    const notificationBell = document.getElementById('notificationBell');
    const notificationModal = document.getElementById('notificationModal');
    const notifNum = document.querySelector('.notification .num');
    const closeBtn = document.getElementById('closeNotifModalBtn');

    // Detail modal elements
    const notifDetailModal = document.getElementById('notifDetailModal');
    const notifDetailContent = document.getElementById('notifDetailContent');
    const closeNotifDetailBtn = document.getElementById('closeNotifDetailBtn');

    if (notificationBell && notificationModal) {
        notificationBell.addEventListener('click', function (e) {
            e.preventDefault();
            notificationModal.style.display = 'block';

            // Mark notifications as read in DB and update badge
            fetch('/SGRMS/app/Controllers/Head/NotifyController/notify_read.php')
                .then(response => response.ok ? response.text() : Promise.reject())
                .then(() => {
                    if (notifNum) {
                        notifNum.textContent = '';
                        notifNum.style.display = 'none';
                    }
                });
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            notificationModal.style.display = 'none';
        });
    }

    // Show notification details when a notif-item is clicked
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', function () {
            const notifId = this.getAttribute('data-id');
            window.location.href = 'notif.php?id=' + encodeURIComponent(notifId);
        });
    });

    // Optional: close modal button logic
    document.getElementById('closeNotifModalBtn').onclick = function () {
        document.getElementById('notificationModal').style.display = 'none';
    };

    // Close modals when clicking outside the modal content
    window.addEventListener('click', function (event) {
        if (event.target === notificationModal) {
            notificationModal.style.display = 'none';
        } else if (event.target === notifDetailModal) {
            notifDetailModal.style.display = 'none';
        }
    });
});
