
@if($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($products as $product)
    
    @php
        $images = explode(',', $product->image_path);
        $firstImage = $images[0];
        
    @endphp
            <div class="card" onclick="hrefClick(this)">
                <input id="cardLinkFromInput" type="hidden" value="{{ route('product.show', ['id' => $product->product_id]) }}">
                <div class="image">
                    <div class="img-placeholder">
                        @if($firstImage && file_exists(public_path('images/' . $firstImage)))
                            <img src="{{ asset('images/' . $firstImage) }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('img/default-product.png') }}" alt="No image available">
                        @endif
                    </div>
                </div>
                <div class="price">P {{ number_format($product->price, 2) }}</div>
                <div class="prod-name">{{ $product->name }}</div>
                <div class="rating">
                    @if($product->average_rating)
                    <span class="theRating">&#9733; {{number_format($product->average_rating, 1) }}</span>
                    @else
                    <span class="theRatings">&#9734; 5.0</span>
                    @endif
                    <i class="fa-solid fa-heart heart-icon {{ in_array($product->product_id, $wishlist) ? 'red' : 'gray' }}" data-product-id="{{ $product->product_id }}"></i>
                    
                </div>
            </div>
            
        
    @endforeach

<!-- Pagination (remove 'display: none' if you want yung page like < page 1 > ganyan visible) -->
<div class="pagination-wrapper">
    {{ $products->links() }}
</div>
@endif
