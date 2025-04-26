@if($wishlistItems->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($wishlistItems as $wishlistItem)
    
        <div class="card">
            <div class="placeholder"></div>
            <h3 class="price">P {{ $wishlistItem->product->price }}</h3>
            <h4 class="productName">{{ $wishlistItem->product->name }}</h4>
            <h4 class="stocks">Stocks: {{ $wishlistItem->product->stock }}</h4>
            <img class="heart-icon" src="img/heart-icon.png" alt="" />
          </div>
        
    @endforeach
@endif