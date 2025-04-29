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
                <img class="image-placeholder"src="{{ asset('images/' . $firstImage) }}" alt="{{ $items->image_path }}">
            @else
                <img class="image-placeholder"src="{{ asset('img/default-product.png') }}" alt="No image available">
            @endif
        </div>
        <div class="card-details">
            <h2>{{ $items->product_name }}</h1>
            <h4>Quantity:  </h4>
            <h4>Total Price: </h4>
            @if($items->voucher_applied==0)
            @else
            <h4>Voucher Amount: {{ $items->voucher_applied }}</h4>
            @endif
        
        </div>
        <div class="card-functions">
            <form action="{{ route('cart.destroy', $items->cart_id) }}" method="POST" onsubmit="return confirm('Remove this item from cart?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="remove-button">Remove From Cart</button>
            </form>
        </div>
    </div>
    @endforeach
@endif