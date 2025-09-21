<!-- Recent Chats Panel Widget -->
<div class="chat-recent-container">
    <!-- Chat Widget Modal -->
    <div id="chatRecentWidget" class="chat-recent-widget">
        <!-- Header -->
        <div class="chat-recent-header">
            <h4>Messages</h4>
            <div class="chat-recent-controls">
                <button id="maximizeChatWidget" class="chat-recent-btn maximize-btn" title="Maximize">
                    <i class="fas fa-expand"></i>
                </button>
                <button id="closeChatWidget" class="chat-recent-btn close-btn" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Contacts List -->
        <div class="chat-recent-body">
            <div id="chatRecentContactsList" class="chat-recent-contacts-list">
                <div class="chat-recent-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading recent chats...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Chats Panel Styles -->
<style>
/* Chat Recent Container */
.chat-recent-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

/* Chat Recent Widget */
.chat-recent-widget {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 300px;
    height: 400px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: none;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.3s ease;
}

.chat-recent-widget.active {
    display: flex;
    transform: translateY(0);
    opacity: 1;
}

/* Header */
.chat-recent-header {
    background: linear-gradient(135deg, #0084ff 0%, #0066cc 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.chat-recent-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.chat-recent-controls {
    display: flex;
    gap: 8px;
}

.chat-recent-btn {
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    padding: 6px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s ease;
}

.chat-recent-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.maximize-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.close-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Body */
.chat-recent-body {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.chat-recent-contacts-list {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

/* Contact Items */
.chat-contact-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.chat-contact-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.chat-contact-item:last-child {
    border-bottom: none;
}

.contact-avatar {
    width: 45px;
    height: 45px;
    margin-right: 12px;
    flex-shrink: 0;
}

.contact-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
}

.contact-info {
    flex: 1;
    min-width: 0;
}

.contact-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 15px;
}

.contact-last-message {
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
    margin-bottom: 2px;
}

.contact-time {
    font-size: 11px;
    color: #999;
}

/* Loading State */
.chat-recent-loading {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    font-size: 14px;
}

.chat-recent-loading i {
    margin-right: 8px;
    font-size: 18px;
    display: block;
    margin-bottom: 10px;
}

.chat-recent-loading span {
    display: block;
}

/* No Contacts State */
.chat-recent-no-contacts {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.chat-recent-no-contacts i {
    font-size: 48px;
    color: #0084ff;
    margin-bottom: 15px;
    display: block;
}

.chat-recent-no-contacts h5 {
    margin: 0 0 10px 0;
    font-size: 16px;
    color: #333;
}

.chat-recent-no-contacts p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

/* Error State */
.chat-recent-error {
    text-align: center;
    padding: 40px 20px;
    color: #e74c3c;
}

.chat-recent-error i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
}

.chat-recent-error h5 {
    margin: 0 0 10px 0;
    font-size: 16px;
}

.chat-recent-error p {
    margin: 0;
    font-size: 14px;
}

/* Mobile Responsiveness */
@media (max-width: 600px) {
    .chat-recent-container {
        bottom: 15px;
        right: 15px;
    }
    
    /* Hide the widget completely on mobile */
    .chat-recent-widget {
        display: none !important;
    }
    
    /* Ensure the floating button is still visible and clickable */
    .chat-recent-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
}
</style>

<!-- Recent Chats Panel JavaScript -->
<script>
class ChatRecentWidget {
    constructor() {
        this.isOpen = false;
        this.contacts = [];
        this.timestampUpdateInterval = null;
        this.init();
    }

    init() {
        this.toggleBtn = document.getElementById('chatWidgetToggle');
        this.closeBtn = document.getElementById('closeChatWidget');
        this.maximizeBtn = document.getElementById('maximizeChatWidget');
        this.widget = document.getElementById('chatRecentWidget');
        this.contactsList = document.getElementById('chatRecentContactsList');

        if (this.toggleBtn) {
            this.bindEvents();
            this.setupEcho();
        }
    }

    bindEvents() {
        this.toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Check if screen width is 600px or less
            if (window.innerWidth <= 600) {
                // On mobile, redirect directly to Chatify
                window.location.href = '{{ route("Yonder/Chat") }}';
            } else {
                // On desktop, toggle the widget
                this.toggle();
            }
        });
        
        this.closeBtn.addEventListener('click', () => this.close());
        
        this.maximizeBtn.addEventListener('click', () => {
            window.location.href = '{{ route("Yonder/Chat") }}';
        });
        
        // Close widget when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.widget.contains(e.target) && !this.toggleBtn.contains(e.target)) {
                this.close();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 600 && this.isOpen) {
                this.close();
            }
        });
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        // Don't open on mobile screens (600px and below)
        if (window.innerWidth <= 600) {
            return;
        }
        
        this.widget.classList.add('active');
        this.isOpen = true;
        this.loadContacts();
        this.startTimestampUpdates();
    }

    close() {
        this.widget.classList.remove('active');
        this.isOpen = false;
        this.stopTimestampUpdates();
    }

    async loadContacts() {
        this.contactsList.innerHTML = `
            <div class="chat-recent-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading recent chats...</span>
            </div>
        `;

        try {
            const response = await fetch('/chat/contacts', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });
            
            const data = await response.json();
            if (data.contacts && data.contacts.length > 0) {
                this.contacts = data.contacts;
                this.displayContacts(data.contacts);
            } else {
                this.showNoContacts();
            }
        } catch (error) {
            console.error('Error loading contacts:', error);
            this.showError('Failed to load recent chats');
        }
    }

    displayContacts(contacts) {
        this.contactsList.innerHTML = '';
        
        contacts.forEach(contact => {
            const contactElement = document.createElement('a');
            contactElement.className = 'chat-contact-item';
            contactElement.href = `{{ route("Yonder/Chat") }}/${contact.id}`;
            contactElement.setAttribute('data-user-id', contact.id);
            
            // Format last message preview (truncate to 40 chars)
            const lastMessagePreview = contact.last_message_preview || 'No messages yet';
            const truncatedPreview = lastMessagePreview.length > 40 
                ? lastMessagePreview.substring(0, 40) + '...' 
                : lastMessagePreview;
            
            // Format last message time
            const lastMessageTime = this.formatTime(contact.last_message_time);
            
            contactElement.innerHTML = `
                <div class="contact-avatar">
                    <img src="${contact.avatar ? '/storage/users-avatar/' + contact.avatar : '/img/profile-placeholder.svg'}" alt="${contact.name}" />
                </div>
                <div class="contact-info">
                    <div class="contact-name">${contact.name}</div>
                    <div class="contact-last-message">${truncatedPreview}</div>
                    <div class="contact-time">${lastMessageTime}</div>
                </div>
            `;
            
            this.contactsList.appendChild(contactElement);
        });
    }

    formatTime(timestamp) {
        if (!timestamp) return '';
        
        const date = new Date(timestamp);
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
        const diffInHours = Math.floor(diffInMinutes / 60);
        const diffInDays = Math.floor(diffInHours / 24);
        
        // Less than 1 minute
        if (diffInMinutes < 1) {
            return 'Just now';
        }
        // First hour: show minutes
        else if (diffInMinutes < 60) {
            return diffInMinutes === 1 ? '1 min ago' : `${diffInMinutes} mins ago`;
        }
        // First day: show hours
        else if (diffInHours < 24) {
            return diffInHours === 1 ? '1 hour ago' : `${diffInHours} hours ago`;
        }
        // Yesterday
        else if (diffInDays === 1) {
            return 'Yesterday';
        }
        // Within last 7 days: show weekday
        else if (diffInDays < 7) {
            const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return weekdays[date.getDay()];
        }
        // Older than a week: show Month + Day
        else {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${months[date.getMonth()]} ${date.getDate()}`;
        }
    }

    showNoContacts() {
        this.contactsList.innerHTML = `
            <div class="chat-recent-no-contacts">
                <i class="fas fa-users"></i>
                <h5>No recent chats</h5>
                <p>Start a conversation!</p>
            </div>
        `;
    }

    showError(message) {
        this.contactsList.innerHTML = `
            <div class="chat-recent-error">
                <i class="fas fa-exclamation-triangle"></i>
                <h5>Error</h5>
                <p>${message}</p>
            </div>
        `;
    }

    setupEcho() {
        // Wait for Echo to be available
        const checkEcho = () => {
            if (window.Echo) {
                // Listen for new messages to update previews
                window.Echo.private(`chat.${window.userId}`)
                    .listen('NewMessage', (e) => {
                        this.handleNewMessage(e);
                    });
            } else {
                setTimeout(checkEcho, 100);
            }
        };
        checkEcho();
    }

    handleNewMessage(e) {
        // Find the contact in our list and update their last message
        const contactElement = document.querySelector(`[data-user-id="${e.from_id}"]`);
        if (contactElement) {
            const lastMessageDiv = contactElement.querySelector('.contact-last-message');
            const timeDiv = contactElement.querySelector('.contact-time');
            
            if (lastMessageDiv) {
                const preview = e.message.length > 40 ? e.message.substring(0, 40) + '...' : e.message;
                lastMessageDiv.textContent = preview;
            }
            
            if (timeDiv) {
                // Use the proper timestamp formatting for new messages
                timeDiv.textContent = this.formatTime(new Date().toISOString());
            }
            
            // Move this contact to the top of the list
            this.moveContactToTop(contactElement);
        }
    }

    moveContactToTop(contactElement) {
        const contactsList = this.contactsList;
        const firstContact = contactsList.firstChild;
        
        if (firstContact && firstContact !== contactElement) {
            contactsList.insertBefore(contactElement, firstContact);
        }
    }

    startTimestampUpdates() {
        // Update timestamps every minute
        this.timestampUpdateInterval = setInterval(() => {
            this.updateAllTimestamps();
        }, 60000); // 60 seconds
    }

    stopTimestampUpdates() {
        if (this.timestampUpdateInterval) {
            clearInterval(this.timestampUpdateInterval);
            this.timestampUpdateInterval = null;
        }
    }

    updateAllTimestamps() {
        const contactElements = document.querySelectorAll('.chat-contact-item');
        contactElements.forEach(contactElement => {
            const timeDiv = contactElement.querySelector('.contact-time');
            if (timeDiv) {
                // Get the original timestamp from the contact data
                const userId = contactElement.getAttribute('data-user-id');
                const contact = this.contacts.find(c => c.id == userId);
                if (contact && contact.last_message_time) {
                    timeDiv.textContent = this.formatTime(contact.last_message_time);
                }
            }
        });
    }
}

// Initialize chat recent widget when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new ChatRecentWidget();
});
</script>
