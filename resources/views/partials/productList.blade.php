@foreach ($products as $product)
    <div class="card">
        <div class="image">
            <div class="img-placeholder">
                <img src="{{ asset('img/profile-placeholder.svg') }}" alt="img">
            </div>
        </div>
        <div class="price">P {{ $product->price }}</div>
        <div class="prod-name">{{ $product->name }}</div>
        <div class="rating">
            <button>4.7</button>
            <img src="{{ asset('img/heart-icon.svg') }}" alt="">
        </div>
    </div>
@endforeach

<div class="pagination-wrapper" style="display: none;">
    {{ $products->links() }}
</div>