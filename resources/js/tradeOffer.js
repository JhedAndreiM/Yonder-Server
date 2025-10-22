/**
 * Steam-like Trade Offer Interface
 * Handles item selection, deselection, and UI updates
 */

document.addEventListener('DOMContentLoaded', function() {
    // State management
    const state = {
        mySelectedItems: new Set(),
        theirSelectedItems: new Set(),
        myItemsData: {},
        theirItemsData: {},
        mySelectedItemsDetails: {}, // Store {selectionKey: {price, quantity}}
        theirSelectedItemsDetails: {} // Store {selectionKey: {price, quantity}}
    };

    // DOM Elements
    const myItemsGrid = document.getElementById('myItemsGrid');
    const theirItemsGrid = document.getElementById('theirItemsGrid');
    const mySelectedArea = document.getElementById('mySelectedArea');
    const theirSelectedArea = document.getElementById('theirSelectedArea');
    const myItemCount = document.getElementById('myItemCount');
    const theirItemCount = document.getElementById('theirItemCount');
    const myTotalPrice = document.getElementById('myTotalPrice');
    const theirTotalPrice = document.getElementById('theirTotalPrice');
    const btnSendOffer = document.getElementById('btnSendOffer');
    const searchMyItems = document.getElementById('searchMyItems');
    const searchTheirItems = document.getElementById('searchTheirItems');

    /**
     * Initialize item data from DOM
     */
    function initializeItemData() {
        // Store my items data
        const myCards = myItemsGrid.querySelectorAll('.item-card');
        myCards.forEach(card => {
            const itemId = card.dataset.itemId;
            let parsedVariants = null;
            let hasVariants = false;
            
            // Safely parse variants JSON
            if (card.dataset.hasVariants === 'true' && card.dataset.variants && card.dataset.variants.trim() !== '') {
                try {
                    parsedVariants = JSON.parse(card.dataset.variants);
                    // Verify it's actually valid variant data
                    if (parsedVariants && parsedVariants.options && Array.isArray(parsedVariants.options) && parsedVariants.options.length > 0) {
                        // Convert optionStocks strings to numbers if needed
                        if (parsedVariants.optionStocks && Array.isArray(parsedVariants.optionStocks)) {
                            parsedVariants.optionStocks = parsedVariants.optionStocks.map(stock => parseInt(stock) || 0);
                        }
                        hasVariants = true;
                    } else {
                        parsedVariants = null;
                    }
                } catch (e) {
                    // Silently treat as no variants - this is expected for products with empty variant strings
                    parsedVariants = null;
                }
            }
            
            state.myItemsData[itemId] = {
                id: itemId,
                name: card.dataset.itemName,
                image: card.dataset.itemImage,
                condition: card.dataset.itemCondition,
                price: parseFloat(card.dataset.itemPrice) || 0,
                stock: parseInt(card.dataset.itemStock) || 0,
                hasVariants: hasVariants,
                variants: parsedVariants
            };
        });

        // Store their items data
        const theirCards = theirItemsGrid.querySelectorAll('.item-card');
        theirCards.forEach(card => {
            const itemId = card.dataset.itemId;
            let parsedVariants = null;
            let hasVariants = false;
            
            console.log('Processing card:', itemId, {
                hasVariantsAttr: card.dataset.hasVariants,
                variantsRaw: card.dataset.variants
            });
            
            // Safely parse variants JSON
            if (card.dataset.hasVariants === 'true' && card.dataset.variants && card.dataset.variants.trim() !== '') {
                try {
                    parsedVariants = JSON.parse(card.dataset.variants);
                    console.log('Parsed variants for', itemId, ':', parsedVariants);
                    // Verify it's actually valid variant data
                    if (parsedVariants && parsedVariants.options && Array.isArray(parsedVariants.options) && parsedVariants.options.length > 0) {
                        // Convert optionStocks strings to numbers if needed
                        if (parsedVariants.optionStocks && Array.isArray(parsedVariants.optionStocks)) {
                            parsedVariants.optionStocks = parsedVariants.optionStocks.map(stock => parseInt(stock) || 0);
                        }
                        hasVariants = true;
                        console.log('Item has variants:', itemId, hasVariants);
                    } else {
                        parsedVariants = null;
                    }
                } catch (e) {
                    console.error('Failed to parse variants for', itemId, ':', e);
                    // Silently treat as no variants - this is expected for products with empty variant strings
                    parsedVariants = null;
                }
            }
            
            state.theirItemsData[itemId] = {
                id: itemId,
                name: card.dataset.itemName,
                image: card.dataset.itemImage,
                condition: card.dataset.itemCondition,
                price: parseFloat(card.dataset.itemPrice) || 0,
                stock: parseInt(card.dataset.itemStock) || 0,
                hasVariants: hasVariants,
                variants: parsedVariants
            };
        });
        
        // Initialize pre-selected items (target product)
        const preSelectedItems = theirSelectedArea.querySelectorAll('.selected-item');
        preSelectedItems.forEach(item => {
            const itemId = item.dataset.itemId;
            if (itemId) {
                state.theirSelectedItems.add(itemId);
                
                // Add event listener to remove button
                const removeBtn = item.querySelector('.btn-remove');
                if (removeBtn) {
                    removeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        removeItemSelection(itemId, 'their');
                    });
                }
            }
        });
        
        // Update initial count display to reflect pre-selected items
        if (theirItemCount && state.theirSelectedItems.size > 0) {
            theirItemCount.textContent = state.theirSelectedItems.size;
        }
    }

    /**
     * Show variant/quantity selection modal
     */
    /**
     * Show modal for variant and quantity selection
     */
    function showItemSelectionModal(itemData, side) {
        if (!itemData) {
            console.error('showItemSelectionModal called with undefined itemData');
            return;
        }
        
        const modal = document.getElementById('itemSelectionModal');
        const modalTitle = document.getElementById('modalItemTitle');
        const modalImage = document.getElementById('modalItemImageDisplay');
        const modalPrice = document.getElementById('modalItemPrice');
        const variantSection = document.getElementById('modalVariantSection');
        const variantSelect = document.getElementById('modalVariantSelect');
        const quantityInput = document.getElementById('modalQuantityInput');
        const maxStockDisplay = document.getElementById('modalMaxStock');
        const confirmBtn = document.getElementById('modalConfirmSelection');
        const quantityWarning = document.getElementById('quantityWarning');
        
        // Reset warning
        if (quantityWarning) {
            quantityWarning.style.display = 'none';
            quantityWarning.textContent = '';
        }
        
        // Set item details
        modalTitle.textContent = itemData.name;
        modalImage.src = itemData.image;
        modalPrice.textContent = `₱${itemData.price.toFixed(2)}`;
        
        console.log('Item data for modal:', {
            hasVariants: itemData.hasVariants,
            variants: itemData.variants,
            variantSection: variantSection
        });
        
        // Handle variants
        if (itemData.hasVariants && itemData.variants && itemData.variants.options && itemData.variants.optionStocks) {
            console.log('Showing variant section with options:', itemData.variants.options);
            variantSection.style.display = 'block';
            variantSelect.innerHTML = '';
            
            itemData.variants.options.forEach((option, index) => {
                const stock = itemData.variants.optionStocks[index] || 0;
                const optionEl = document.createElement('option');
                optionEl.value = index;
                optionEl.textContent = `${option} (${stock} available)`;
                optionEl.dataset.stock = stock;
                if (stock === 0) optionEl.disabled = true;
                variantSelect.appendChild(optionEl);
            });
            
            // Set initial max from first available variant
            let firstAvailableStock = 0;
            for (let i = 0; i < itemData.variants.optionStocks.length; i++) {
                const stock = itemData.variants.optionStocks[i] || 0;
                if (stock > 0) {
                    firstAvailableStock = stock;
                    variantSelect.selectedIndex = i;
                    break;
                }
            }
            
            quantityInput.max = firstAvailableStock;
            quantityInput.value = Math.min(1, firstAvailableStock);
            maxStockDisplay.textContent = firstAvailableStock;
            
            // Update on variant change
            variantSelect.addEventListener('change', function() {
                const selectedStock = parseInt(this.options[this.selectedIndex].dataset.stock) || 0;
                const currentQtyInput = document.getElementById('modalQuantityInput');
                currentQtyInput.max = selectedStock;
                currentQtyInput.value = Math.min(parseInt(currentQtyInput.value) || 1, selectedStock);
                maxStockDisplay.textContent = selectedStock;
            });
        } else {
            variantSection.style.display = 'none';
            quantityInput.max = itemData.stock;
            quantityInput.value = Math.min(1, itemData.stock);
            maxStockDisplay.textContent = itemData.stock;
        }
        
        quantityInput.value = quantityInput.value || 1;
        quantityInput.min = 1;
        
        // Get the quantity buttons
        const qtyBtnMinus = document.querySelector('.qty-btn:first-of-type');
        const qtyBtnPlus = document.querySelector('.qty-btn:last-of-type');
        
        // Add event listeners for quantity buttons (read current max dynamically)
        if (qtyBtnMinus) {
            qtyBtnMinus.onclick = function() {
                const input = document.getElementById('modalQuantityInput');
                const currentVal = parseInt(input.value) || 1;
                if (currentVal > 1) {
                    input.value = currentVal - 1;
                }
            };
        }
        
        if (qtyBtnPlus) {
            qtyBtnPlus.onclick = function() {
                const input = document.getElementById('modalQuantityInput');
                const currentVal = parseInt(input.value) || 1;
                const maxVal = parseInt(input.max) || 1;
                if (currentVal < maxVal) {
                    input.value = currentVal + 1;
                }
            };
        }
        
        // Prevent typing 0 or negative values
        quantityInput.addEventListener('input', function() {
            if (this.value === '' || parseInt(this.value) < 1) {
                this.value = 1;
            }
            const maxVal = parseInt(this.max) || 1;
            if (parseInt(this.value) > maxVal) {
                this.value = maxVal;
            }
        });
        
        quantityInput.addEventListener('blur', function() {
            if (this.value === '' || parseInt(this.value) < 1) {
                this.value = 1;
            }
        });
        
        // Show modal
        modal.style.display = 'flex';
        
        // Handle confirm - get the new element references
        const finalVariantSelect = document.getElementById('modalVariantSelect');
        const finalQuantityInput = document.getElementById('modalQuantityInput');
        const finalQuantityWarning = document.getElementById('quantityWarning');
        
        confirmBtn.onclick = function() {
            const quantity = parseInt(finalQuantityInput.value) || 1;
            const selectedVariantIndex = itemData.hasVariants && itemData.variants ? parseInt(finalVariantSelect.value) : null;
            const selectedVariantName = itemData.hasVariants && itemData.variants && finalVariantSelect.options[finalVariantSelect.selectedIndex] 
                ? finalVariantSelect.options[finalVariantSelect.selectedIndex].textContent.split(' (')[0] 
                : null;
            
            // Clear any previous warning
            if (finalQuantityWarning) {
                finalQuantityWarning.style.display = 'none';
                finalQuantityWarning.textContent = '';
            }
            
            console.log('Adding item:', {
                name: itemData.name,
                quantity: quantity,
                variantIndex: selectedVariantIndex,
                variantName: selectedVariantName
            });
            
            const result = addItemSelection(itemData, side, quantity, selectedVariantIndex, selectedVariantName);
            
            if (result.success) {
                modal.style.display = 'none';
            } else if (result.warning && finalQuantityWarning) {
                // Show warning message
                finalQuantityWarning.textContent = result.warning;
                finalQuantityWarning.style.display = 'block';
            }
        };
    }

    /**
     * Add item with quantity and variant info
     * Returns {success: boolean, warning: string}
     */
    function addItemSelection(itemData, side, quantity, variantIndex, variantName) {
        const selectedSet = side === 'my' ? state.mySelectedItems : state.theirSelectedItems;
        const selectedArea = side === 'my' ? mySelectedArea : theirSelectedArea;
        const itemsGrid = side === 'my' ? myItemsGrid : theirItemsGrid;
        const selectedDetails = side === 'my' ? state.mySelectedItemsDetails : state.theirSelectedItemsDetails;
        
        // Create unique key for item+variant combination
        const selectionKey = variantIndex !== null ? `${itemData.id}_variant_${variantIndex}` : itemData.id;
        
        // Determine max stock for this item/variant
        let maxStock;
        if (variantIndex !== null && itemData.variants && itemData.variants.optionStocks) {
            maxStock = itemData.variants.optionStocks[variantIndex] || 0;
        } else {
            maxStock = itemData.stock || 0;
        }
        
        // Check if this item+variant already exists
        const existingCard = selectedArea.querySelector(`.selected-item[data-item-id="${selectionKey}"]`);
        
        if (existingCard) {
            // Update existing card's quantity
            const currentQty = selectedDetails[selectionKey].quantity;
            const newQty = currentQty + quantity;
            
            // Validate against max stock
            if (newQty > maxStock) {
                return {
                    success: false,
                    warning: `Cannot add ${quantity} more. You already have ${currentQty} selected and max stock is ${maxStock}.`
                };
            }
            
            selectedDetails[selectionKey].quantity = newQty;
            
            // Update the display
            const qtyDisplay = existingCard.querySelector('.selected-item-qty');
            if (qtyDisplay) {
                qtyDisplay.textContent = `Qty: ${newQty}`;
            }
            
            updateUI();
            return { success: true };
        }
        
        // Item doesn't exist, create new card
        selectedDetails[selectionKey] = {
            price: itemData.price,
            quantity: quantity
        };
        
        // Add to selected set
        selectedSet.add(selectionKey);
        
        // Remove empty state
        const emptyState = selectedArea.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }
        
        // Create selected card
        const selectedCard = document.createElement('div');
        selectedCard.className = 'selected-item';
        selectedCard.dataset.itemId = selectionKey;
        selectedCard.dataset.originalId = itemData.id;
        selectedCard.innerHTML = `
            <img src="${itemData.image}" alt="${itemData.name}" class="selected-item-image">
            <div class="selected-item-details">
                <div class="selected-item-name">${itemData.name}</div>
                ${variantName ? `<div class="selected-item-variant">${variantName}</div>` : ''}
                <div class="selected-item-qty">Qty: ${quantity}</div>
            </div>
            <button class="btn-remove" data-item-id="${selectionKey}" data-side="${side}">&times;</button>
        `;
        
        selectedArea.appendChild(selectedCard);
        
        // Add remove button listener
        const removeBtn = selectedCard.querySelector('.btn-remove');
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            removeItemSelection(selectionKey, side);
        });
        
        // Mark original card as selected
        updateItemCardState(itemData.id, itemsGrid, true);
        updateUI();
        
        return { success: true };
    }

    /**
     * Remove item selection
     */
    function removeItemSelection(selectionKey, side) {
        const selectedSet = side === 'my' ? state.mySelectedItems : state.theirSelectedItems;
        const selectedArea = side === 'my' ? mySelectedArea : theirSelectedArea;
        const itemsGrid = side === 'my' ? myItemsGrid : theirItemsGrid;
        const selectedDetails = side === 'my' ? state.mySelectedItemsDetails : state.theirSelectedItemsDetails;
        
        // Remove from set
        selectedSet.delete(selectionKey);
        
        // Remove price details
        delete selectedDetails[selectionKey];
        
        // Remove card
        const card = selectedArea.querySelector(`.selected-item[data-item-id="${selectionKey}"]`);
        if (card) {
            const originalId = card.dataset.originalId || selectionKey;
            card.remove();
            
            // Check if this was the last variant of this item
            const hasOtherVariants = Array.from(selectedSet).some(key => key.startsWith(originalId));
            if (!hasOtherVariants) {
                updateItemCardState(originalId, itemsGrid, false);
            }
        }
        
        // Add empty state if needed
        if (selectedSet.size === 0) {
            addEmptyState(selectedArea);
        }
        
        updateUI();
    }

    /**
     * Add empty state to selected area
     */
    function addEmptyState(selectedArea) {
        // Check if empty state already exists
        if (selectedArea.querySelector('.empty-state')) {
            return; // Don't add another one
        }
        
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <p>Click items below to add to your ${selectedArea.id === 'mySelectedArea' ? 'offer' : 'request'}</p>
        `;
        selectedArea.appendChild(emptyState);
    }

    /**
     * Update item card state (selected/unselected)
     */
    function updateItemCardState(itemId, itemsGrid, isSelected) {
        const card = itemsGrid.querySelector(`.item-card[data-item-id="${itemId}"]`);
        if (card) {
            if (isSelected) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }
    }

    /**
     * Update UI counts and button state
     */
    function updateUI() {
        // Update counts
        myItemCount.textContent = state.mySelectedItems.size;
        theirItemCount.textContent = state.theirSelectedItems.size;

        // Calculate and update total prices
        let myTotal = 0;
        for (const key in state.mySelectedItemsDetails) {
            const item = state.mySelectedItemsDetails[key];
            myTotal += item.price * item.quantity;
        }
        
        let theirTotal = 0;
        for (const key in state.theirSelectedItemsDetails) {
            const item = state.theirSelectedItemsDetails[key];
            theirTotal += item.price * item.quantity;
        }
        
        // Format and display totals
        myTotalPrice.textContent = '₱' + myTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        theirTotalPrice.textContent = '₱' + theirTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Require at least MY items to be selected (cannot send empty offer)
        const hasMyItems = state.mySelectedItems.size > 0;
        btnSendOffer.disabled = !hasMyItems;

        // Update button text based on selection
        if (state.mySelectedItems.size > 0 && state.theirSelectedItems.size > 0) {
            btnSendOffer.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Send Trade Offer
            `;
        } else if (state.mySelectedItems.size > 0) {
            btnSendOffer.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12V22H4V12"></path>
                    <path d="M22 7H2V12H22V7Z"></path>
                    <path d="M12 22V7"></path>
                    <path d="M12 7H7.5C6.83696 7 6.20107 6.73661 5.73223 6.26777C5.26339 5.79893 5 5.16304 5 4.5C5 3.83696 5.26339 3.20107 5.73223 2.73223C6.20107 2.26339 6.83696 2 7.5 2C11 2 12 7 12 7Z"></path>
                    <path d="M12 7H16.5C17.163 7 17.7989 6.73661 18.2678 6.26777C18.7366 5.79893 19 5.16304 19 4.5C19 3.83696 18.7366 3.20107 18.2678 2.73223C17.7989 2.26339 17.163 2 16.5 2C13 2 12 7 12 7Z"></path>
                </svg>
                Send Gift Offer
            `;
        } else if (state.theirSelectedItems.size > 0) {
            btnSendOffer.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Send Request
            `;
        }
    }

    /**
     * Handle item card click
     */
    function handleItemCardClick(e) {
        const card = e.target.closest('.item-card');
        if (!card) return;
        
        // Allow clicking even if selected (to add more variants or quantities)
        // But skip if it's the target product card with special styling
        if (card.classList.contains('target-product-card')) {
            // Can still click target product to add more variants
        }

        const itemId = card.dataset.itemId;
        const grid = card.closest('.items-grid');
        const side = grid.id === 'myItemsGrid' ? 'my' : 'their';
        const itemsData = side === 'my' ? state.myItemsData : state.theirItemsData;

        // Check if item data exists
        if (!itemsData[itemId]) {
            console.error('Item data not found for ID:', itemId);
            console.log('Available items:', Object.keys(itemsData));
            return;
        }

        // Show modal for variant/quantity selection
        showItemSelectionModal(itemsData[itemId], side);
    }

    /**
     * Search/filter items
     */
    function filterItems(searchTerm, grid) {
        const cards = grid.querySelectorAll('.item-card');
        const lowerSearch = searchTerm.toLowerCase();

        cards.forEach(card => {
            const name = card.dataset.itemName.toLowerCase();
            const condition = card.dataset.itemCondition.toLowerCase();

            if (name.includes(lowerSearch) || condition.includes(lowerSearch)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    /**
     * Handle send offer button click
     */
    function handleSendOffer() {
        // Disable button to prevent double submission
        btnSendOffer.disabled = true;
        btnSendOffer.innerHTML = '<span class="spinner"></span> Sending...';

        // Prepare my items array
        const myItems = [];
        for (const [selectionKey, details] of Object.entries(state.mySelectedItemsDetails)) {
            const originalId = selectionKey.includes('_variant_') 
                ? selectionKey.split('_variant_')[0] 
                : selectionKey;
            
            const variantIndex = selectionKey.includes('_variant_')
                ? parseInt(selectionKey.split('_variant_')[1])
                : null;
            
            const itemData = state.myItemsData[originalId];
            const variantName = variantIndex !== null && itemData.variants 
                ? itemData.variants.options[variantIndex] 
                : null;
            
            myItems.push({
                id: parseInt(originalId),
                quantity: details.quantity,
                variant_index: variantIndex,
                variant_name: variantName,
                price: details.price
            });
        }

        // Prepare their items array
        const theirItems = [];
        for (const [selectionKey, details] of Object.entries(state.theirSelectedItemsDetails)) {
            const originalId = selectionKey.includes('_variant_') 
                ? selectionKey.split('_variant_')[0] 
                : selectionKey;
            
            const variantIndex = selectionKey.includes('_variant_')
                ? parseInt(selectionKey.split('_variant_')[1])
                : null;
            
            const itemData = state.theirItemsData[originalId];
            const variantName = variantIndex !== null && itemData.variants 
                ? itemData.variants.options[variantIndex] 
                : null;
            
            theirItems.push({
                id: parseInt(originalId),
                quantity: details.quantity,
                variant_index: variantIndex,
                variant_name: variantName,
                price: details.price
            });
        }

        // Prepare offer data
        const offerData = {
            recipient_id: window.tradeData.recipientId,
            my_items: myItems,
            their_items: theirItems,
            _token: window.tradeData.csrfToken
        };

        console.log('Sending Trade Offer:', offerData);

        // Determine the endpoint based on whether this is a counter offer
        const endpoint = window.tradeData.isCounterOffer 
            ? `/trade/counter/${window.tradeData.originalOfferId}` 
            : '/trade/store';
        
        const successMessage = window.tradeData.isCounterOffer 
            ? 'Counter offer sent successfully!' 
            : 'Trade offer sent successfully!';

        // Send to backend
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.tradeData.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(offerData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to the specified page or trade offers with success message
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '/student/dashboard?trade_success=1';
                }
            } else if (data.error) {
                // Show error message
                alert('Error: ' + data.error);
                // Re-enable button
                btnSendOffer.disabled = false;
                updateUI();
            }
        })
        .catch(error => {
            console.error('Error sending trade offer:', error);
            alert('Failed to send trade offer. Please try again.');
            // Re-enable button
            btnSendOffer.disabled = false;
            updateUI();
        });
    }

    /**
     * Event Listeners
     */
    
    // Item card clicks
    if (myItemsGrid) {
        myItemsGrid.addEventListener('click', handleItemCardClick);
    }
    if (theirItemsGrid) {
        theirItemsGrid.addEventListener('click', handleItemCardClick);
    }

    // Search inputs
    if (searchMyItems) {
        searchMyItems.addEventListener('input', (e) => {
            filterItems(e.target.value, myItemsGrid);
        });
    }
    if (searchTheirItems) {
        searchTheirItems.addEventListener('input', (e) => {
            filterItems(e.target.value, theirItemsGrid);
        });
    }

    /**
     * Pre-select target product if provided
     */
    function preselectTargetProduct() {
        if (window.tradeData && window.tradeData.targetProduct) {
            const target = window.tradeData.targetProduct;
            
            // If product has variants, don't pre-select - let user choose variant
            if (target.hasVariants) {
                // Just highlight the card so user knows to click it
                const itemCard = theirItemsGrid.querySelector(`.item-card[data-item-id="${target.id}"]`);
                if (itemCard) {
                    itemCard.classList.add('target-product-card');
                }
                return;
            }
            
            // Check if target product card already exists in DOM (no variants case)
            const existingCard = theirSelectedArea.querySelector(`.selected-item[data-item-id="${target.id}"]`);
            
            if (existingCard) {
                // Card already exists, just register it in state
                const selectionKey = target.id.toString();
                state.theirSelectedItems.add(selectionKey);
                
                // Store price and quantity details
                state.theirSelectedItemsDetails[selectionKey] = {
                    price: target.price,
                    quantity: 1
                };
                
                // Mark the item card in the grid as selected
                const itemCard = theirItemsGrid.querySelector(`.item-card[data-item-id="${target.id}"]`);
                if (itemCard) {
                    itemCard.classList.add('selected');
                }
                
                // Attach remove button listener to the existing card
                const removeBtn = existingCard.querySelector('.btn-remove');
                if (removeBtn) {
                    removeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        removeItemSelection(selectionKey, 'their');
                    });
                }
            }
        }
    }

    // Send offer button
    if (btnSendOffer) {
        btnSendOffer.addEventListener('click', handleSendOffer);
    }

    /**
     * Pre-select items from original offer when creating a counter offer
     */
    function preselectCounterOfferItems() {
        if (!window.tradeData || !window.tradeData.isCounterOffer) {
            console.log('Not a counter offer, skipping preselection');
            return;
        }

        console.log('=== PRESELECTING COUNTER OFFER ITEMS ===');
        console.log('My items to preselect:', window.tradeData.preSelectedMyItems);
        console.log('Their items to preselect:', window.tradeData.preSelectedTheirItems);
        console.log('Available myItemsData:', Object.keys(state.myItemsData));
        console.log('Available theirItemsData:', Object.keys(state.theirItemsData));

        // Pre-select "My Items" (which were the recipient's items in the original offer)
        if (window.tradeData.preSelectedMyItems && window.tradeData.preSelectedMyItems.length > 0) {
            window.tradeData.preSelectedMyItems.forEach((item, index) => {
                console.log(`\n--- Preselecting MY item ${index + 1} ---`);
                console.log('Item data:', item);
                
                // Convert ID to string to match object keys
                const itemId = String(item.id);
                console.log('Looking for itemId:', itemId);
                
                // Get full item data from state
                const itemData = state.myItemsData[itemId];
                if (!itemData) {
                    console.error('❌ Item NOT found in myItemsData!');
                    console.log('Available keys:', Object.keys(state.myItemsData));
                    console.log('Full myItemsData:', state.myItemsData);
                    return;
                }
                
                console.log('✓ Found itemData:', itemData);
                
                // Add the item with correct parameters: (itemData, side, quantity, variantIndex, variantName)
                const result = addItemSelection(
                    itemData, 
                    'my', 
                    item.quantity, 
                    item.variantIndex, 
                    item.variantName
                );
                console.log('Preselect result:', result);
            });
        }

        // Pre-select "Their Items" (which were the sender's items in the original offer)
        if (window.tradeData.preSelectedTheirItems && window.tradeData.preSelectedTheirItems.length > 0) {
            window.tradeData.preSelectedTheirItems.forEach((item, index) => {
                console.log(`\n--- Preselecting THEIR item ${index + 1} ---`);
                console.log('Item data:', item);
                
                // Convert ID to string to match object keys
                const itemId = String(item.id);
                console.log('Looking for itemId:', itemId);
                
                // Get full item data from state
                const itemData = state.theirItemsData[itemId];
                if (!itemData) {
                    console.error('❌ Item NOT found in theirItemsData!');
                    console.log('Available keys:', Object.keys(state.theirItemsData));
                    console.log('Full theirItemsData:', state.theirItemsData);
                    return;
                }
                
                console.log('✓ Found itemData:', itemData);
                
                // Add the item with correct parameters: (itemData, side, quantity, variantIndex, variantName)
                const result = addItemSelection(
                    itemData, 
                    'their', 
                    item.quantity, 
                    item.variantIndex, 
                    item.variantName
                );
                console.log('Preselect result:', result);
            });
        }
        
        console.log('=== PRESELECTION COMPLETE ===\n');
    }

    /**
     * Initialize
     */
    initializeItemData();
    preselectTargetProduct();
    preselectCounterOfferItems();
    updateUI();

    // Add CSS for slide out animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }
    `;
    document.head.appendChild(style);
});
