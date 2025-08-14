@php
    $seller = $products->user;
    $joinedYear = \Carbon\Carbon::parse($seller->created_at)->year;
    $role = $seller->role;
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/productDetails.css')
    @vite('resources/js/productDetails.js')
  </head>
  <body>
    <!-- nav bar -->
    <div class="navBar">
      <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

      <div class="navBarRight">
        <img class="hover" src="{{ asset('img/help.png') }}" alt="" />
        <div class="dropdown-container">
    <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="" />
    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
      <div class="notification-header">
        <h3>Notifications</h3>
      </div>
      <div class="notification-list">
        @if ($notifications->isEmpty())
          <p style="padding-left:10px;">No notifications</p>
        @else
          @foreach ($notifications as $notification)
            <div class="notification">
              <div class="title">
                <h1>
                  @if($notification['title'] === "Product Approved")
                    <span style="color:Green;">{{ $notification['title'] }}</span>
                  @elseif($notification['title'] === "Product Rejected")
                    <span style="color:red;">{{ $notification['title'] }}</span>
                  @else
                    {{ $notification['title'] }}
                  @endif
                </h1>
              </div>
              <div class="Message">{{ $notification['message'] }}</div>
              <div class="time">{{ $notification['time_ago'] }}</div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
        <img class="hover wishlistBtn" src="{{ asset('img/wishlist.png') }}" alt=""/>
        <img class="hover cartBtn" src="{{ asset('img/cart.png') }}" alt="" />
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li><a href="">My Profile</a></li>
        <li><a href=" ">Wishlist</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->

    <div class="floating">
      <img src="imgs/message.png" alt="" />
    </div>

    <div class="container">
      <div class="productPictures">
        <div class="sliderWrapper">
         @php
            $images = \Illuminate\Support\Facades\DB::table('product_images')
                        ->where('product_id', $products->product_id)
                        ->get();
        @endphp
        @if ($images->count() > 1)
        <button class="prevBtn" onclick="prevImage()">❮</button>
        @endif

        <div class="sliderImages">
            @foreach ($images as $index => $img)
                <img src="{{ asset('images/' . $img->image_path) }}" 
                     class="sliderImage {{ $index === 0 ? 'active' : '' }}"
                     alt="Product Image">
            @endforeach
        </div>
        @if ($images->count() > 1)
        <button class="nextBtn" onclick="nextImage()">❯</button>
        @endif
    </div>
      </div>
      <div class="productDetails">
        <h2>{{ $products->name }}</h2>
        <div class="detailsTop">
          <div class="detailsLeft">
            <div class="priceAndReviews">
              <p class="price">P{{ number_format($products->price, 2) }}</p>
              <img src="{{ asset('img/rating.svg') }}" alt="" />
              <p class="ratings">
                @if(isset($globalRatings[$products->product_id]))
                {{ number_format($globalRatings[$products->product_id]->avg_rating, 1) }}
              @else
                  0
              @endif
              </p>
              <a href="#" class="seeReviews">(see reviews)</a>

              <!-- Popup -->
              <div class="popup" id="popup">
                <div class="popup-content">
                  <div class="popup-header">
                    <h3>Reviews</h3>
                    <span class="close-btn">×</span>
                  </div>

                  <div class="reviews-container">
                    <!-- Review Item -->
                    
                  @forelse($reviews as $review)
                      <div class="review">
                      <img
                        src="{{asset('storage/users-avatar/'. $review->avatar)}}"
                        alt="Profile"
                        class="profile-img"
                      />
                      <div class="review-details">
                        <div class="review-header">
                          <p class="name">{{$review->name}} {{$review->last_name}}</p>
                          <p class="date">{{ $review->formatted_date }}</p>
                        </div>
                        <p class="review-text">
                          {{ $review->comment }}
                        </p>
                        <span class="see-more">See more</span>
                      </div>
                      <div class="review-rating">
                        @if($review->rating == 1)
                                        ⭐
                                    @elseif($review->rating == 2)
                                        ⭐⭐
                                    @elseif($review->rating == 3)
                                        ⭐⭐⭐
                                    @elseif($review->rating == 4)
                                        ⭐⭐⭐⭐
                                    @elseif($review->rating == 5)
                                        ⭐⭐⭐⭐⭐
                                    @endif
                      </div>
                    </div>
                  @empty
                  <p class="NoRating">No Rating Available!</p>
                  @endforelse

                  </div>
                </div>
              </div>
            </div>
                    @php
                        use Carbon\Carbon;
                        $created = Carbon::parse($products->created_at);
                        $diffInDays = $created->diffInDays(now());
                        $roundedValue = (int) round($diffInDays);
                    @endphp

                    @if ($roundedValue > 7)
                        <p class="dateListed">Listed more than 7 days ago</p>
                    @elseif($roundedValue === 0)
                        <p class="dateListed">Listed today</p>
                    @elseif($roundedValue === 1)
                        <p class="dateListed">Listed 1 day ago</p>
                    @else
                        <p class="dateListed">Listed {{ $roundedValue }} days ago</p>
                    @endif

                    @php
                        $pbenUser = \App\Models\User::where('email', 'pben@bpsu.edu.ph')->first();
                        $isPBEN = $pbenUser && $products->user_id === $pbenUser->id;
                    @endphp
            <p class="stocks" id="mainStockDisplay">Stocks: {{ $products->stock }}</p>
          </div>
          <div class="detailsRight">
            @if($products->forSaleTrade==='trade')
            @else
            <h3 class="qty">Quantity</h3>
            <div class="qtyButtons">
              <img src="{{ asset('img/minus.svg') }}" alt="" id="qtyMinus"/>
              <input type="number" id="qtyDisplay" class="numberQty" value="1" min="1"  max="{{ $products->stock }}" inputmode="numeric" style="height:30px;width: 60px; text-align: center;">
              <img src="{{ asset('img/plus.svg') }}" alt="" id="qtyPlus"/>
            </div>
            @endif
            @if($products->forSaleTrade==='trade')
            <a href="{{ url('/Yonder/Chat/'.$seller->id) }}" class="addToCartBtn">Message Seller</a>
            @elseif($products->user_id=== Auth::id())
            <a href="{{ route('listing.seller') }}"id="goToSellerListing"><button class="addToCartBtn" id="goToSellerListing">Edit Listing</button></a>
            @else
            <div class="buttonCartAndBuy">
              <button class="addToCart" id="addToCart">Add to cart</button>
              <button class="buy" id="buyNow">Buy Now</button>
            </div>
            @endif
          </div>
        </div>
        @if($products->forSaleTrade==='trade')
        @else
        <div class="paymentMethod">
          {{ session('error')}}
          <h3>Payment Method</h3>
          <select id="payment" name="payment">
            <option value="onlinePayment">Online Payment</option>
            <option value="cashPayment">Cash Payment</option>
          </select>
        </div>
        @endif
        @php
            $variants = $products->variants;
            $hasVariants = !empty($variants) && isset($variants['options']) && count($variants['options']) > 0;
        @endphp
        
        @if ($hasVariants)
            <div class="variations">
                <h3>Variations ({{ $variants['name'] ?? 'Options' }})</h3>
                <div class="variationsAndStocks">
                    <div class="varationsGroup">
                        @foreach ($variants['options'] as $index => $option)
                            <button class="variationsButton {{ $index === 0 ? 'active' : '' }}" 
                                    data-index="{{ $index }}"
                                    data-stock="{{ $variants['optionStocks'][$index] ?? 0 }}">
                                {{ $option }}
                            </button>
                        @endforeach
                    </div>
                    <div class="varationsStocks">
                        <p>Stocks: <span id="variantStock">{{ $variants['optionStocks'][0] ?? 0 }}</span></p>
                    </div>
                </div>
            </div>
            
            <!-- Hidden inputs for variant data -->
            <script>
                window.productVariants = @json($variants);
                window.hasVariants = true;
            </script>
        @else
            <script>
                window.hasVariants = false;
                window.productVariants = null;
            </script>
        @endif

        <div class="productAttr">
          <h3>Details</h3>
          @if($products->product_condition)
          <p>Product Condition: {{ ucfirst($products->product_condition) }}</p>
          @endif
          @if($products->forSaleTrade)
          <p>Transaction Type: {{ ucfirst($products->forSaleTrade) }}</p>
          @endif
          @if($products->colleges)
          <p>Colleges: {{ strtoupper($products->colleges) }}</p>
          @endif
        </div>

        <div class="description">
          <h3>Description</h3>
          <p>
            {!! $products->description !!}
          </p>
        </div>

        <div class="sellerInfo">
          <div class="sellerTop">
            <h3>Seller Information</h3>
            <a class="seeProfile" href="">see profile</a>
          </div>
          <div class="profile">
            <img src="{{asset('storage/users-avatar/'. $seller->avatar)}}" alt="" class="sellerProfile"/>
            <div class="profileInfo">
              <p class="name">{{ $seller->name }}</p>
              <p class="level">{{ ucfirst($role) }}</p>
            </div>
            <div class="rating">
              <img src="{{ asset('img/rating.svg') }}" alt="" />
              <p>{{ number_format($sellerRating->avg_rating, 1) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CARTCOTRNOLLER controller nito -->
    <!-- UNIFIED MODAL FOR BOTH ADD TO CART AND BUY NOW -->
    <form id="orderForm" action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="modal hidden" id="orderModal">
            <div class="modal-blur-background"></div>
            <div class="modalContent">
                <!-- Hidden inputs for backend -->
                <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                <input type="hidden" name="unit_price" value="{{ $products->price }}">
                <input type="hidden" name="total_price" id="modalTotalPrice">
                <input type="hidden" name="quantity" id="modalQuantity">
                <input type="hidden" name="action_type" id="modalActionType">
                <input type="hidden" name="selected_variant" id="modalSelectedVariant">
                <input type="hidden" name="voucher_id" id="modalVoucherId">
                <input type="hidden" name="voucher_amount" id="modalVoucherAmount">
                <input type="hidden" id="paymentType" name="paymentType">

                <div class="img-placeholder">
                    <img src="{{ asset('img/confirmation-logo.svg') }}" alt="" style="width: 179px;">
                </div>
                
                <h2 class="productName">{{ $products->name }}</h2>
                <p class="productPrice">Price per unit: ₱<span id="modalUnitPrice">{{ number_format($products->price, 2) }}</span></p>
                
                <!-- Variant Selection in Modal -->
                @if ($hasVariants)
                    <div class="modal-variant-div">
                        <label>{{ $variants['name'] ?? 'Variation' }}:</label>
                        <select id="modalVariantSelect" name="variant_selection">
                            @foreach ($variants['options'] as $index => $option)
                                <option value="{{ $index }}" data-stock="{{ $variants['optionStocks'][$index] ?? 0 }}" {{ $index === 0 ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        <p class="modal-stock-info">Available: <span id="modalStockDisplay">{{ $variants['optionStocks'][0] ?? 0 }}</span></p>
                    </div>
                @endif
                
                <div class="quantity-div">
                    <label>Quantity:</label>
                    <div class="modal-qty-controls">
                        <button type="button" id="modalQtyMinus">-</button>
                        <input type="number" id="modalQuantityInput" value="1" min="1" max="{{ $hasVariants ? ($variants['optionStocks'][0] ?? 0) : $products->stock }}">
                        <button type="button" id="modalQtyPlus">+</button>
                    </div>
                </div>

                @if ($isPBEN)
                    <div class="voucher-div">
                        <label>Apply Voucher</label>
                        <select id="modalVoucherSelect" name="voucher_id">
                            <option value="">No Voucher</option>
                            @foreach ($availableVouchers as $voucher)
                                <option value="{{ $voucher->id }}" data-amount="{{ $voucher->amount }}">
                                    ₱{{ number_format($voucher->amount, 2) }} Off
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <p class="totalPrice">Total: ₱<span id="modalTotalDisplay">{{ number_format($products->price, 2) }}</span></p>
                
                <div class="btnGroup">
                    <button type="submit" id="modalConfirmBtn">Confirm</button>
                    <button type="button" class="modal-close-btn">Close</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Pass data to JavaScript -->
    <script>
        window.productData = {
            price: {{ $products->price }},
            stock: {{ $products->stock }},
            hasVariants: {{ $hasVariants ? 'true' : 'false' }},
            variants: @json($hasVariants ? $variants : null),
            isPBEN: {{ $isPBEN ? 'true' : 'false' }}
        };
    </script>
    
    <script src="productDetails.js"></script>
  </body>
</html>
