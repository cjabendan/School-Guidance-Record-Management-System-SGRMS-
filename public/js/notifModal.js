document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notificationBell');
    const notificationModal = document.getElementById('notificationModal');
    const notifNum = document.querySelector('.notification .num');
    const closeBtn = notificationModal ? notificationModal.querySelector('button[onclick="closeNotifModal()"]') : null;

    if (notificationBell && notificationModal) {
        notificationBell.addEventListener('click', function (e) {
            e.preventDefault();
            notificationModal.style.display = 'block';

            // Mark notifications as read in DB and update badge
            fetch('/SGRMS/app/Controllers/Head/notify_read.php')
                .then(response => response.ok ? response.text() : Promise.reject())
                .then(() => {
                    if (notifNum) {
                        notifNum.textContent = '';
                        notifNum.style.display = 'none';
                    }
                });
        });
    }

    // Close modal when clicking the close button
    window.closeNotifModal = function() {
        if (notificationModal) notificationModal.style.display = 'none';
    };

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        if (notificationModal && event.target === notificationModal) {
            notificationModal.style.display = 'none';
        }
    };
});
