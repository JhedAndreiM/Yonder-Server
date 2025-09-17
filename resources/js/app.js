import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Pusher and Echo only once when the script loads
function initializePusher() {
    if (window.Echo) {
        return;
    }

    window.Pusher = Pusher;
    Pusher.logToConsole = false;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'ebd4e7af97e041817d46',
        cluster: 'ap1',
        forceTLS: true,
        authEndpoint: '/dashboard/Thesis/public/broadcasting/auth',
        csrf_token: document.querySelector('meta[name="csrf-token"]')?.content
    });

    // Debug connection
    window.Echo.connector.pusher.connection.bind('connected', () => {
        // Set up notifications immediately after connection
        setupNotifications();
    });

    window.Echo.connector.pusher.connection.bind('error', (err) => {
        console.error("❌ Pusher error:", err);
    });
}
function setupNotifications() {
    if (!window.userId) {
        return;
    }

    const channel = window.Echo.private(`notifications.${window.userId}`);
    
    // Listen for notifications (both variants)
    channel.listen('new-notification', (data) => {
        addNotificationToList(data);
    });
    channel.listen('.new-notification', (data) => {
        addNotificationToList(data);
    });
}

function addNotificationToList(notificationData) {
    const notificationList = document.querySelector('.notification-list');
    if (!notificationList) {
        return;
    }

    // Clear "No notifications" message if present
    const emptyMessage = notificationList.querySelector('p');
    if (emptyMessage?.textContent.trim() === 'No notifications') {
        notificationList.innerHTML = '';
    }

    // Title coloring logic
    const titleHtml =
        notificationData.title === "Product Approved"
            ? `<span style="color:Green;">${notificationData.title}</span>`
            : notificationData.title === "Product Rejected"
            ? `<span style="color:red;">${notificationData.title}</span>`
            : notificationData.title;

    // Create proper HTML structure (matches Blade + CSS)
    const notificationHtml = `
        <div class="notification unread">
            <div class="notification-content">
                <h1>${titleHtml}</h1>
                <div class="Message">${notificationData.message}</div>
            </div>
            <div class="notification-time">${notificationData.time_ago ?? 'just now'}</div>
        </div>
    `;

    // Insert at the beginning of the list
    notificationList.insertAdjacentHTML('afterbegin', notificationHtml);
    
    const notifIcon = document.getElementById('notif-icon');
    let badge = notifIcon.querySelector('.notif-badge');

    if (typeof notificationData.unread_count !== "undefined") {
        // If backend sends unread_count, trust it
        if (badge) {
            badge.innerText = notificationData.unread_count;
        } else {
            const newBadge = document.createElement('span');
            newBadge.className = 'notif-badge';
            newBadge.innerText = notificationData.unread_count;
            notifIcon.appendChild(newBadge);
        }
    } else {
        // Otherwise just increment the current badge
        if (badge) {
            let count = parseInt(badge.innerText, 10) || 0;
            badge.innerText = count + 1;
        } else {
            const newBadge = document.createElement('span');
            newBadge.className = 'notif-badge';
            newBadge.innerText = 1;
            notifIcon.appendChild(newBadge);
        }
    }
}


// Initialize everything when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    initializePusher();
});
