// Global variables
let currentSelectedVariant = 0;
let currentStock = window.productData.hasVariants ? 
    window.productData.variants.optionStocks[0] : 
    window.productData.stock;

// DOM Elements
const elements = {
    // Main page elements
    qtyDisplay: document.getElementById("qtyDisplay"),
    qtyPlus: document.getElementById("qtyPlus"),
    qtyMinus: document.getElementById("qtyMinus"),
    mainStockDisplay: document.getElementById("mainStockDisplay"),
    variantStock: document.getElementById("variantStock"),
    // Modal elements
    orderModal: document.getElementById("orderModal"),
    orderForm: document.getElementById("orderForm"),
    modalQuantityInput: document.getElementById("modalQuantityInput"),
    modalQtyPlus: document.getElementById("modalQtyPlus"),
    modalQtyMinus: document.getElementById("modalQtyMinus"),
    modalTotalDisplay: document.getElementById("modalTotalDisplay"),
    modalStockDisplay: document.getElementById("modalStockDisplay"),
    modalVariantSelect: document.getElementById("modalVariantSelect"),
    modalVoucherSelect: document.getElementById("modalVoucherSelect"),
    modalConfirmBtn: document.getElementById("modalConfirmBtn"),
    modalCloseBtn: document.querySelector(".modal-close-btn"),
    backdrop: document.querySelector(".modal-blur-background"),
    uniqueHeaderMessage: document.getElementById("uniqueHeaderMessage"),
    // Hidden inputs
    modalTotalPrice: document.getElementById("modalTotalPrice"),
    modalQuantity: document.getElementById("modalQuantity"),
    modalActionType: document.getElementById("modalActionType"),
    modalSelectedVariant: document.getElementById("modalSelectedVariant"),
    modalVoucherId: document.getElementById("modalVoucherId"),
    modalVoucherAmount: document.getElementById("modalVoucherAmount"),
    
    // Reviews
    seeReviews: document.querySelector(".seeReviews"),
    popup: document.getElementById("popup"),
    closeBtn: document.querySelector(".close-btn")
};

// Initialize the page
const paymentSelect = document.getElementById("payment");
document.addEventListener("DOMContentLoaded", function() {
    if (paymentSelect && paymentSelect.value) {
        document.getElementById("paymentType").value = paymentSelect.value;
        // Payment Type
        document.getElementById("payment").addEventListener("change", function () {
        document.getElementById("paymentType").value = this.value;
    });
    }

    initializeVariants();
    initializeEventListeners();
    initSeeMore();
    updateMainPageStock();
});

// Initialize variants
function initializeVariants() {
    if (window.productData.hasVariants) {
        // Set first variant as selected
        currentSelectedVariant = 0;
        currentStock = window.productData.variants.optionStocks[0];
        
        // Add click listeners to variant buttons
        document.querySelectorAll('.variationsButton').forEach((button, index) => {
            button.addEventListener('click', () => selectVariant(index));
            
        });
        
        // Update main page quantity max
        if (elements.qtyDisplay) {
            elements.qtyDisplay.setAttribute('max', currentStock);
        }
        if (elements.modalQuantityInput) {
            elements.modalQuantityInput.setAttribute('max', currentStock);
        }
    } else {
        currentStock = window.productData.stock;
    }
}

