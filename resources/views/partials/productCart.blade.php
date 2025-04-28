@if($cartItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($cartItems as $items)
    <div class="card">
        <div class="card-image">
            @if($items->image_path)
                <img class="image-placeholder"src="{{ asset('images/' . $wishlistItem->product->image_path) }}" alt="{{ $wishlistItem->product->name }}">
            @else
                <img class="image-placeholder"src="{{ asset('img/default-product.png') }}" alt="No image available">
            @endif
        </div>
        <div class="card-details">
            <h2>{{ $items->product_name }}</h1>
            <h4>Stocks: </h4>
            <h4>Price: </h4>
        </div>
        <div class="card-functions"></div>
    </div>
    @endforeach
@endif