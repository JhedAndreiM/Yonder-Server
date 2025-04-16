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
            <div class="card-left"><img src="{{ asset('images/' . $firstImage) }}" alt="wtf"></div>
            <div class="card-right">
                <h1 class="product-name">{{ $product->name }}</h1>

            </div>
            <div class="card-right-stock">
                <h3 class="product-stock">{{ $product->stock }}</h3></div>
            <div class="card-right-price">
                <h3 class="product-price">P {{ $product->price }}</h3></div>
            <div class="card-right-two">
                <a href="" class="card-edit">edit</a>
            </div>
        </div>
    </div>
    @endforeach
@endif