import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
});

// ✅ Chat
window.Echo.private(`chat.${userId}`)
    .listen('MessageSent', (e) => {
        console.log('💬 MessageSent event:', e);
    });

// ✅ Notifications 
if (typeof window.userId !== 'undefined' && window.userRole !== 'admin') {

    // Personal (Private) notifications
    window.Echo.private(`user.${window.userId}`)
        .listen('.notification.created', (e) => {
            console.log("📩 New private notification:", e.notification);
            Livewire.emit('notificationReceived', e.notification);
        });

    // Global announcements (Public channel)
    window.Echo.channel('announcements')
        .listen('.notification.created', (e) => {
            console.log("📢 New announcement:", e.notification);
            Livewire.emit('notificationReceived', e.notification);
        });
}
