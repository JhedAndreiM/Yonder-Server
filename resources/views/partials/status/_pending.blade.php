@if($cartItems->seller_id == Auth::id() && $cartItems->payment_type=='cashPayment')
<form action="{{ route('cart.confirmPayment', $cartItems->cart_id) }}" method="POST">
    @csrf
    <input id="filterValue" name="filterValue" type="hidden" value="{{ $filters }}">
    <button class="confirmCOD" type="submit">Confirm COD</button>
</form>
@else
<!-- For Seller to Confirm an Order -->
@if($cartItems->seller_id == Auth::id() && $cartItems->paymentConfirmation == "no")
<form action="{{route('cart.confirmSales', $cartItems->cart_id)}}" method="POST">
    @csrf
    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
    <button class="confirmOrder">Confirm Order</button>
</form>
@elseif ($cartItems->seller_id != Auth::id() && $cartItems->paymentConfirmation == "no")
@endif
@endif

<!-- If Confirmed na 'yung Buy Order and need nalang Iview 'yung receipt -->
<!-- Seller's View -->
@if($cartItems->seller_id == Auth::id() && $cartItems->paymentConfirmation == "yes")
@if($cartItems->gcash_receipt)
<button
    class="viewImage viewReceiptBtn"
    data-image="{{ asset('gcash_receipts/' . $cartItems->gcash_receipt) }}">
    View Image
</button>
<div class="gcashReceiptModalView" id="gcashReceiptModalView">
    <div class="gcashReceipt-ContentView" id="gcashReceipt-ContentView">
        <div class="gcashReceipt-containerView" id="gcashReceipt-containerView">
            <h3>Uploaded GCash Receipt</h3>
            <img id="receiptView" alt="Receipt Preview">
            <div class="buttonGroup">
                <button class="closeButton_ViewGcash" id="closeReceiptView">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@if($cartItems->gcash_receipt)
<form action="{{ route('cart.confirmPayment', $cartItems->cart_id) }}" method="POST">
    @csrf
    <input id="filterValue" name="filterValue" type="hidden" value="{{ $filters }}">
    <button class="confirmPayment" type="submit">Confirm Payment</button>
</form>
@else
<form action="{{ route('cart.confirmPayment', $cartItems->cart_id) }}" method="POST">
    @csrf
    <input id="filterValue" name="filterValue" type="hidden" value="{{ $filters }}">
    <button class="confirmPayment" type="submit" disabled title="Wait for buyer to add GCash receipt">Confirm Payment</button>
</form>
@endif
<!-- Buyer's View -->
@elseif ($cartItems->seller_id != Auth::id() && $cartItems->paymentConfirmation == "yes")
@if($cartItems->gcash_receipt)
<button
    class="viewImage viewReceiptBtn"
    data-image="{{ asset('gcash_receipts/' . $cartItems->gcash_receipt) }}">
    View Image
</button>
<div class="gcashReceiptModalView" id="gcashReceiptModalView">
    <div class="gcashReceipt-ContentView" id="gcashReceipt-ContentView">
        <div class="gcashReceipt-containerView" id="gcashReceipt-containerView">
            <h3>Uploaded GCash Receipt</h3>
            <img id="receiptView" alt="Receipt Preview">
            <div class="buttonGroup">
                <form action="{{route('gcash.receiptRemove', $cartItems->cart_id)}}" method="POST">
                    @csrf
                    <button class="removeImage" id="closeReceiptView">Remove Image</button>
                </form>
                <button class="closeButton_ViewGcash" id="closeReceiptView">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
<form class="uploadGcashReceipt" action="{{route('gcash.receipt', $cartItems->cart_id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input id="receiptInput" type="file" class="gcash_receipt" name="gcash_receipt" accept="image/*" required>
    <label class="lbl_gcash_receipt" for="receiptInput">Upload GCash Receipt</label>
</form>
<!-- Modal -->
<div class="gcashReceiptModal" id="gcashReceiptModal">
    <div class="gcashReceipt-Content" id="gcashReceipt-Content">
        <div class="gcashReceipt-container" id="gcashReceipt-container">
            <h3>Preview GCash Receipt</h3>
            <img id="receiptPreview" alt="Receipt Preview">
            <div class="modal-buttons">
                <button type="button" id="cancelReceipt">Cancel</button>
                <button type="button" id="submitReceipt">Submit</button>
            </div>
        </div>
    </div>
</div>
@endif