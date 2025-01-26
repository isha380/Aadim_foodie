
// Store references to our container and overlay
let notificationContainer = null;

// Function to create and set up the initial container 
function setupNotificationSystem() {
    // Check if container already exists
    if (notificationContainer) return;

    // Create the container that will hold our notifications
    notificationContainer = document.createElement('div');
    notificationContainer.id = 'notification-container';
    document.body.appendChild(notificationContainer);
}

// Function to get the appropriate icon based on notification type fromsession
function getNotificationIcon(type) {
    if (type === 'success') {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>';
    } else {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    }
}

// Function to create a new notification element
function createNotificationElement(message, type) {
    // Create the main notification div
    let notification = document.createElement('div');
    notification.className = 'notification-popup';
    
    // Get the icon for this notification type
    let icon = getNotificationIcon(type);
    
    // Set the HTML content foro showing the notification in toast
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

    // on hover listeners to pause/resume the auto-close timer
    notification.addEventListener('mouseenter', function() {
        let timeoutId = notification.getAttribute('data-timeout');
        if (timeoutId) {
            clearTimeout(parseInt(timeoutId));
        }
    });

    notification.addEventListener('mouseleave', function() {
        let timeoutId = setTimeout(function() {
            closeNotification(notification);
        }, 2000); // 2 second delay after mouse leave
        notification.setAttribute('data-timeout', timeoutId);
    });
    
    return notification;
}

// Function to close/remove a notification
function closeNotification(notification) {
    if (!notification) return;
    
    // Remove the show class to trigger hiding animation
    notification.classList.remove('show');
    notification.classList.add('hiding');
    
    // Clear the timeout that was set for auto-removal
    let timeoutId = notification.getAttribute('data-timeout');
    if (timeoutId) {
        clearTimeout(parseInt(timeoutId));
    }
    
    // Remove the notification after animation
    setTimeout(function() {
        notification.remove();
    }, 300);
}

// Main function to show a notification
function showAlert(message, type) { //handle success or failure with message
    // set up notification system (i.e. container)
    if (!notificationContainer) {
        setupNotificationSystem();
    }

    // Create the notification
    let notification = createNotificationElement(message, type);
    
    // Add it to the container
    notificationContainer.appendChild(notification);
    
    // Show the notification with animation
    setTimeout(function() {
        notification.classList.add('show');
    }, 10);
    
    // Set up auto-removal
    let timeoutId = setTimeout(function() {
        closeNotification(notification);
    }, 4000); // 4 seconds default display time
    
    // Store the timeout ID on the element
    notification.setAttribute('data-timeout', timeoutId);
}

// Initialize the system when the script loads
setupNotificationSystem();

// Also set up when DOM is fully loaded (as a backup)
document.addEventListener('DOMContentLoaded', function() {
    setupNotificationSystem();
});