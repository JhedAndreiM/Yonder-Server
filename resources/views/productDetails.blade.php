@php
    $seller = $products->user;
    $joinedYear = \Carbon\Carbon::parse($seller->created_at)->year;
@endphp
@extends('Front_layouts.default')

@section('head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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

@endsection

@section('maincontent')
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
                <button class="addToCartBtn"id="addToCartBtn">Add to Cart</button>
                <button class="addToBuyNowBtn"id="buyNowBtn">Buy Now</button>
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

    <!-- CartController yung Controller page nito :D -->
    <!-- MODAL TO GUYS NG BUY ADD TO CART (WORKING) -->
    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="modal hidden" id="cartModal">
            <div class="modal-blur-background"></div>
            <div class="modalContent">
                <span class="close-btn">&times;</span>
                 <!-- Hidden Items Para Ma-send ko sa backend -->
                 <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                 <input type="hidden" name="unit_price" value="{{ $products->price }}">
                 <input type="hidden" name="total_price" id="total_price_Order">
                 <input type="hidden" name="quantity" id="quantity_Order">
                 <input type="hidden" name="action_type" value="cart">
                 <input type="hidden" name="voucher_id" id="voucherAddToCart">
                 <input type="hidden" name="voucher_amount" id="voucherAddToCartAmount">
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
                    <select id="voucher" name="voucher_id" onchange="myFunction(event)">
                        <option disabled selected>No Voucher</option>
                        @foreach($availableVouchers as $voucher)
                            <option value="{{ $voucher->id }}" data-amount="{{ $voucher->amount }}">
                                ₱{{ number_format($voucher->amount, 2) }} Off
                            </option>
                        @endforeach
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
                <input type="hidden" name="total_price" id="total_price_BuyNow">
                <input type="hidden" name="quantity" id="quantity_BuyNow">
                <input type="hidden" name="action_type" value="buy_now">
                <input type="hidden" name="voucher_id" id="voucherBuyNow">
                 <input type="hidden" name="voucher_amount" id="voucherBuyNowAmount">
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
                    <select id="voucherBuySelect" name="voucher_id" onchange="myFunctionBuy(event)">
                        <option disabled selected>No Voucher</option>
                        @foreach($availableVouchers as $voucher)
                            <option value="{{ $voucher->id }}" data-amount="{{ $voucher->amount }}">
                                ₱{{ number_format($voucher->amount, 2) }} Off
                            </option>
                        @endforeach
                    </select>
                    @endif
    
                    <p>Total: ₱<span id="totalPriceBuy"></span></p>
                    <button type="submit">Confirm Add to Cart</button>
            </div>
        </div>
    
    </form>
    

    <!-- modal to para sa mga pop ups like error and success -->
    @if(session('Failed'))
    <div class="modal" id="FailedModal">
        <div class="modal-blur-background"></div>
            <div class="modalContentFailed">
                <div class="failed_modal_top"><i class="fa-regular fa-circle-xmark fa-2xl" style="color: #ffffff;"></i></div>
                <div class="failed_modal_bottom">
                    <p class="failed-text"style="color: Red;font-size: 25px;">{{ session('Failed') }}</p>
                    <span class="close-btn-failed">Continue Shopping</span>
                </div>

                
            </div>
        </div>
    </div>
    @endif
    
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
        function myFunction(e){
            const selectedVoucherId = e.target.value;
            document.getElementById("voucherAddToCart").value = selectedVoucherId;
            console.log('Selected voucher id:', selectedVoucherId);
        }
        function myFunctionBuy(e){
            const selectedVoucherIdBuy = e.target.value;
            document.getElementById("voucherBuyNow").value = selectedVoucherIdBuy;
            console.log('Selected voucher id:', selectedVoucherIdBuy);
        }
        const closeBtnFailed = document.querySelector(".close-btn-failed");
        closeBtnFailed.addEventListener("click", () => {
            console.log('test');
            FailedModal.classList.add("hidden");
            window.location.href = "{{ route('custom.home') }}";
        });
        </script>
@endsection