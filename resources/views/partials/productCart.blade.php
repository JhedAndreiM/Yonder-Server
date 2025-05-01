@if($cartItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($cartItems as $items)
    @php
        $images = explode(',', $items->image_path);
        $firstImage = $images[0];
        
    @endphp
    <div class="card">
        <div class="card-image">
            @if($items->image_path)
            <div class="image-placeholder">
                <img src="{{ asset('images/' . $firstImage) }}" alt="{{ $items->image_path }}">
            </div>
                
            @else
            <div class="image-placeholder">
                <img src="{{ asset('img/default-product.png') }}" alt="No image available">
            </div>
            @endif
        </div>
        <div class="card-details">
            <h2>{{ $items->product_name }}</h1>
            <div class="div-quantity">
                <h4>Quantity: </h4>
                <p>{{ $items->quantity }}</p>
            </div>
            <div class="div-price">
                <h4>Total Price: </h4>
                <p>P {{ ($items->unit_price*$items->quantity)-$items->voucher_applied }}</p>
            </div>
            @if($items->voucher_applied==0)
            @else
            <div class="div-voucher">
                <h4>Voucher Amount: </h4>
                <p>P {{ $items->voucher_applied }}</p>
            </div>
            @endif
        
        </div>
        <div class="card-functions">
            <form action="{{ route('cart.buy', $items->cart_id) }}" method="POST">
                @csrf
                <button type="submit" class="buy-button">Buy Now</button>
            </form>
            <form action="{{ route('cart.destroy', $items->cart_id) }}" method="POST" onsubmit="return confirm('Remove this item from cart?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="remove-button">Remove From Cart</button>
            </form>
        </div>
    </div>
    @endforeach
@endif