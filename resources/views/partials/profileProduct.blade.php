<style>
    .viewReasonBtn {
        padding: 8px 16px;
        background-color: #fff;
        color: #BE1A1A;
        border: 1px solid #BE1A1A;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .viewReasonBtn:hover {
        background-color: #BE1A1A;
        color: #fff;
    }

    .reason-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }

    .reason-modal-content {
        position: relative;
        background-color: #fff;
        margin: 15% auto;
        padding: 0;
        width: 90%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.3s ease;
    }

    .reason-modal-header {
        background-color: #BE1A1A;
        color: white;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .reason-modal-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .close-reason-modal {
        color: white;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-reason-modal:hover {
        color: #f0f0f0;
    }

    .reason-modal-body {
        padding: 20px;
    }

    .reason-detail {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
    }

    .reason-detail p {
        margin: 10px 0;
        line-height: 1.5;
    }

    .reason-detail strong {
        color: #333;
        min-width: 120px;
        display: inline-block;
    }

    .detail-text {
        color: #666;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

@if ($items->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($items as $cartItems)
    @php
        $images = DB::table('product_images')
        ->where('product_id', $cartItems->product_id)
        ->get();
        $firstImage= $images->first();
    @endphp
          <div class="card" data-id="{{$cartItems->cart_id}}">
            <div class="text">
            <!-- For User Name -->
            @if(Auth::user()->role==="student")
                <!-- <a href="/Yonder/Chat/{{$cartItems->seller_id}}" class="seller">{{$cartItems->seller_name}} {{$cartItems->seller_lastname}}</a> -->
                <a href="{{ route('stalk.profile', $cartItems->seller_id) }}" class="seller">{{$cartItems->seller_name}} {{$cartItems->seller_lastname}}</a>
            @elseif(Auth::user()->role==="organization")
                <a href="{{ route('stalk.profile', $cartItems->buyer_id) }}" class="seller buyer">{{$cartItems->buyer_name}} {{$cartItems->buyer_lastname}}</a>
                <!-- <a href="/Yonder/Chat/{{$cartItems->buyer_id}}" class="seller buyer">{{$cartItems->buyer_name}} {{$cartItems->buyer_lastname}}</a> -->
            @endif
            <!-- End For User Name -->

            <!-- For the Status -->
            @if($cartItems->status == 'receive')
                <!-- Seller For Receiving -->
                @if ($cartItems->seller_id == Auth::id())
                    <!-- Organization Seller -->
                    @if(Auth::user() && Auth::user()->role == 'organization')
                    <p class="status">Ready for Pick Up</p>
                    @else <!-- For student seller -->
                    <p class="status">Ready for Meet Up</p>
                    @endif
                    <!-- End -->
                @else <!-- For student Buyer -->
                    <p class="status">Ready for Pick Up</p>
                @endif
                
            @elseif ($cartItems->status == 'pending')
                @if($cartItems->seller_id == Auth::id() && $cartItems->paymentConfirmation == "no")
                    <p class="status">Awaiting for Approval</p>
                @elseif($cartItems->seller_id != Auth::id() && $cartItems->paymentConfirmation == "no")
                    <p class="status">Pending Seller's Confirmation</p>
                @elseif($cartItems->seller_id == Auth::id() && $cartItems->paymentConfirmation == "yes" && $cartItems->gcash_receipt)
                    <!-- Seller Side After Payment -->
                    <p class="status">Verify Payment</p>
                @elseif($cartItems->seller_id != Auth::id() && $cartItems->paymentConfirmation == "yes" && $cartItems->gcash_receipt)
                    <!-- Buyer Side After Payment -->
                    <p class="status">Pending Payment Verification</p>
                @elseif($cartItems->seller_id == Auth::id() && $cartItems->paymentConfirmation == "yes")
                    <!-- Seller Side Before Payment -->
                    <p class="status">Awaiting Payment</p>
                @elseif($cartItems->seller_id != Auth::id() && $cartItems->paymentConfirmation == "yes")
                    <!-- Buyer Side Before Payment -->
                    <p class="status">Pending Payment</p>
                @else
                    <p class="status">Pending</p>
                @endif
                <!-- End -->
            @elseif ($cartItems->status == 'cancelled')
                <p class="status">Cancelled</p>
            @elseif ($cartItems->status == 'completed')
                <p class="status">Transaction Successful</p>
            @endif
            <!-- End For the Status -->
            </div>
            <div class="content">
              <div class="leftPart">
                <div class="placeholder">
                    <!-- For showing the first Image -->
                    @if($firstImage && $firstImage->image_path)
                    <img class="placeholder" src="{{ asset('images/' . $firstImage->image_path) }}" alt="Product Image" />
                    @else
                    <img class="placeholder" src="{{ asset('img/default-product.png') }}" alt="Product Image" />
                    @endif
                    <!-- End For showing the first Image -->
                </div>
                <div class="info">
                  <p class="productName">{{$cartItems->product_name}}</p>
                  @if(!empty($cartItems->listing_update))
                  <p>Order Date: {{ \Carbon\Carbon::parse($cartItems->listing_update)->format('F d, Y') }}</p>
                  @endif
                  <p class="price">Price Per Item: P {{$cartItems->unit_price}}</p>
                  @if($cartItems->selected_variant)
                  <p class="variants">Variant: {{$cartItems->selected_variant}}</p>
                  @endif
                    @if($cartItems->voucher_applied != 0)
                        <p class="voucher">Voucher Applied: P {{$cartItems->voucher_applied}}</p>
                    @endif
                  <p>
                    @if($cartItems->quantity <= 1)
                    {{$cartItems->quantity}} item
                    @else
                    {{$cartItems->quantity}} items
                    @endif
                  </p>
                </div>
              </div>
              <div class="rightPart">
                <div class="totalPrice">
                  <p class="price">Total Price: P {{ ($cartItems->unit_price*$cartItems->quantity)-$cartItems->voucher_applied }}</p>
                </div>
                <div class="cardButtons">
                    <!-- MAIN IF STATEMENT FOR PENDING -->
                    @if($cartItems->status=='pending')
                        @include('partials.status._pending', ['cartItems' => $cartItems, 'filters' => $filters])
                    <!-- MAIN IF STATEMENT FOR RECEIVE -->
                    @elseif($cartItems->status=='receive')
                        @include('partials.status._receive', ['cartItems' => $cartItems, 'filters' => $filters])
                    <!-- MAIN IF STATEMENT FOR COMPLETED -->
                    @elseif($cartItems->status=='completed')
                        @include('partials.status._completed', ['cartItems' => $cartItems, 'filters' => $filters])
                    @endif
                    @if($cartItems->status !== 'cancelled' && $cartItems->status !== 'completed' && $cartItems->status !=='receive')
                    <form action="{{route('cart.cancel',$cartItems->cart_id)}}" method="post">
                        @csrf
                        <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                        <input type="hidden" name="cancel_reason" id="cancel_reason_{{ $cartItems->cart_id }}">
                        <input type="hidden" name="custom_reason" id="custom_reason_{{ $cartItems->cart_id }}">
                        <button type="submit" class="cancelButton">Cancel</button>
                    </form>
                    @endif
                    @if($cartItems->status !== 'cancelled')
                    @if(Auth::user()->role === "student")
                        <button onclick="window.location.href='/Yonder/Chat/{{$cartItems->seller_id}}'" class="cancelButton">Message</button>
                    @elseif(Auth::user()->role === "organization")
                        <button onclick="window.location.href='/Yonder/Chat/{{$cartItems->buyer_id}}'" class="cancelButton">Message</button>
                    @endif
                    @endif
                    @if($cartItems->status == 'cancelled')
                        <!-- View Reason Button -->
                        <button type="button"
                                class="viewReasonBtn"
                                data-cart-id="{{ $cartItems->cart_id }}"
                                onclick="showReasonModal(this)">
                            View Reason
                        </button>

                        <!-- Custom Modal for Cancel Reason -->
                        <div id="reasonModal_{{ $cartItems->cart_id }}" class="reason-modal">
                            <div class="reason-modal-content">
                                <div class="reason-modal-header" style="text-align:center;">
                                    <h3 style="color:white;text-align:center;">Cancellation Details</h3>
                                    <span class="close-reason-modal" onclick="closeReasonModal({{ $cartItems->cart_id }})">&times;</span>
                                </div>
                                <div class="reason-modal-body">
                                    @php
                                        $cancelDetails = DB::table('cancelled_cart_items')
                                            ->where('original_cart_id', $cartItems->cart_id)
                                            ->first();
                                    @endphp
                                    @if($cancelDetails)
                                        <div class="reason-detail">
                                            <p><strong>Cancelled By:</strong> 
                                                <span class="detail-text">{{ ucfirst($cancelDetails->cancelled_by) }}</span>
                                            </p>
                                            <p><strong>Reason:</strong> 
                                                <span class="detail-text">
                                                    @if($cancelDetails->cancel_reason == 'changed_mind')
                                                        Changed my mind
                                                    @elseif($cancelDetails->cancel_reason == 'found_better_deal')
                                                        Found a better deal elsewhere
                                                    @elseif($cancelDetails->cancel_reason == 'seller_unresponsive')
                                                        Seller is unresponsive
                                                    @elseif($cancelDetails->cancel_reason == 'product_unavailable')
                                                        Product is no longer available
                                                    @elseif($cancelDetails->cancel_reason == 'other')
                                                        Other reason
                                                    @else
                                                        {{ ucfirst(str_replace('_', ' ', $cancelDetails->cancel_reason)) }}
                                                    @endif
                                                </span>
                                            </p>
                                            @if($cancelDetails->custom_reason)
                                                <p><strong>Additional Details:</strong> 
                                                    <span class="detail-text">{{ $cancelDetails->custom_reason }}</span>
                                                </p>
                                            @endif
                                            <p><strong>Cancelled On:</strong> 
                                                <span class="detail-text">{{ \Carbon\Carbon::parse($cancelDetails->cancelled_at)->format('F d, Y h:i A') }}</span>
                                            </p>
                                        </div>
                                    @else
                                        <p>No cancellation details available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
              </div>
            </div>
          </div>
          
    @endforeach
@endif

<script>
    function showReasonModal(button) {
        const cartId = button.getAttribute('data-cart-id');
        const modal = document.getElementById(`reasonModal_${cartId}`);
        modal.style.display = 'block';
        
        // Close on outside click
        window.onclick = function(event) {
            if (event.target === modal) {
                closeReasonModal(cartId);
            }
        }
    }

    function closeReasonModal(cartId) {
        const modal = document.getElementById(`reasonModal_${cartId}`);
        modal.style.display = 'none';
    }
</script>