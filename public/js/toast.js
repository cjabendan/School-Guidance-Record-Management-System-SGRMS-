// Toast details and logic
const notifications = document.querySelector(".notifications");
function createToast(type, text) {
    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };
    const toast = document.createElement("li");
    toast.className = `toast ${type}`;
    toast.innerHTML = `<div class=\"column\">
                         <i class=\"fa-solid ${icons[type]}\"></i>
                         <span>${text}</span>
                      </div>
                      <i class=\"fa-solid fa-xmark\" onclick=\"removeToast(this.parentElement)\"></i>`;
    notifications.appendChild(toast);
    toast.timeoutId = setTimeout(() => removeToast(toast), 5000);
}
function removeToast(toast) {
    toast.classList.add("hide");
    if(toast.timeoutId) clearTimeout(toast.timeoutId);
    setTimeout(() => toast.remove(), 500);
}
