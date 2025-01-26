// Store references to our container
let notificationContainer = null;

// Function to create and set up the initial container
function setupNotificationSystem() {
    // Check if the container already exists
    if (notificationContainer) return;

    // Create the container to hold notifications
    notificationContainer = document.createElement('div');
    notificationContainer.id = 'notification-container';
    document.body.appendChild(notificationContainer);
}

// Function to get the appropriate icon based on notification type
function getNotificationIcon(type) {
    switch(type) {
        case 'success':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>';
        case 'error':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        case 'warning':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        case 'info':
        default:
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    }
}

// Function to create a new notification element
function createNotificationElement(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification-popup ${type}`;

    const icon = getNotificationIcon(type);

    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon ${type}">${icon}</div>
            <div class="notification-message">${message}</div>
            <button class="notification-close" onclick="closeNotification(this.closest('.notification-popup'))">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    `;

    notification.addEventListener('mouseenter', () => {
        const timeoutId = notification.getAttribute('data-timeout');
        if (timeoutId) {
            clearTimeout(parseInt(timeoutId));
        }
    });

    notification.addEventListener('mouseleave', () => {
        const timeoutId = setTimeout(() => {
            closeNotification(notification);
        }, 2000);
        notification.setAttribute('data-timeout', timeoutId);
    });

    return notification;
}

// Function to close/remove a notification
function closeNotification(notification) {
    if (!notification) return;
    notification.classList.remove('show');
    notification.classList.add('hiding');

    const timeoutId = notification.getAttribute('data-timeout');
    if (timeoutId) {
        clearTimeout(parseInt(timeoutId));
    }

    setTimeout(() => {
        notification.remove();
    }, 300);
}

// Main function to show a notification
function showAlert(message, type = 'success') {
    if (!notificationContainer) {
        setupNotificationSystem();
    }

    const validTypes = ['success', 'error', 'warning', 'info']; //valid types accepted by the system
    type = validTypes.includes(type) ? type : 'info'; //if not valid type, directly throw to info type
    
    const notification = createNotificationElement(message, type);
    notificationContainer.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    const timeoutId = setTimeout(() => {
        closeNotification(notification);
    }, 4000);
    notification.setAttribute('data-timeout', timeoutId);
}

// Set up the notification system when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    setupNotificationSystem();
});
