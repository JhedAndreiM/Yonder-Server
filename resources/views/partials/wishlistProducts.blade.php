@if($wishlistItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($wishlistItems as $wishlistItem)
@php
$images = DB::table('product_images')
        ->where('product_id', $wishlistItem->product_id)
        ->get();
        $firstImage= $images->first();

@endphp
<a id="card-link"
   @if($wishlistItem->product->stock == 0)
       style="pointer-events: none; cursor: not-allowed; opacity: 0.7; position: relative;"
       tabindex="-1"
   @else
       href="{{ route('product.show', ['id' => $wishlistItem->product->product_id]) }}"
   @endif
   class="card-link">

    <div class="card" style="position: relative;">
        @if($wishlistItem->product->stock == 0)
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 15%; background: rgba(87, 87, 87, 0.6); z-index: 2; display: flex; align-items: center; justify-content: center; border-radius: 0px 0px 8px 8px;">
                <span style="color: white;font-size: 1.5rem; font-weight: bold; letter-spacing: 1px;">Out of Stock</span>
            </div>
        @endif

        <div class="card-container">
            <input id="cardLinkFromInput" type="hidden" value="{{ route('product.show', ['id' => $wishlistItem->product->product_id]) }}">

            @if($firstImage)
                <img class="image-placeholder" src="{{ asset('images/' . $firstImage->image_path) }}" alt="Product Image">
            @else
                <img class="image-placeholder" src="{{ asset('img/default-product.png') }}" alt="No image available">
            @endif

            <h3 class="price">P {{ $wishlistItem->product->price }}</h3>
            <h4 class="productName">{{ $wishlistItem->product->name }}</h4>

            <div style="display: flex; align-items: center; gap: 8px;">
                <h4 class="stocks" style="margin: 0;">
                    Stocks: {{ $wishlistItem->product->stock }}
                </h4>
                @if($wishlistItem->product->stock <= $wishlistItem->product->critical_level && $wishlistItem->product->stock > 0)
                    <span style="color: #d9534f; font-weight: bold;">Low in stock!</span>
                @endif
            </div>

            <i class="fa-solid fa-heart heart-icon {{ in_array($wishlistItem->product->product_id, $wishlist) ? 'red' : 'gray' }}"
               data-product-id="{{ $wishlistItem->product->product_id }}"
               style="pointer-events: auto; cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 1.5rem; z-index: 5;"></i>
        </div>
    </div>
</a>
    @endforeach
@endif