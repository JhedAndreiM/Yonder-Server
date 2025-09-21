<!-- Floating Chat Widget - Recent Chats Panel -->
<div class="chat-widget-container">
    <!-- Chat Widget Modal -->
    <div id="chatWidget" class="chat-widget">
        <!-- Header -->
        <div class="chat-widget-header">
            <h4>Messages</h4>
            <button id="chatWidgetClose" class="chat-widget-close">&times;</button>
        </div>
        
        <!-- Contacts Panel -->
        <div class="chat-contacts-panel">
            <div class="chat-contacts-header">
                <h5>Recent Chats</h5>
            </div>
            <div id="chatContactsList" class="chat-contacts-list">
                <div class="chat-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading contacts...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Widget Styles -->
<style>
/* Chat Widget Container */
.chat-widget-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

/* Chat Widget Modal */
.chat-widget {
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

.chat-widget.active {
    display: flex;
    transform: translateY(0);
    opacity: 1;
}

/* Header */
.chat-widget-header {
    background: linear-gradient(135deg, #0084ff 0%, #0066cc 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.chat-widget-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.chat-widget-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s ease;
}

.chat-widget-close:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Contacts Panel */
.chat-contacts-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
}

.chat-contacts-header {
    padding: 15px 20px;
    background: #e9ecef;
    border-bottom: 1px solid #e0e0e0;
    flex-shrink: 0;
}

.chat-contacts-header h5 {
    margin: 0;
    font-size: 14px;
    color: #666;
    font-weight: 600;
}

.chat-contacts-list {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

.chat-contact-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
    transition: background-color 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.chat-contact-item:hover {
    background-color: #e9ecef;
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
}

.contact-time {
    font-size: 11px;
    color: #999;
    margin-top: 2px;
}

/* Loading States */
.chat-loading {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    font-size: 14px;
}

.chat-loading i {
    margin-right: 8px;
    font-size: 18px;
}

.chat-loading span {
    display: block;
    margin-top: 8px;
}

/* No Contacts State */
.chat-no-contacts {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.chat-no-contacts i {
    font-size: 48px;
    color: #0084ff;
    margin-bottom: 15px;
    display: block;
}

.chat-no-contacts h5 {
    margin: 0 0 10px 0;
    font-size: 16px;
    color: #333;
}

.chat-no-contacts p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

/* Error State */
.chat-error {
    text-align: center;
    padding: 40px 20px;
    color: #e74c3c;
}

.chat-error i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
}

.chat-error h5 {
    margin: 0 0 10px 0;
    font-size: 16px;
}

.chat-error p {
    margin: 0;
    font-size: 14px;
}

/* Mobile Responsiveness */
@media (max-width: 600px) {
    .chat-widget-container {
        bottom: 15px;
        right: 15px;
    }
    
    .chat-widget {
        width: calc(100vw - 30px);
        height: calc(100vh - 100px);
        bottom: 70px;
        right: 0;
        left: 0;
        margin: 0 auto;
    }
    
    .chat-contact-item {
        padding: 12px 15px;
    }
    
    .contact-avatar {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }
    
    .contact-name {
        font-size: 14px;
    }
    
    .contact-last-message {
        font-size: 12px;
    }
}
</style>

<!-- Chat Widget JavaScript -->
<script>
class ChatWidget {
    constructor() {
        this.isOpen = false;
        this.init();
    }

    init() {
        this.toggleBtn = document.getElementById('chatWidgetToggle');
        this.closeBtn = document.getElementById('chatWidgetClose');
        this.widget = document.getElementById('chatWidget');
        this.contactsList = document.getElementById('chatContactsList');

        if (this.toggleBtn) {
            this.bindEvents();
        }
    }

    bindEvents() {
        this.toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggle();
        });
        this.closeBtn.addEventListener('click', () => this.close());
        
        // Close widget when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.widget.contains(e.target) && !this.toggleBtn.contains(e.target)) {
                this.close();
            }
        });
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        this.widget.classList.add('active');
        this.isOpen = true;
        this.loadContacts();
    }

    close() {
        this.widget.classList.remove('active');
        this.isOpen = false;
    }

    async loadContacts() {
        this.contactsList.innerHTML = `
            <div class="chat-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading contacts...</span>
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
                this.displayContacts(data.contacts);
            } else {
                this.showNoContacts();
            }
        } catch (error) {
            console.error('Error loading contacts:', error);
            this.showError('Failed to load contacts');
        }
    }

    displayContacts(contacts) {
        this.contactsList.innerHTML = '';
        
        contacts.forEach(contact => {
            const contactElement = document.createElement('a');
            contactElement.className = 'chat-contact-item';
            contactElement.href = `/chatify/${contact.id}`;
            
            // Format last message time
            const lastMessageTime = this.formatTime(contact.last_message_time);
            
            contactElement.innerHTML = `
                <div class="contact-avatar">
                    <img src="${contact.avatar ? '/storage/users-avatar/' + contact.avatar : '/img/profile-placeholder.svg'}" alt="${contact.name}" />
                </div>
                <div class="contact-info">
                    <div class="contact-name">${contact.name}</div>
                    <div class="contact-last-message">Click to start chatting</div>
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
        const diffInHours = (now - date) / (1000 * 60 * 60);
        
        if (diffInHours < 1) {
            return 'Just now';
        } else if (diffInHours < 24) {
            return Math.floor(diffInHours) + 'h ago';
        } else {
            return Math.floor(diffInHours / 24) + 'd ago';
        }
    }

    showNoContacts() {
        this.contactsList.innerHTML = `
            <div class="chat-no-contacts">
                <i class="fas fa-users"></i>
                <h5>No recent chats</h5>
                <p>Start a conversation!</p>
            </div>
        `;
    }

    showError(message) {
        this.contactsList.innerHTML = `
            <div class="chat-error">
                <i class="fas fa-exclamation-triangle"></i>
                <h5>Error</h5>
                <p>${message}</p>
            </div>
        `;
    }
}

// Initialize chat widget when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new ChatWidget();
});
</script>