// Select variant function
function selectVariant(index) {
    currentSelectedVariant = index;
    currentStock = window.productData.variants.optionStocks[index];
    
    // Update variant buttons - ADD/REMOVE ACTIVE CLASS
    document.querySelectorAll('.variationsButton').forEach((btn, i) => {
        if (i === index) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    // Update stock displays
    if (elements.variantStock) {
        elements.variantStock.textContent = currentStock;
    }
    
    // Update quantity limits
    if (elements.qtyDisplay) {
            elements.qtyDisplay.setAttribute('max', currentStock);
        }
    if (parseInt(elements.qtyDisplay.value) > currentStock) {
        elements.qtyDisplay.value = Math.min(currentStock, 1);
    }
    
    updateMainPageStock();
}

// Also update the modal variant selection to sync with main page variants
if (elements.modalVariantSelect) {
    elements.modalVariantSelect.addEventListener("change", () => {
        const selectedIndex = parseInt(elements.modalVariantSelect.value);
        const selectedStock = window.productData.variants.optionStocks[selectedIndex];
        
        // Update main page variant buttons active class
        document.querySelectorAll('.variationsButton').forEach((btn, i) => {
            if (i === selectedIndex) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Update current selection
        currentSelectedVariant = selectedIndex;
        currentStock = selectedStock;
        
        elements.modalStockDisplay.textContent = selectedStock;
        elements.modalQuantityInput.setAttribute('max', selectedStock);
        
        // Update main page variant stock display
        if (elements.variantStock) {
            elements.variantStock.textContent = selectedStock;
        }
        
        // Adjust quantity if it exceeds new stock
        if (parseInt(elements.modalQuantityInput.value) > selectedStock) {
            elements.modalQuantityInput.value = Math.min(selectedStock, 1);
        }
        
        updateModalTotal();
        updateMainPageStock();
    });
}

// Update main page stock display
function updateMainPageStock() {
    if (window.productData.hasVariants) {
        if (currentStock === 0) {
            elements.mainStockDisplay.textContent = 'Out of Stock';
            elements.mainStockDisplay.className = 'outOfStock';
        } else {
            elements.mainStockDisplay.textContent = `Stocks: ${currentStock}`;
            elements.mainStockDisplay.className = 'stocks';
        }
    } else {
        if (window.productData.stock === 0) {
            elements.mainStockDisplay.textContent = 'Out of Stock';
            elements.mainStockDisplay.className = 'outOfStock';
        } else {
            elements.mainStockDisplay.textContent = `Stocks: ${window.productData.stock}`;
            elements.mainStockDisplay.className = 'stocks';
        }
    }
}

// Initialize all event listeners
function initializeEventListeners() {
    // Main page quantity controls
    if(elements.qtyPlus){
            elements.qtyPlus.addEventListener("click", () => {
        let current = parseInt(elements.qtyDisplay.value) || 1;
        if (current < currentStock) {
            elements.qtyDisplay.value = current + 1;
        }
    });
    }
    if(elements.qtyMinus){
            elements.qtyMinus.addEventListener("click", () => {
        let current = parseInt(elements.qtyDisplay.value) || 1;
        if (current > 1) {
            elements.qtyDisplay.value = current - 1;
        }
    });
    }

    if(elements.qtyDisplay){
            elements.qtyDisplay.addEventListener("input", () => {
        let val = parseInt(elements.qtyDisplay.value);
        if (isNaN(val) || val < 1) {
            val = 1;
        } else if (val > currentStock) {
            val = currentStock;
        }
        elements.qtyDisplay.value = val;
    });
    }


    const addToCartBtn = document.getElementById("addToCart");
    const buyNowBtn = document.getElementById("buyNow");

    // Modal triggers
    if (addToCartBtn) {
        addToCartBtn.addEventListener("click", () => openModal('in_cart'));
    }
    if (buyNowBtn) {
        buyNowBtn.addEventListener("click", () => openModal('buy_now'));
    }

    // Modal controls
    if (elements.modalQtyPlus) {
        elements.modalQtyPlus.addEventListener("click", () => {
            let current = parseInt(elements.modalQuantityInput.value) || 1;
            let maxStock = getModalMaxStock();
            if (current < maxStock) {
                elements.modalQuantityInput.value = current + 1;
                updateModalTotal();
            }
        });
    }

    if (elements.modalQtyMinus) {
        elements.modalQtyMinus.addEventListener("click", () => {
            let current = parseInt(elements.modalQuantityInput.value) || 1;
            if (current > 1) {
                elements.modalQuantityInput.value = current - 1;
                updateModalTotal();
            }
        });
    }

    if (elements.modalQuantityInput) {
        elements.modalQuantityInput.addEventListener("input", () => {
            let val = parseInt(elements.modalQuantityInput.value);
            let maxStock = getModalMaxStock();
            if (isNaN(val) || val < 1) {
                val = 1;
            } else if (val > maxStock) {
                val = maxStock;
            }
            elements.modalQuantityInput.value = val;
            updateModalTotal();
        });
    }

    // Modal variant selection
    if (elements.modalVariantSelect) {
        elements.modalVariantSelect.addEventListener("change", () => {
            const selectedIndex = parseInt(elements.modalVariantSelect.value);
            const selectedStock = window.productData.variants.optionStocks[selectedIndex];
            
            elements.modalStockDisplay.textContent = selectedStock;
            elements.modalQuantityInput.setAttribute('max', selectedStock);
            
            // Adjust quantity if it exceeds new stock
            if (parseInt(elements.modalQuantityInput.value) > selectedStock) {
                elements.modalQuantityInput.value = Math.min(selectedStock, 1);
            }
            
            updateModalTotal();
        });
    }

    // Modal voucher selection
    if (elements.modalVoucherSelect) {
        elements.modalVoucherSelect.addEventListener("change", updateModalTotal);
    }

    // Modal close
    if (elements.modalCloseBtn) {
        elements.modalCloseBtn.addEventListener("click", closeModal);
        elements.backdrop.addEventListener("click", closeModal);
    }

    // Form submission
    if (elements.orderForm) {
        elements.orderForm.addEventListener("submit", handleFormSubmission);
    }

// Reviews popup
const reviewLinks = document.querySelectorAll(".seeReviews");

reviewLinks.forEach(link => {
    link.addEventListener("click", (e) => {
        console.log('clicked');
        e.preventDefault();
        elements.popup.classList.add("show");
        setTimeout(initSeeMore, 100);
    });
});

    if (elements.closeBtn) {
        elements.closeBtn.addEventListener("click", () => {
            elements.popup.classList.remove("show");
        });
    }

    if (elements.popup) {
        elements.popup.addEventListener("click", (e) => {
            if (e.target === elements.popup) {
                elements.popup.classList.remove("show");
            }
        });
    }

    // Close modal when clicking outside
    elements.orderModal.addEventListener("click", (e) => {
        if (e.target === elements.orderModal) {
            console.log('clicked');
            closeModal();
        }
    });
}

// Get maximum stock for modal
function getModalMaxStock() {
    if (window.productData.hasVariants && elements.modalVariantSelect) {
        const selectedIndex = parseInt(elements.modalVariantSelect.value);
        return window.productData.variants.optionStocks[selectedIndex];
    }
    return currentStock;
}

// Open modal function
function openModal(actionType) {
    // Set action type
    elements.modalActionType.value = actionType;

    if (actionType === "in_cart") {
        elements.uniqueHeaderMessage.textContent = "Add To Cart";
    } else if (actionType === "buy_now") {
        elements.uniqueHeaderMessage.textContent = "Buy Now";
    }    
    // Set initial quantity from main page
    const mainPageQty = parseInt(elements.qtyDisplay.value) || 1;
    elements.modalQuantityInput.value = mainPageQty;
    
    // Set variant selection if variants exist
    if (window.productData.hasVariants) {
        elements.modalVariantSelect.value = currentSelectedVariant;
        elements.modalStockDisplay.textContent = currentStock;
        elements.modalQuantityInput.setAttribute('max', currentStock);
    }
    
    // Reset voucher
    if (elements.modalVoucherSelect) {
        elements.modalVoucherSelect.value = "";
    }
    
    // Update total
    updateModalTotal();
    
    // Show modal
    elements.orderModal.classList.remove("hidden");
}

// Close modal function
function closeModal() {
    elements.orderModal.classList.add("hidden");
}

// Update modal total
function updateModalTotal() {
    const quantity = parseInt(elements.modalQuantityInput.value) || 1;
    const unitPrice = window.productData.price;
    let discount = 0;
    
    // Get voucher discount
    if (elements.modalVoucherSelect && elements.modalVoucherSelect.value) {
        const selectedOption = elements.modalVoucherSelect.options[elements.modalVoucherSelect.selectedIndex];
        discount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
    }
    
    // Calculate total
    let total = (unitPrice * quantity) - discount;
    if (total < 0) total = 0;
    
    // Update displays
    elements.modalTotalDisplay.textContent = total.toFixed(2);
    
    // Update hidden inputs
    elements.modalTotalPrice.value = total;
    elements.modalQuantity.value = quantity;
    elements.modalVoucherAmount.value = discount;
    
    // Set variant info
    if (window.productData.hasVariants && elements.modalVariantSelect) {
        elements.modalSelectedVariant.value = elements.modalVariantSelect.value;
    }
    
    // Set voucher ID
    if (elements.modalVoucherSelect) {
        elements.modalVoucherId.value = elements.modalVoucherSelect.value || "";
    }
}

// Handle form submission
function handleFormSubmission(e) {
    e.preventDefault();
    
    // Validate stock
    const quantity = parseInt(elements.modalQuantityInput.value);
    const maxStock = getModalMaxStock();
    
    if (quantity > maxStock) {
        alert(`Sorry, only ${maxStock} items available in stock.`);
        return;
    }
    
    if (quantity < 1) {
        alert("Please select a valid quantity.");
        return;
    }
    
    // Submit form
    elements.orderForm.submit();
}

// Reviews functionality
function initSeeMore() {
    const collapsedHeight = 60;

    document.querySelectorAll(".review").forEach((review) => {
        const reviewText = review.querySelector(".review-text");
        const seeMoreLink = review.querySelector(".see-more");

        if (!reviewText || !seeMoreLink) return;

        // Reset state
        reviewText.classList.remove("expanded");
        reviewText.style.maxHeight = collapsedHeight + "px";
        seeMoreLink.textContent = "See more";

        // Show "See more" only if text overflows
        if (reviewText.scrollHeight > collapsedHeight) {
            seeMoreLink.style.display = "inline-block";
        } else {
            seeMoreLink.style.display = "none";
        }

        seeMoreLink.onclick = () => {
            const expanded = reviewText.classList.toggle("expanded");
            if (expanded) {
                reviewText.style.maxHeight = reviewText.scrollHeight + "px";
                seeMoreLink.textContent = "See less";
            } else {
                reviewText.style.maxHeight = collapsedHeight + "px";
                seeMoreLink.textContent = "See more";
            }
        };
    });
}

// for image next button
document.addEventListener('DOMContentLoaded', function () {
    let currentIndex = 0;
    const images = document.querySelectorAll('.sliderImage');

    function showImage(index) {
      images.forEach((img, i) => {
        img.classList.toggle('active', i === index);
      });
    }

    function prevImage() {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      showImage(currentIndex);
    }

    function nextImage() {
      currentIndex = (currentIndex + 1) % images.length;
      showImage(currentIndex);
    }

    // Make prev/next globally available
    window.prevImage = prevImage;
    window.nextImage = nextImage;

    // Show the first image initially
    showImage(currentIndex);
    // link to papunta sa chat ni seller

  });

// for modal
// document.addEventListener("DOMContentLoaded", function() {
//     const yesBtn = document.getElementById("uniqueConfirmYes");
//     const noBtn = document.getElementById("uniqueConfirmNo");

//     noBtn.addEventListener("click", function () {
//         document.getElementById("uniqueConfirmModal").style.display = "none";
//     });
// });
