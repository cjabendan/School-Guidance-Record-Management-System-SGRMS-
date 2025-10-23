// Toast details and logic
function getNotificationsContainer() {
    let container = document.querySelector('.notifications');
    if (!container) {
        container = document.createElement('ul');
        container.className = 'notifications';
        container.style.position = 'fixed';
        container.style.top = '1rem';
        container.style.right = '1rem';
        container.style.zIndex = 9999;
        container.style.listStyle = 'none';
        container.style.margin = '0';
        container.style.padding = '0';
        document.body.appendChild(container);
    }
    return container;
}

function createToast(type, text, options = {}) {
    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };

    const container = getNotificationsContainer();
    const toast = document.createElement('li');
    toast.className = `toast ${type}`;
    const iconClass = icons[type] || icons.info;
    toast.innerHTML = `<div class="column">
                         <i class="fa-solid ${iconClass}"></i>
                         <span>${text}</span>
                      </div>
                      <i class="fa-solid fa-xmark" onclick="removeToast(this.parentElement)"></i>`;

    container.appendChild(toast);

    const duration = typeof options.duration === 'number' ? options.duration : 5000;
    try { toast.style.setProperty('--toast-duration', duration + 'ms'); } catch(e) {}
    if (duration === 0) {
        toast.classList.add('no-progress');
    } else {
        toast.timeoutId = setTimeout(() => removeToast(toast), duration);
    }

    return toast;
}

function removeToast(toast) {
    if (!toast) return;
    toast.classList.add('hide');
    if (toast.timeoutId) clearTimeout(toast.timeoutId);
    setTimeout(() => toast.remove(), 500);
}
