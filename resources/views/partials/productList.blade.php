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
        <a href="{{ route('product.show', ['id' => $product->product_id]) }}" class="card-link">
            <div class="card">
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
                    <button>4.7</button>
                    <img src="{{ asset('img/heart-icon.svg') }}" alt="Favorite">
                </div>
            </div>
        </a>
    @endforeach
@endif

<!-- Pagination (remove 'display: none' if you want it visible) -->
<div class="pagination-wrapper" display: none>
    {{ $products->links() }}
</div>