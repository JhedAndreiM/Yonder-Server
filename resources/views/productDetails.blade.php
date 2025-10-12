@php
    $seller = $products->user;
    $joinedYear = \Carbon\Carbon::parse($seller->created_at)->year;
    $role = $seller->role;
@endphp
@extends('Front_layouts.app')

@section('title', 'Product')
@section('head')
  <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite('resources/css/productDetails.css')
    @vite('resources/js/productDetails.js')
@endsection

@section('content')
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
                     alt="Product Image"
                     onclick="openImageModal({{ $index }})">
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
                        
                        // Define variants early so we can use them in the stock display
                        $variants = $products->variants;
                        $hasVariants = !empty($variants) && isset($variants['options']) && count($variants['options']) > 0;
                    @endphp
                    @if ($hasVariants)
                        @php $firstVariantStock = $variants['optionStocks'][0] ?? 0; @endphp
                        @if($firstVariantStock == 0)
                            <p class="outOfStock" id="mainStockDisplay">Out of Stock</p>
                        @else
                            <p class="stocks" id="mainStockDisplay">Stocks: {{ $firstVariantStock }}</p>
                        @endif
                    @else
                        @if($products->stock == 0)
                            <p class="outOfStock" id="mainStockDisplay">Out of Stock</p>
                        @else
                            <p class="stocks" id="mainStockDisplay">Stocks: {{ $products->stock }}</p>
                        @endif
                    @endif
            <div class="wishlistDiv">
                                <img
                    src="{{ in_array($products->product_id, $wishlist) ? asset('img/wishlist-red.png') : asset('img/wishlist.png') }}"
                    alt="Wishlist"
                    class="wishlist-icon"
                    data-product-id="{{ $products->product_id }}"
                  />Add to Wishlist
            </div>

          </div>
          <div class="detailsRight">
            <!-- @if($products->forSaleTrade==='trade')
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
            @endif -->
            @if($products->forSaleTrade==='trade')
              @if($products->user_id=== Auth::id())
                <a href="{{ route('listing.seller') }}"id="goToSellerListing"><button class="addToCartBtn" id="goToSellerListing">Edit Listing</button></a>
              @else
                <a href="{{ url('/Yonder/Chat/'.$seller->id) }}" class="addToCartBtn">Start Trading</a>
              @endif
            @else
              @if($products->user_id=== Auth::id())
                <a href="{{ route('listing.seller') }}"id="goToSellerListing"><button class="addToCartBtn" id="goToSellerListing">Edit Listing</button></a>
              @else
                @php 
                  $hasStock = $hasVariants ? ($variants['optionStocks'][0] ?? 0) > 0 : $products->stock > 0;
                @endphp
                @if($hasStock)
                <h3 class="qty" id="qtyLabel">Quantity</h3>
                <div class="qtyButtons" id="qtyControls">
                  <img src="{{ asset('img/minus.svg') }}" alt="" id="qtyMinus"/>
                  <input type="number" id="qtyDisplay" class="numberQty" value="1" min="1"  max="{{ $hasVariants ? ($variants['optionStocks'][0] ?? 0) : $products->stock }}" inputmode="numeric" style="height:30px;width: 60px; text-align: center;">
                  <img src="{{ asset('img/plus.svg') }}" alt="" id="qtyPlus"/>
                </div>
                <div class="buttonCartAndBuy" id="purchaseButtons">
                  <button class="addToCart" id="addToCart">Add to cart</button>
                  <button class="buy" id="buyNow">Buy Now</button>
                </div>
                @endif
              @endif
            @endif
          </div>
        </div>

        
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

                </div>
            </div>
            
            <!-- Hidden inputs for variant data -->
            <script>
                window.productVariants = @json($variants);
                window.hasVariants = true;
                
                // Handle variant selection and stock display
                document.addEventListener('DOMContentLoaded', function() {
                    const variantButtons = document.querySelectorAll('.variationsButton');
                    const mainStockDisplay = document.getElementById('mainStockDisplay');
                    const variantStockContainer = document.getElementById('variantStockContainer');
                    
                    variantButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            // Remove active class from all buttons
                            variantButtons.forEach(btn => btn.classList.remove('active'));
                            // Add active class to clicked button
                            this.classList.add('active');
                            
                            // Get stock for selected variant
                            const stock = parseInt(this.getAttribute('data-stock'));
                            
                            // Update main stock display (same location as non-variant products)
                            if (stock === 0) {
                                mainStockDisplay.innerHTML = 'Out of Stock';
                                mainStockDisplay.className = 'outOfStock';
                                
                                // Hide quantity controls and purchase buttons when out of stock
                                const qtyLabel = document.getElementById('qtyLabel');
                                const qtyControls = document.getElementById('qtyControls');
                                const purchaseButtons = document.getElementById('purchaseButtons');
                                
                                if (qtyLabel) qtyLabel.style.display = 'none';
                                if (qtyControls) qtyControls.style.display = 'none';
                                if (purchaseButtons) purchaseButtons.style.display = 'none';
                            } else {
                                mainStockDisplay.innerHTML = 'Stocks: ' + stock;
                                mainStockDisplay.className = 'stocks';
                                
                                // Show quantity controls and purchase buttons when in stock
                                const qtyLabel = document.getElementById('qtyLabel');
                                const qtyControls = document.getElementById('qtyControls');
                                const purchaseButtons = document.getElementById('purchaseButtons');
                                
                                if (qtyLabel) qtyLabel.style.display = 'block';
                                if (qtyControls) qtyControls.style.display = 'flex';
                                if (purchaseButtons) purchaseButtons.style.display = 'block';
                                
                                // Update quantity input max value
                                const qtyInput = document.getElementById('qtyDisplay');
                                if (qtyInput) {
                                    qtyInput.max = stock;
                                    if (parseInt(qtyInput.value) > stock) {
                                        qtyInput.value = Math.min(parseInt(qtyInput.value), stock);
                                    }
                                }
                            }
                        });
                    });
                });
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
        </div>

        <div class="description">
          <h3>Description</h3>
          <p>
            {!! $products->description !!}
          </p>
        </div>
        <div class="report-listing">
          <button id="reportTriggerBtn" class="reportTriggerBtn" onclick="reportTriggerBtn()">Report This Product</button>
        </div>
        <div class="sellerInfo">
          <div class="sellerTop">
            <h3>Seller Information</h3>
          </div>
          <div class="profile">
            <a href="{{ route('stalk.profile', $sellerId) }}" class="profileLink">
              <img src="{{ asset('storage/users-avatar/'. $seller->avatar) }}" alt="" class="sellerProfile"/>
            </a>
            <div class="profileInfo">
              <a href="{{ route('stalk.profile', $sellerId) }}" class="profileLink">
                <p class="name">{{ $seller->name }} {{$seller->last_name}}</p>
              </a>
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
            <div class="unique-modal-content">
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

                <div id="uniqueModalHeader" class="unique-modal-header" style="background-color: #5196F0;">
                    <div class="imageWrapper" id="imageWrapper">
                        <img id="uniqueModalIcon" src="{{asset('imgModal/confirmationLogo.svg')}}" alt="icon" />
                    </div>
                </div>
                
                <h3 id="uniqueHeaderMessage">Confirmation</h3>
                
                <!-- Variant Selection in Modal -->
                <div class="content-wrapper">
                @if ($hasVariants)
                    <div class="modal-variant-div">
                        <label>{{ $variants['name'] ?? 'Variation' }}: <p class="modal-stock-info">Available: <span id="modalStockDisplay">{{ $variants['optionStocks'][0] ?? 0 }}</span></p></label>
                        <select id="modalVariantSelect" name="variant_selection">
                            @foreach ($variants['options'] as $index => $option)
                                @php $optionStock = $variants['optionStocks'][$index] ?? 0; @endphp
                                <option value="{{ $index }}" 
                                        data-stock="{{ $optionStock }}" 
                                        {{ $index === 0 ? 'selected' : '' }}
                                        {{ $optionStock == 0 ? 'disabled' : '' }}>
                                    {{ $option }}{{ $optionStock == 0 ? ' (Out of Stock)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <div class="quantity-div">
                    <label>Quantity: <p class="modal-stock-info">@if(!$hasVariants) Available: <span id="modalStockDisplay">{{ $products->stock }}</span>@endif</p></label>
                    <div class="modal-qty-controls">
                        <button type="button" id="modalQtyMinus">-</button>
                        <input type="number" id="modalQuantityInput" value="1" min="1" max="{{ $hasVariants ? ($variants['optionStocks'][0] ?? 0) : $products->stock }}">
                        <button type="button" id="modalQtyPlus">+</button>
                    </div>
                </div>

                @if ($isPBEN)
                    <div class="voucher-div">
                        <label>Apply Voucher: </label>
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
                @if($products->forSaleTrade==='trade')
                @else
                <div class="paymentMethod">
                  {{ session('error')}}
                  <label>Payment Method</label>
                  <select id="payment" name="payment">
                    <option value="onlinePayment">Gcash Payment</option>
                    <option value="cashPayment">Cash Payment</option>
                  </select>
                </div>
                @endif
                <p class="totalPrice">Total: ₱<span id="modalTotalDisplay">{{ number_format($products->price, 2) }}</span></p>
                
                <div class="btnGroup">
                    <button type="button" class="modal-close-btn">Close</button>
                    <button type="submit" id="modalConfirmBtn">Confirm</button>
                </div>
            </div>
            </div>
        </div>
    </form>
    @if (session('error'))
        <div id="errorBar" class="error-bar">
                {{session('error')}} <img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
            </div>
            <script>
                const errorbar = document.getElementById('errorBar');
                errorbar.classList.add('show');

                // Hide after 3 seconds
                setTimeout(() => {
                    errorbar.classList.remove("show");
                    setTimeout(() => bar.remove(), 400);
                }, 5000);
        </script>
        @elseif (session('success'))
        <div id="successBar" class="success-bar">
            {{session('success')}} <img src="{{ asset('imgModal/barCheckLogo.svg') }}" alt="success" class="success-icon">
        </div>
        <script>
            const bar = document.getElementById('successBar');
            bar.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                bar.classList.remove("show");
                setTimeout(() => bar.remove(), 400);
            }, 5000);
        </script>
        @endif
        <!-- MODAL FOR REPORTS -->
    <div id="reportProductModal" class="report-modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:10000; align-items:center; justify-content:center;">
      <div class="report-modal-content" style="background:#fff; border-radius:12px; width:90%; max-width:450px; box-shadow:0 8px 32px rgba(0,0,0,0.15); overflow:hidden; position:relative;">
        <div id="reportModalHeader" class="report-modal-header" style="background-color:#d9534f; padding:40px 20px 20px 20px; text-align:center; position:relative;">
          <div class="report-imageWrapper" style="width:60px; height:60px; background:#fff; border-radius:50%; margin:0 auto; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.15); position:absolute; left:50%; transform:translateX(-50%); bottom:-30px; z-index:1;">
            <img id="reportModalIcon" src="{{ asset('imgModal/cancelLogo.svg') }}" alt="icon" style="width:30px; height:30px;" />
          </div>
        </div>

        <div style="padding:40px 24px 24px 24px;">
          <h3 id="reportHeaderMessage" style="margin:0 0 8px 0; font-size:20px; font-weight:600; color:#d9534f; text-align:center;">Report Product</h3>
          <p id="reportConfirmMessage" style="margin:0 0 20px 0; color:#666; text-align:center; font-size:14px;">Tell us what happened. Your report helps keep Yonder safe.</p>

          <!-- Report Form -->
          <form id="reportProductForm" method="POST" action="{{ route('reports.store') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="report_id" value="{{ $products->product_id }}">

            <div style="margin-bottom:16px;">
              <label for="reason" style="display:block; font-weight:600; font-size:14px; margin-bottom:6px; color:#333;">Reason</label>
              <select id="reason" name="reason" required
                      style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:14px; background:#fff; box-sizing:border-box;">
                <option value="" disabled selected>Select a reason</option>
                <option value="inappropriate_content">Inappropriate content</option>
                <option value="misleading_info">Misleading information</option>
                <option value="counterfeit">Counterfeit product</option>
                <option value="spam">Spam or duplicate listing</option>
                <option value="prohibited_item">Prohibited item</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div id="detailsDiv" style="margin-bottom:20px; display:none;">
              <label for="details" style="display:block; font-weight:600; font-size:14px; margin-bottom:6px; color:#333;">Details</label>
              <textarea id="details" name="message" rows="4"
                        placeholder="Please specify your reason and provide details..."
                        style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:14px; resize:vertical; box-sizing:border-box; font-family:inherit;"></textarea>
            </div>

            <div class="report-modal-buttons" style="display:flex; gap:12px; justify-content:flex-end;">
              <button type="button" id="reportConfirmNo" class="report-modal-btn report-modal-cancel" 
                      style="padding:12px 24px; border:1px solid #ddd; background:#fff; color:#666; border-radius:8px; cursor:pointer; font-size:14px; font-weight:500; transition:all 0.2s;">Cancel</button>
              <button type="submit" id="reportConfirmYes" class="report-modal-btn report-modal-submit"
                      style="padding:12px 24px; border:none; background:#d9534f; color:#fff; border-radius:8px; cursor:pointer; font-size:14px; font-weight:500; transition:all 0.2s;">Submit Report</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- IMAGE MODAL -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <button class="image-modal-prev" onclick="navigateImage(-1)" id="modalPrevBtn" style="display: none;">&#10094;</button>
        <button class="image-modal-next" onclick="navigateImage(1)" id="modalNextBtn" style="display: none;">&#10095;</button>
        <img class="image-modal-content" id="modalImage">
    </div>
    <script>
        window.productData = {
            price: {{ $products->price }},
            stock: {{ $products->stock }},
            hasVariants: {{ $hasVariants ? 'true' : 'false' }},
            variants: @json($hasVariants ? $variants : null),
            isPBEN: {{ $isPBEN ? 'true' : 'false' }},
            images: @json($images->map(function($img) { return asset('images/' . $img->image_path); })->toArray())
        };
            document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
    // for report modal 
        function reportTriggerBtn() {
            const modal = document.getElementById('reportProductModal');
            modal.style.display = 'flex';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('reportProductModal');
            const cancelBtn = document.getElementById('reportConfirmNo');
            const reasonSelect = document.getElementById('reason');
            const detailsTextarea = document.getElementById('details');

            // Add hover effects for buttons
            const submitBtn = document.getElementById('reportConfirmYes');
            
            cancelBtn.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
                this.style.borderColor = '#adb5bd';
            });
            
            cancelBtn.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#fff';
                this.style.borderColor = '#ddd';
            });
            
            submitBtn.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#c9302c';
            });
            
            submitBtn.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#d9534f';
            });

            // Handle reason dropdown change
            reasonSelect.addEventListener('change', function() {
                const detailsDiv = document.getElementById('detailsDiv');
                if (this.value === 'other') {
                    detailsDiv.style.display = 'block';
                    detailsTextarea.required = true;
                } else {
                    detailsDiv.style.display = 'none';
                    detailsTextarea.required = false;
                    detailsTextarea.value = '';
                }
            });

            // Cancel button click
            cancelBtn.addEventListener('click', function () {
                modal.style.display = 'none';
                // Reset form
                document.getElementById('reportProductForm').reset();
                document.getElementById('detailsDiv').style.display = 'none';
                detailsTextarea.required = false;
            });

            // Close when clicking outside
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    // Reset form
                    document.getElementById('reportProductForm').reset();
                    document.getElementById('detailsDiv').style.display = 'none';
                    detailsTextarea.required = false;
                }
            });
        });

        // Image modal functions
        let currentImageIndex = 0;
        let modalImages = window.productData.images || [];

        function openImageModal(index) {
            currentImageIndex = index;
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");
            var prevBtn = document.getElementById("modalPrevBtn");
            var nextBtn = document.getElementById("modalNextBtn");

            if (modal && modalImg) {
                modal.style.display = "block";
                modalImg.src = modalImages[index];

                // Show/hide navigation buttons based on image count
                if (modalImages.length > 1) {
                    if (prevBtn) prevBtn.style.display = "block";
                    if (nextBtn) nextBtn.style.display = "block";
                } else {
                    if (prevBtn) prevBtn.style.display = "none";
                    if (nextBtn) nextBtn.style.display = "none";
                }
            }
        }

        function navigateImage(direction) {
            currentImageIndex += direction;

            // Wrap around if at the beginning or end
            if (currentImageIndex < 0) {
                currentImageIndex = modalImages.length - 1;
            } else if (currentImageIndex >= modalImages.length) {
                currentImageIndex = 0;
            }

            var modalImg = document.getElementById("modalImage");
            if (modalImg) {
                modalImg.src = modalImages[currentImageIndex];
            }
        }

        function closeImageModal() {
            var modal = document.getElementById("imageModal");
            if (modal) {
                modal.style.display = "none";
            }
        }

        // Close modal when clicking outside the image
        var imageModal = document.getElementById("imageModal");
        if (imageModal) {
            imageModal.addEventListener('click', function(event) {
                if (event.target === this) {
                    closeImageModal();
                }
            });
        }

        // Close modal with ESC key and navigate with arrow keys
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            } else if (event.key === 'ArrowLeft' && modalImages.length > 1) {
                navigateImage(-1);
            } else if (event.key === 'ArrowRight' && modalImages.length > 1) {
                navigateImage(1);
            }
        });
            document.addEventListener('click', function(event) {
        // Check if the clicked element is a wishlist icon
        if (event.target.classList.contains('wishlist-icon')) {
            event.stopPropagation();
            isHeartClicked = true;

            const img = event.target;
            const currentSrc = img.getAttribute('src');
            const grayHeart = "{{ asset('img/wishlist.png') }}";
            const redHeart = "{{ asset('img/wishlist-red.png') }}";

            img.setAttribute(
                'src',
                currentSrc.includes('wishlist-red.png') ? grayHeart : redHeart
            );

            event.preventDefault();     
            event.stopPropagation();
            console.log("clicked");
            var productId = $(img).data('product-id');
            var heart = $(img);
            
            $.ajax({
                url: "{{ route('wishlist.toggle') }}", 
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("worked");
                }
            });
        }
    });
    </script>
    
    <script src="productDetails.js"></script>
@endsection