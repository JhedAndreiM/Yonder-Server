@if($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach($products as $product)
<div class="card">
    <div class="card-left">
        @if($product->image_path && file_exists(public_path('images/' . $product->image_path)))
            <img src="{{ asset('images/' . $product->image_path) }}" alt="{{ $product->name }}">
        @else
            <img src="{{ asset('img/default-product.png') }}" alt="No image available">
        @endif

        <!-- @if($product->approved === 'yes')
            <span class="status-badge approved">Approved</span>
        @elseif($product->approved === 'not')
            <span class="status-badge pending">Pending</span>
        @elseif($product->approved === 'rejected')
            <span class="status-badge rejected">Rejected</span>
        @endif -->
    </div>

    <div class="card-center">
        <h2 class="product-name">{{ $product->name }}</h2>
        <p class="product-stock">Stock: {{ $product->stock }}</p>
    </div>

    <div class="card-right">
        <h3 class="product-price">Price per Unit : ₱{{ number_format($product->price, 2) }}</h3>
        <a href="{{ route('products.edit', $product->product_id) }}" class="btn-edit">Edit</a>
        <button class="btn-delete" data-id="{{ $product->product_id }}">Delete</button>
    </div>
</div>

    @endforeach
@endif