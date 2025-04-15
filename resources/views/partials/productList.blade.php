@if($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($products as $product)
        <div class="card">
            <div class="image">
                <div class="img-placeholder">
                    @if($product->image_path)
                        <img src="storage/app/public/uploads/GevK4AElXBmwmRHcSPlAXOKHcQgTDgtS9LFcjNG7.jpg" alt="{{ $product->name }}">
                    @else
                        <!-- Fallback if no image -->
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
    @endforeach
@endif

<!-- Pagination (remove 'display: none' if you want it visible) -->
<div class="pagination-wrapper">
    {{ $products->links() }}
</div>