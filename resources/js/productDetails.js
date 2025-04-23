document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("productModal");
    const closeBtn = document.querySelector(".close-btn");
    const closeBtnbuy = document.querySelector(".close-btn-buy");

    const cartModal = document.getElementById("cartModal");
    const buyModal = document.getElementById("buyModal");


    const quantityInput = document.getElementById("quantity");
    const quantityBuy = document.getElementById("quantityBuy");
    const unitPrice = parseFloat(document.getElementById("unitPrice").textContent.replace(',', ''));
    const unitPriceBuy = parseFloat(document.getElementById("unitPriceBuy").textContent.replace(',', ''));
    const totalPrice = document.getElementById("totalPrice");
    const totalPriceBuy = document.getElementById("totalPriceBuy");

    const voucherSelect = document.getElementById("voucher");
    const voucherBuySelect = document.getElementById("voucherBuy");


    addToCartBtn.addEventListener("click", () => {
        cartModal.classList.remove("hidden");
        updateTotal();
    });

    buyNowBtn.addEventListener("click", () => {
        buyModal.classList.remove("hidden");
        updateTotalBuy();
    });

    function updateTotal() {
        const qty = parseInt(quantityInput.value) || 1;
        const discount = voucherSelect ? parseInt(voucherSelect.value) : 0;
        let total = unitPrice * qty - discount;
        if (total < 0) total = 0;
        totalPrice.textContent = total.toFixed(2);
        document.getElementById("total_price_Order").value = total;
        document.getElementById("quantity_Order").value = qty;
    }
    function updateTotalBuy() {
        const qty = parseInt(quantityBuy.value) || 1;
        const discount = voucherBuySelect ? parseInt(voucherBuySelect.value) : 0;
        let totalBuy = unitPriceBuy * qty - discount;
        if (totalBuy < 0) totalBuy = 0;
        totalPriceBuy.textContent = totalBuy.toFixed(2);
        document.getElementById("total_price_BuyNow").value = totalBuy;
        document.getElementById("quantity_BuyNow").value = qty;

    }

    closeBtn.addEventListener("click", () => {
        cartModal.classList.add("hidden");
        buyModal.classList.add("hidden");
    });
    closeBtnbuy.addEventListener("click", () => {
        buyModal.classList.add("hidden");
    });
    
    quantityInput.addEventListener("input", updateTotal);
    if (voucherSelect) voucherSelect.addEventListener("change", updateTotal);

    quantityBuy.addEventListener("input", updateTotalBuy);
    if (voucherBuySelect) voucherBuySelect.addEventListener("change", updateTotalBuy);


    const inputBuyNow = document.getElementById('quantityBuy');

        inputBuyNow.addEventListener('keydown', function(e) {
        e.preventDefault(); 
    });
    const inputAddtoCart = document.getElementById('quantity');

        inputAddtoCart.addEventListener('keydown', function(e) {
        e.preventDefault(); 
    });
});



