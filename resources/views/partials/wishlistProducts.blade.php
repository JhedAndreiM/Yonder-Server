@if($wishlistItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($wishlistItems as $wishlistItem)
    
        <div class="card">
            @if($wishlistItem->product->image_path)
                <img class="image-placeholder"src="{{ asset('images/' . $wishlistItem->product->image_path) }}" alt="{{ $wishlistItem->product->name }}">
            @else
                <img class="image-placeholder"src="{{ asset('img/default-product.png') }}" alt="No image available">
            @endif
            <h3 class="price">P {{ $wishlistItem->product->price }}</h3>
            <h4 class="productName">{{ $wishlistItem->product->name }}</h4>
            <h4 class="stocks">Stocks: {{ $wishlistItem->product->stock }}</h4>
            <img class="heart-icon" src="img/heart-icon.png" alt="" />
            <i class="fa-solid fa-heart heart-icon {{ in_array($wishlistItem->product->product_id, $wishlist) ? 'red' : 'gray' }}" data-product-id="{{ $wishlistItem->product->product_id }}"></i>
          </div>
        
    @endforeach
@endif