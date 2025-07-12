
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
            <!-- <div class="card" onclick="hrefClick(this)">
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
            </div> -->
            
            <div class="card" onclick="hrefClick(this)">
                @if($firstImage && file_exists(public_path('images/' . $firstImage)))
                    <img alt="Product"src="{{ asset('images/' . $firstImage) }}" class="cardImg" />
                @else
                    <img src="{{ asset('img/default-product.png') }}" alt="No image available" class="cardImg">
                @endif
              <div class="info">
                <div class="price">
                  <p>P {{ number_format($product->price, 2) }}</p>
                  <img
                    src="{{ asset('img/wishlist.png') }}"
                    alt="Wishlist"
                    class="wishlist"
                  />
                </div>
                <p class="productDesc">
                  {{ $product->name }}
                </p>
                <div class="rating">
                  <img src="{{ asset('img/rating.svg') }}" alt="Star" />
                  <p class="ratingScore">4.7</p>
                  <p class="numberOfReview">(10 reviews)</p>
                </div>
              </div>
            </div>
    @endforeach

@endif
