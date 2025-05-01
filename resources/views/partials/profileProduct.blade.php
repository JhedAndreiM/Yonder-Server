@if ($items->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($items as $cartItems)
    @php
        $images = explode(',', $cartItems->image_path);
        $firstImage = $images[0];
    @endphp
        <div class="items">
            <div class="itemsTop">
                <a href="" class="sellerName">Seller Name</a>
                @if($cartItems->status == 'receive')
                <p>To Receive</p>
                @elseif ($cartItems->status == 'pending')
                <p>Pending</p>
                @elseif ($cartItems->status == 'Cancelled')
                <p>Cancelled</p>
                @endif
            </div>
            <div class="itemsBottom">
                <div class="placeholder">
                    </div>
                <div class="itemsBottomLeft">
                    <h2 class="productName">{{$cartItems->product_name}}</h2>
                    <h3 class="amount">Quantity: {{$cartItems->quantity}}</h3>
                    <h3 class="price">P {{$cartItems->unit_price}}</h3>
                </div>
                <div class="itemsBottomRight">
                    <h2 class="totalPrice">Total Amount: P {{ ($cartItems->unit_price*$cartItems->quantity)-$cartItems->voucher_applied }}</h1>
                        <button>Cancel</button>
                </div>
            </div>
        </div>
    @endforeach
@endif
