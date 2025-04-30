@if($wishlistItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($wishlistItems as $wishlistItem)
@php
$images = explode(',', $wishlistItem->product->image_path);
$firstImage = $images[0];

@endphp
        <a id="card-link" href="{{ route('product.show', ['id' => $wishlistItem->product->product_id]) }}" class="card-link">
        <div class="card">
            <input id="cardLinkFromInput" type="hidden" value="{{ route('product.show', ['id' => $wishlistItem->product->product_id]) }}">
            @if($firstImage)
                <img class="image-placeholder"src="{{ asset('images/' . $firstImage) }}" alt="{{ $firstImage }}">
            @else
                <img class="image-placeholder"src="{{ asset('img/default-product.png') }}" alt="No image available">
            @endif
            <h3 class="price">P {{ $wishlistItem->product->price }}</h3>
            <h4 class="productName">{{ $wishlistItem->product->name }}</h4>
            <h4 class="stocks">Stocks: {{ $wishlistItem->product->stock }}</h4>
            <img class="heart-icon" src="img/heart-icon.png" alt="" />
            <i class="fa-solid fa-heart heart-icon {{ in_array($wishlistItem->product->product_id, $wishlist) ? 'red' : 'gray' }}" data-product-id="{{ $wishlistItem->product->product_id }}"></i>
          </div>
        </a>
    @endforeach
@endif