@if($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach($products as $product)
    @php
        $images = explode(',', $product->image_path);
        $firstImage = $images[0];
    @endphp
    <div>
        <div class="card">
            <div class="card-left">
                @if($firstImage && file_exists(public_path('images/' . $firstImage)))
                            <img src="{{ asset('images/' . $firstImage) }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('img/default-product.png') }}" alt="No image available">
                        @endif
            </div>
            <div class="card-right">
                <h1 class="product-name">{{ $product->name }}</h1>

            </div>
            <div class="card-right-stock">
                <h3 class="product-stock">{{ $product->stock }}</h3></div>
            <div class="card-right-price">
                <h3 class="product-price">P {{ number_format($product->price, 2) }}</h3></div>
            <div class="card-right-two">
                
                <button class="card-edit" onclick="openModal(this)"
                class="edit-listing"
                data-bs-toggle="modal"
                data-bs-target="#myModal"
                data-name="{{ $product->name }}"
                data-price="{{ $product->price }}"
                data-stock="{{ $product->stock }}"
                data-id="{{ $product->product_id}}"
                data-fImage="{{ asset('images/' . $firstImage) }}"
                >edit</button>
            </div>
        </div>
    </div>
    @endforeach
@endif