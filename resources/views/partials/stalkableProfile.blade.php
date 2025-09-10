@if ($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($products as $items)
        @php
            $firstImage = \Illuminate\Support\Facades\DB::table('product_images')
                ->where('product_id', $items->product_id)
                ->first();
        @endphp

        <div class="product-card">
            <a href="{{ route('product.show', $items->product_id) }}">
                @if ($firstImage && $firstImage->image_path)
                    <img class="placeholder" src="{{ asset('images/' . $firstImage->image_path) }}" alt="Product Image" />
                @else
                    <img class="placeholder" src="{{ asset('img/default-product.png') }}" alt="Product Image" />
                @endif
            </a>
            <p class="price">Php {{ $items->price }}</p>
            <p class="name">{{ $items->name }}</p>
        </div>
    @endforeach
@endif