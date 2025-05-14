@if ($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($products as $items)
    @php
        $images = explode(',', $items->image_path);
        $firstImage = $images[0];
        
    @endphp
        <div class="card">
            @if($items->image_path)
            <div class="placeholder">
                <img src="{{ asset('images/' . $firstImage) }}" alt="{{ $items->image_path }}">
            </div>
                
            @else
            <div class="placeholder">
                <img src="{{ asset('img/default-product.png') }}" alt="No image available">
            </div>
            @endif
            <h3 class="price">P {{$items->price}}</h3>
            <h4 class="productName">{{$items->name}}</h4>
            <h4 class="stocks">Stocks: <span>{{$items->stock}}</span></h4>
            <div class="click">
                <p class="remove">Remove</p>
                <button class="card-edit" onclick="openModal(this)"
                class="edit-listing"
                data-bs-toggle="modal"
                data-bs-target="#myModal"
                data-name="{{ $items->name }}"
                data-price="{{ $items->price }}"
                data-stock="{{ $items->stock }}"
                data-id="{{ $items->product_id}}"
                data-fImage="{{ asset('images/' . $firstImage) }}"
                >edit</button>
            </div>
        </div>
    @endforeach
@endif
