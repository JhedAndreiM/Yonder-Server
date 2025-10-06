@if($cartItems->seller_id == Auth::id())
<button
    class="view-receipt receiptButton"
    data-bs-toggle="modal"
    data-bs-target="#myModal"
    data-names="{{ $cartItems->product_name }}"
    data-prices="{{ $cartItems->unit_price }}"
    data-qtys="{{ $cartItems->quantity }}"
    data-vouchers="{{ $cartItems->voucher_applied }}"
    data-id="{{ $cartItems->cart_id }}"
    data-date="{{ $cartItems->formatted_updated_at ?? $cartItems->updated_at}}"
    onclick="openProductModalSeller(this)">Invoice</button>
@else

<button class="btn btn-primary rate-btn reviewButton" data-itemid="{{ $cartItems->product_id}}">Review</button>
<button
    class="view-receipt receiptButton"
    data-bs-toggle="modal"
    data-bs-target="#myModal"
    data-name="{{ $cartItems->product_name }}"
    data-price="{{ $cartItems->unit_price }}"
    data-qty="{{ $cartItems->quantity }}"
    data-voucher="{{ $cartItems->voucher_applied }}"
    data-id="{{ $cartItems->cart_id }}"
    data-date="{{ $cartItems->formatted_updated_at }}"
    onclick="openProductModal(this)">Invoice</button>
@endif