@extends('Front_layouts.app')

@section('title', 'Trade Offers')
@section('head')
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    @vite('resources/css/tradeOffers.css')
@endsection

@section('content')
<div class="trade-offers-container">
    <div class="header">
        <h1>Trade Offers</h1>
    </div>

    <div class="tabs">
        <button class="tab-btn {{ $activeTab === 'received' ? 'active' : '' }}" data-tab="received">
            Received ({{ $receivedOffers->where('status', 'pending')->count() }})
        </button>
        <button class="tab-btn {{ $activeTab === 'sent' ? 'active' : '' }}" data-tab="sent">
            Sent ({{ $sentOffers->where('status', 'pending')->count() }})
        </button>
    </div>

    <!-- Received Trade Offers Tab -->
    <div class="tab-content {{ $activeTab === 'received' ? 'active' : '' }}" id="received-tab">
        @if($receivedOffers->isEmpty())
            <div class="empty-state">
                <img src="{{ asset('img/empty-box.svg') }}" alt="No offers" />
                <p>You haven't received any trade offers yet.</p>
            </div>
        @else
            @foreach($receivedOffers as $offer)
                <div class="trade-offer-card">
                    <div class="offer-header">
                        <div class="user-info">
                            <img src="{{ asset('storage/users-avatar/' . $offer->sender->avatar) }}" alt="User" class="avatar" />
                            <div>
                                <h3>
                                    {{ $offer->sender->name }} {{ $offer->sender->last_name }}
                                    @if($offer->parent_offer_id)
                                        <span class="counter-badge">Counter Offer</span>
                                    @endif
                                </h3>
                                <span class="offer-date">{{ $offer->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="offer-status status-{{ $offer->status }}">
                            {{ ucfirst($offer->status) }}
                        </div>
                    </div>

                    @if($offer->message)
                        <div class="offer-message">
                            <p>{{ $offer->message }}</p>
                        </div>
                    @endif

                    <div class="offer-items">
                        <div class="items-section">
                            <h4>They're offering:</h4>
                            <div class="items-grid">
                                @foreach($offer->senderItems as $item)
                                    <div class="item-card">
                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->product->name }}" />
                                        <div class="item-details">
                                            <p class="item-name">{{ $item->product->name }}</p>
                                            @if($item->variant_name)
                                                <p class="item-variant">{{ $item->variant_name }}</p>
                                            @endif
                                            <p class="item-qty">Qty: {{ $item->quantity }}</p>
                                            <p class="item-price">₱{{ number_format($item->price_at_time, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="items-divider">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div class="items-section">
                            <h4>For your items:</h4>
                            <div class="items-grid">
                                @foreach($offer->recipientItems as $item)
                                    <div class="item-card">
                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->product->name }}" />
                                        <div class="item-details">
                                            <p class="item-name">{{ $item->product->name }}</p>
                                            @if($item->variant_name)
                                                <p class="item-variant">{{ $item->variant_name }}</p>
                                            @endif
                                            <p class="item-qty">Qty: {{ $item->quantity }}</p>
                                            <p class="item-price">₱{{ number_format($item->price_at_time, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($offer->status === 'declined' && $offer->decline_reason)
                        <div class="offer-reason">
                            <strong>Reason for declining:</strong>
                            <p>{{ $offer->decline_reason }}</p>
                        </div>
                    @endif

                    @if($offer->status === 'pending')
                        <div class="offer-actions">
                            <button class="btn btn-accept" data-offer-id="{{ $offer->id }}">Accept</button>
                            <button class="btn btn-decline" data-offer-id="{{ $offer->id }}">Decline</button>
                            <button class="btn btn-counter" data-offer-id="{{ $offer->id }}">Counter Offer</button>
                        </div>
                    @elseif($offer->status === 'accepted')
                        <div class="offer-confirmation">
                            <p class="confirmation-status">
                                @if($offer->recipient_confirmed)
                                    <span class="confirmed">✓ You received the items</span>
                                @else
                                    <span class="pending-confirm">Waiting for you to receive the items</span>
                                @endif
                                
                                @if($offer->sender_confirmed)
                                    <span class="confirmed">✓ {{ $offer->sender->name }} received the items</span>
                                @else
                                    <span class="pending-confirm">Waiting for {{ $offer->sender->name }} to receive items</span>
                                @endif
                            </p>
                            
                            @if(!$offer->recipient_confirmed)
                                <div class="offer-actions">
                                    <button class="btn btn-confirm" data-offer-id="{{ $offer->id }}">Confirm Received</button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Sent Trade Offers Tab -->
    <div class="tab-content {{ $activeTab === 'sent' ? 'active' : '' }}" id="sent-tab">
        @if($sentOffers->isEmpty())
            <div class="empty-state">
                <img src="{{ asset('img/empty-box.svg') }}" alt="No offers" />
                <p>You haven't sent any trade offers yet.</p>
            </div>
        @else
            @foreach($sentOffers as $offer)
                <div class="trade-offer-card">
                    <div class="offer-header">
                        <div class="user-info">
                            <img src="{{ asset('storage/users-avatar/' . $offer->recipient->avatar) }}" alt="User" class="avatar" />
                            <div>
                                <h3>
                                    {{ $offer->recipient->name }} {{ $offer->recipient->last_name }}
                                    @if($offer->parent_offer_id)
                                        <span class="counter-badge">Counter Offer</span>
                                    @endif
                                </h3>
                                <span class="offer-date">{{ $offer->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="offer-status status-{{ $offer->status }}">
                            {{ ucfirst($offer->status) }}
                        </div>
                    </div>

                    @if($offer->message)
                        <div class="offer-message">
                            <p>{{ $offer->message }}</p>
                        </div>
                    @endif

                    <div class="offer-items">
                        <div class="items-section">
                            <h4>You're offering:</h4>
                            <div class="items-grid">
                                @foreach($offer->senderItems as $item)
                                    <div class="item-card">
                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->product->name }}" />
                                        <div class="item-details">
                                            <p class="item-name">{{ $item->product->name }}</p>
                                            @if($item->variant_name)
                                                <p class="item-variant">{{ $item->variant_name }}</p>
                                            @endif
                                            <p class="item-qty">Qty: {{ $item->quantity }}</p>
                                            <p class="item-price">₱{{ number_format($item->price_at_time, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="items-divider">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div class="items-section">
                            <h4>For their items:</h4>
                            <div class="items-grid">
                                @foreach($offer->recipientItems as $item)
                                    <div class="item-card">
                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->product->name }}" />
                                        <div class="item-details">
                                            <p class="item-name">{{ $item->product->name }}</p>
                                            @if($item->variant_name)
                                                <p class="item-variant">{{ $item->variant_name }}</p>
                                            @endif
                                            <p class="item-qty">Qty: {{ $item->quantity }}</p>
                                            <p class="item-price">₱{{ number_format($item->price_at_time, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($offer->status === 'cancelled' && $offer->cancellation_reason)
                        <div class="offer-reason">
                            <strong>Reason for cancellation:</strong>
                            <p>{{ $offer->cancellation_reason }}</p>
                        </div>
                    @endif

                    @if($offer->status === 'pending')
                        <div class="offer-actions">
                            <button class="btn btn-cancel" data-offer-id="{{ $offer->id }}">Cancel Offer</button>
                        </div>
                    @elseif($offer->status === 'accepted')
                        <div class="offer-confirmation">
                            <p class="confirmation-status">
                                @if($offer->sender_confirmed)
                                    <span class="confirmed">✓ You received the items</span>
                                @else
                                    <span class="pending-confirm">Waiting for you to receive the items</span>
                                @endif
                                
                                @if($offer->recipient_confirmed)
                                    <span class="confirmed">✓ {{ $offer->recipient->name }} received the items</span>
                                @else
                                    <span class="pending-confirm">Waiting for {{ $offer->recipient->name }} to receive items</span>
                                @endif
                            </p>
                            
                            @if(!$offer->sender_confirmed)
                                <div class="offer-actions">
                                    <button class="btn btn-confirm" data-offer-id="{{ $offer->id }}">Confirm Received</button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="{{ asset('imgModal/cancelLogo.svg') }}" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage">Confirm Action</h3>
    <p id="uniqueConfirmMessage">Are you sure you want to proceed?</p>
    
    <!-- Reason section (shown conditionally) -->
    <div id="reasonSection" style="display:none; padding: 0 20px 20px;">
        <label for="reasonSelect" style="display:block; text-align:left; margin-bottom:8px; font-weight:500; color:#333;">
            Select Reason <span style="color:#ae0505;">*</span>
        </label>
        <select 
            id="reasonSelect" 
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; font-family:inherit; font-size:14px; margin-bottom:12px;"
        >
            <option value="">Choose a reason...</option>
            <option value="Not what I'm looking for">Not what I'm looking for</option>
            <option value="Price too high">Price too high</option>
            <option value="Found a better deal">Found a better deal</option>
            <option value="Changed my mind">Changed my mind</option>
            <option value="other">Other (please specify)</option>
        </select>
        
        <div id="customReasonSection" style="display:none;">
            <label for="actionReason" style="display:block; text-align:left; margin-bottom:8px; font-weight:500; color:#333;">
                Please specify <span style="color:#ae0505;">*</span>
            </label>
            <textarea 
                id="actionReason" 
                rows="3" 
                placeholder="Please provide a reason..."
                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; font-family:inherit; font-size:14px; resize:vertical;"
            ></textarea>
        </div>
        
        <p id="reasonError" style="display:none; color:#ae0505; font-size:12px; margin-top:5px;">Please select or provide a reason</p>
    </div>
    
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Confirm</button>
    </div>
  </div>
</div>

<script>
    // Modal elements
    const modal = document.getElementById("uniqueConfirmModal");
    const modalHeader = document.getElementById("uniqueModalHeader");
    const confirmYes = document.getElementById("uniqueConfirmYes");
    const confirmNo = document.getElementById("uniqueConfirmNo");
    const modalIcon = document.getElementById("uniqueModalIcon");
    const modalTitle = document.getElementById("uniqueHeaderMessage");
    const modalMessage = document.getElementById("uniqueConfirmMessage");
    const imageWrapper = document.getElementById("imageWrapper");
    const reasonSection = document.getElementById("reasonSection");
    const reasonSelect = document.getElementById("reasonSelect");
    const customReasonSection = document.getElementById("customReasonSection");
    const actionReason = document.getElementById("actionReason");
    const reasonError = document.getElementById("reasonError");

    let pendingAction = null; // Store the action to execute on confirm
    let requiresReason = false; // Flag to check if reason is required

    // Handle reason dropdown change
    reasonSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            customReasonSection.style.display = 'block';
            actionReason.value = '';
        } else {
            customReasonSection.style.display = 'none';
            actionReason.value = '';
        }
        reasonError.style.display = 'none';
    });

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            
            // Update active states
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(tab + '-tab').classList.add('active');
        });
    });

    // Helper function to show modal
    function showModal(config) {
        if (modalIcon) modalIcon.src = config.icon;
        if (modalTitle) modalTitle.textContent = config.title;
        if (modalMessage) modalMessage.textContent = config.message;
        if (confirmYes) confirmYes.textContent = config.confirmText;
        
        // Apply theme colors
        if (modalHeader) modalHeader.style.backgroundColor = config.headerColor;
        if (imageWrapper) imageWrapper.style.boxShadow = config.shadowColor;
        if (modalTitle) modalTitle.style.color = config.titleColor;
        if (confirmYes) {
            confirmYes.style.backgroundColor = config.buttonColor;
            confirmYes.style.color = config.buttonTextColor;
            confirmYes.style.border = config.buttonBorder || 'none';
        }
        
        // Handle reason section
        requiresReason = config.requiresReason || false;
        if (requiresReason) {
            reasonSection.style.display = 'block';
            reasonSelect.value = '';
            customReasonSection.style.display = 'none';
            actionReason.value = '';
            reasonError.style.display = 'none';
        } else {
            reasonSection.style.display = 'none';
        }
        
        pendingAction = config.onConfirm;
        modal.style.display = "flex";
    }

    // Modal cancel button
    confirmNo.addEventListener("click", function () {
        modal.style.display = "none";
        pendingAction = null;
        requiresReason = false;
    });

    // Modal confirm button
    confirmYes.addEventListener("click", function () {
        // Validate reason if required
        if (requiresReason) {
            const selectedReason = reasonSelect.value;
            
            // Check if a reason is selected
            if (!selectedReason) {
                reasonError.textContent = 'Please select a reason';
                reasonError.style.display = 'block';
                return; // Don't close modal
            }
            
            // If "other" is selected, check if custom reason is provided
            if (selectedReason === 'other') {
                const customReason = actionReason.value.trim();
                if (!customReason) {
                    reasonError.textContent = 'Please provide a reason';
                    reasonError.style.display = 'block';
                    return; // Don't close modal
                }
            }
        }
        
        modal.style.display = "none";
        if (pendingAction) {
            let finalReason = null;
            if (requiresReason) {
                const selectedReason = reasonSelect.value;
                finalReason = selectedReason === 'other' ? actionReason.value.trim() : selectedReason;
            }
            pendingAction(finalReason);
            pendingAction = null;
            requiresReason = false;
        }
    });

    // Cancel Offer functionality
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const offerId = this.dataset.offerId;
            const button = this;
            
            showModal({
                icon: '{{ asset("imgModal/cancelLogo.svg") }}',
                title: 'Cancel Trade Offer?',
                message: 'Please provide a reason for cancelling this trade offer.',
                confirmText: 'Cancel Offer',
                headerColor: '#ae0505',
                shadowColor: '0 1px 0 rgba(165, 0, 0, 0.6)',
                titleColor: '#ae0505',
                buttonColor: '',
                buttonTextColor: '',
                buttonBorder: '',
                requiresReason: true,
                onConfirm: (reason) => cancelOffer(offerId, button, reason)
            });
        });
    });

    function cancelOffer(offerId, button, reason) {
        // Disable button to prevent double clicks
        button.disabled = true;
        button.textContent = 'Cancelling...';

        fetch(`/trade/cancel/${offerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the offer card to show cancelled status
                const offerCard = button.closest('.trade-offer-card');
                const statusBadge = offerCard.querySelector('.offer-status');
                statusBadge.textContent = 'Cancelled';
                statusBadge.className = 'offer-status status-cancelled';
                
                // Remove the actions section
                const actionsSection = offerCard.querySelector('.offer-actions');
                if (actionsSection) {
                    actionsSection.remove();
                }
                
                // Show success message
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message || 'Failed to cancel offer');
                button.disabled = false;
                button.textContent = 'Cancel Offer';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while cancelling the offer');
            button.disabled = false;
            button.textContent = 'Cancel Offer';
        });
    }

    function showMessage(type, message) {
        // Create message bar
        const messageBar = document.createElement('div');
        messageBar.className = type === 'success' ? 'success-bar' : 'error-bar';
        messageBar.id = type === 'success' ? 'successBar' : 'errorBar';
        const iconSrc = type === 'success' 
            ? '{{ asset("imgModal/barCheckLogo.svg") }}' 
            : '{{ asset("imgModal/barCrossLogo.svg") }}';
        messageBar.innerHTML = `
            ${message} <img src="${iconSrc}" alt="${type}" class="${type}-icon">
        `;
        
        document.body.appendChild(messageBar);
        messageBar.classList.add('show');
        
        // Hide after 5 seconds (matching profile.blade.php timing)
        setTimeout(() => {
            messageBar.classList.remove('show');
            setTimeout(() => messageBar.remove(), 400);
        }, 5000);
    }

    // Accept Offer functionality
    document.querySelectorAll('.btn-accept').forEach(btn => {
        btn.addEventListener('click', function() {
            const offerId = this.dataset.offerId;
            const button = this;
            
            showModal({
                icon: '{{ asset("imgModal/confirmationLogo.svg") }}',
                title: 'Accept Trade Offer?',
                message: 'Are you sure you want to accept this trade? Stock will be deducted and both parties will need to confirm receipt.',
                confirmText: 'Accept',
                headerColor: '#5196F0',
                shadowColor: '0 1px 0 rgba(81, 150, 240, 0.6)',
                titleColor: '#5196F0',
                buttonColor: '#5196F0',
                buttonTextColor: '#ffffff',
                buttonBorder: 'none',
                onConfirm: () => acceptOffer(offerId, button)
            });
        });
    });

    function acceptOffer(offerId, button) {
        // Disable button to prevent double clicks
        button.disabled = true;
        button.textContent = 'Accepting...';

        fetch(`/trade/accept/${offerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message);
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showMessage('error', data.message || 'Failed to accept offer');
                button.disabled = false;
                button.textContent = 'Accept';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while accepting the offer');
            button.disabled = false;
            button.textContent = 'Accept';
        });
    }

    // Decline Offer functionality
    document.querySelectorAll('.btn-decline').forEach(btn => {
        btn.addEventListener('click', function() {
            const offerId = this.dataset.offerId;
            const button = this;
            
            showModal({
                icon: '{{ asset("imgModal/cancelLogo.svg") }}',
                title: 'Decline Trade Offer?',
                message: 'Please provide a reason for declining this trade offer.',
                confirmText: 'Decline',
                headerColor: '#ae0505',
                shadowColor: '0 1px 0 rgba(165, 0, 0, 0.6)',
                titleColor: '#ae0505',
                buttonColor: '',
                buttonTextColor: '',
                buttonBorder: '',
                requiresReason: true,
                onConfirm: (reason) => declineOffer(offerId, button, reason)
            });
        });
    });

    function declineOffer(offerId, button, reason) {
        // Disable button to prevent double clicks
        button.disabled = true;
        button.textContent = 'Declining...';

        fetch(`/trade/decline/${offerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the offer card to show declined status
                const offerCard = button.closest('.trade-offer-card');
                const statusBadge = offerCard.querySelector('.offer-status');
                statusBadge.textContent = 'Declined';
                statusBadge.className = 'offer-status status-declined';
                
                // Remove the actions section
                const actionsSection = offerCard.querySelector('.offer-actions');
                if (actionsSection) {
                    actionsSection.remove();
                }
                
                // Show success message
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message || 'Failed to decline offer');
                button.disabled = false;
                button.textContent = 'Decline';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while declining the offer');
            button.disabled = false;
            button.textContent = 'Decline';
        });
    }

    // Counter Offer functionality
    document.querySelectorAll('.btn-counter').forEach(btn => {
        btn.addEventListener('click', function() {
            const offerId = this.dataset.offerId;
            // Redirect to counter offer page
            window.location.href = `/trade/counter/${offerId}`;
        });
    });

    // Confirm Receipt functionality
    document.querySelectorAll('.btn-confirm').forEach(btn => {
        btn.addEventListener('click', function() {
            const offerId = this.dataset.offerId;
            const button = this;
            
            showModal({
                icon: '{{ asset("imgModal/confirmationLogo.svg") }}',
                title: 'Confirm Received?',
                message: 'Have you received the items from this trade? Once both parties confirm, the trade will be marked as completed.',
                confirmText: 'Confirm Received',
                headerColor: '#5196F0',
                shadowColor: '0 1px 0 rgba(81, 150, 240, 0.6)',
                titleColor: '#5196F0',
                buttonColor: '#5196F0',
                buttonTextColor: '#ffffff',
                buttonBorder: 'none',
                onConfirm: () => confirmReceipt(offerId, button)
            });
        });
    });

    function confirmReceipt(offerId, button) {
        // Disable button to prevent double clicks
        button.disabled = true;
        button.textContent = 'Confirming...';

        fetch(`/trade/confirm/${offerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.message);
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showMessage('error', data.message || 'Failed to confirm receipt');
                button.disabled = false;
                button.textContent = 'Confirm Receipt';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'An error occurred while confirming receipt');
            button.disabled = false;
            button.textContent = 'Confirm Receipt';
        });
    }
</script>
@endsection
