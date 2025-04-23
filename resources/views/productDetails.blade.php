@php
    $seller = $products->user;
    $joinedYear = \Carbon\Carbon::parse($seller->created_at)->year;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
        .mySlides {
            display:none;
        }
    </style>
    @vite('resources/css/productDetails.css')
    @vite('resources/js/productDetails.js')
</head>
<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
            <h1 class="webName">Yonder</h2>
        <div class="left-nav">
            <img class="wishlistBtn" src="{{ asset('img/cart.svg') }}" alt="">
            <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
            <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
            <div class="vertical-line"></div>
            <img class="profile_link" src="{{ asset('img/profile-placeholder.svg') }}" alt="">
        </div>
    </header>
    <div class="container">
        <div class="left">
            <div class="left-container">
                @php
                $images = explode(',', $products->image_path);
                
                @endphp
                <div class="w3-content w3-display-container image-slider-wrapper">
                    @foreach ($images as $image)
                        <img class="mySlides" src="{{ asset('images/' . $image) }}">
                    @endforeach
                    
                    <div class="slider-btn left-btn" onclick="plusDivs(-1)">&#10094;</div> 
                    <button class="slider-btn right-btn" onclick="plusDivs(1)">&#10095;</button>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="right-container">
                <h1 class="product-name">{{ $products->name }}</h1>
                <h3 class="product-price">Price:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;P {{ number_format($products->price, 2) }}</h3>
                @php
                use Carbon\Carbon;
                $created = Carbon::parse($products->created_at);
                $diffInDays = $created->diffInDays(now());
                $roundedValue = (int) round($diffInDays);
                @endphp

                @if($roundedValue>7)
                    <h3 class="product-listing">Listed more than 7 days ago</h3>
                @elseif($roundedValue===0)
                    <h3 class="product-listing">Listed today</h3>
                @elseif($roundedValue===1)
                    <h3 class="product-listing">Listed 1 day ago</h3>
                @else
                    <h3 class="product-listing">Listed {{ $roundedValue }} days ago</h3>
                @endif

                @php
                $isPBEN = $products->user_id === 5;
                @endphp
                <h3 class="product-stock">Stock:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $products->stock }}</h3>
                <button id="addToCartBtn">Add to Cart</button>
                <button id="buyNowBtn">Buy Now</button>
                <h1 class="product-name">Details</h1>
                <h3 class="product-condition">Condition: {{ $products->product_condition }}</h3>
                <h3 class="product-colleges">Colleges: {{ $products->colleges }}</h3>
                <h3 class="product-forSaleTrade">For: {{ $products->forSaleTrade }}</h3>
                <h2 class="product-headerDesc">Description: </h2>
                <h3 class="product-description">{{ $products->description }} </h3>
                <div class="sellerInfo">
                    <hr>
                    <div class="sellerinfo-top">
                        <h2>Seller Information</h2>
                        <a href="">see profile</a>
                    </div>
                    <div class="sellerinfo-middle">
                        <div class="profile-name">
                            <img src="{{ asset('img/login-image.svg') }}" alt="profile">
                            <h3>{{ $seller->name }}</h3>
                        </div>
                        <div class="rating"><button>Rating</button></div>
                    </div>
                    
                    <div class="sellerinfo-bottom">
                        <h5>Joined Younder in {{ $joinedYear }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL TO GUYS NG BUY ADD TO CART (WORKING) -->
    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="modal hidden" id="cartModal">
            <div class="modal-blur-background"></div>
            <div class="modalContent">
                <span class="close-btn">&times;</span>
                <span>wtf</span>
                 <!-- Hidden Items Para Ma-send ko sa backend -->
                 <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                 <input type="hidden" name="unit_price" value="{{ $products->price }}">
                 <input type="number" name="total_price" id="total_price_Order">
                 <input type="hidden" name="quantity" id="quantity_Order">
                 <input type="hidden" name="action_type" value="cart">
                 <input type="hidden" name="voucher" value="20">
                 <!----------------------------------------->
                <h2>{{ $products->name}}</h2>
                <p>Condition: {{$products->product_condition}}</p>
                <p>Colleges: {{$products->colleges}}</p>
                <p>For: {{$products->forSaleTrade}}</p>
                <p>Description: {{$products->description}}</p>
                <p>Price per unit: ₱<span id="unitPrice">{{ number_format($products->price, 2) }}</span></p>
                <label>Quantity:</label>
                <input type="number" id="quantity" value="1" min="1" max="{{ number_format($products->stock, 2) }}">
    
                @if($isPBEN)
                    <label>Apply Voucher</label>
                    <select id="voucher">
                        <option value="0">No Voucher</option>
                        <option value="10">₱10 Off</option>
                        <option value="20">₱20 Off</option>
                    </select>
                    @endif
    
                    <p>Total: ₱<span id="totalPrice"></span></p>
                    <button onclick="submitAction('cart')">Confirm Add to Cart</button>
            </div>
        </div>
    </form>
    

    <!-- MODAL TO GUYS PERO PANG BUY NOW-->
    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="modal hidden" id="buyModal">
            <div class="modal-blur-background"></div>
            <div class="modalContent">
                <span class="close-btn-buy">&times;</span>
                <!-- Hidden Items Para Ma-send ko sa backend -->
                <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                <input type="hidden" name="unit_price" value="{{ $products->price }}">
                <input type="number" name="total_price" id="total_price_BuyNow">
                <input type="hidden" name="quantity" id="quantity_BuyNow">
                <input type="hidden" name="action_type" value="buy_now">
                <!----------------------------------------->
                <h2>{{ $products->name}}</h2>
                <p>Condition: {{$products->product_condition}}</p>
                <p>Colleges: {{$products->colleges}}</p>
                <p>For: {{$products->forSaleTrade}}</p>
                <p>Description: {{$products->description}}</p>
                <p>Price per unit: ₱<span id="unitPriceBuy">{{ number_format($products->price, 2) }}</span></p>
                <label>Quantity:</label>
                <input type="number" id="quantityBuy" value="1" min="1">
    
                @if($isPBEN)
                    <label>Apply Voucher</label>
                    <select id="voucherBuy">
                        <option value="0">No Voucher</option>
                        <option value="10">₱10 Off</option>
                        <option value="20">₱20 Off</option>
                    </select>
                    @endif
    
                    <p>Total: ₱<span id="totalPriceBuy"></span></p>
                    <button type="submit">Confirm Add to Cart</button>
            </div>
        </div>
    
    </form>
    
    <script>
        var slideIndex = 1;
        showDivs(slideIndex);
        
        function plusDivs(n) {
          showDivs(slideIndex += n);
        }
        
        function showDivs(n) {
          var i;
          var x = document.getElementsByClassName("mySlides");
          if (n > x.length) {slideIndex = 1}
          if (n < 1) {slideIndex = x.length}
          for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";  
          }
          x[slideIndex-1].style.display = "block";  
        }
        </script>
</body>
</html>