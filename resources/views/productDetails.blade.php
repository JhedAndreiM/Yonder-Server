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
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
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
            display: none;
        }
    </style>
    @vite('resources/css/productDetails.css')
    @vite('resources/js/productDetails.js')
@endsection

@section('maincontent')
    <div class="container">
        <h1 class="goBack"><a href="{{ route('custom.home') }}"><img src="{{ asset('img/back-button.svg') }}" alt=""></a></h1>
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
                <div class="tab-buttons">
                    <button id="tabBtnDetails" class="active-tab">Details</button>
                    <button id="tabBtnOther">Reviews</button>
                </div>

                <div id="tab-details" class="tab-content active-tab-content">
                    <h1 class="product-name">{{ $products->name }}</h1>
                    <h3 class="product-price">Price:<span class="lowFontWeight">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;P
                            {{ number_format($products->price, 2) }}</span></h3>
                    @php
                        use Carbon\Carbon;
                        $created = Carbon::parse($products->created_at);
                        $diffInDays = $created->diffInDays(now());
                        $roundedValue = (int) round($diffInDays);
                    @endphp

                    @if ($roundedValue > 7)
                        <h3 class="product-listing">Listed more than 7 days ago</h3>
                    @elseif($roundedValue === 0)
                        <h3 class="product-listing">Listed today</h3>
                    @elseif($roundedValue === 1)
                        <h3 class="product-listing">Listed 1 day ago</h3>
                    @else
                        <h3 class="product-listing">Listed {{ $roundedValue }} days ago</h3>
                    @endif

                    @php
                        $pbenUser = \App\Models\User::where('email', 'pben@bpsu.edu.ph')->first();
                        $isPBEN = $pbenUser && $products->user_id === $pbenUser->id;
                    @endphp
                    <h3 class="product-stock">Stock:<span class="lowFontWeight">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ $products->stock }}</span></h3>
                    @if( $products->forSaleTrade==='trade' )
                    <button class="addToCartBtn"id="goToSellerChat">Message Seller</button>
                    @elseif($products->user_id=== Auth::id())
                    <a href="{{ route('listing.seller') }}"id="goToSellerListing"><button class="addToCartBtn"id="goToSellerListing">Edit Listing</button></a>
                    @else
                    <button class="addToCartBtn"id="addToCartBtn">Add to Cart</button>
                    <button class="addToBuyNowBtn"id="buyNowBtn">Buy Now</button>
                    @endif
                    <h1 class="product-name">Details</h1>
                    <h3 class="product-condition">Condition: <span
                            class="lowFontWeight">{{ $products->product_condition }}</span></h3>
                    <h3 class="product-colleges">Colleges: <span class="lowFontWeight">{{ $products->colleges }}</span></h3>
                    <h3 class="product-forSaleTrade">For: <span class="lowFontWeight">{{ $products->forSaleTrade }}</span>
                    </h3>
                    <h2 class="product-headerDesc">Description: </h2>
                    <h3 class="product-description">{{ $products->description }}</h3>
                    <div class="sellerInfo">
                        <hr>
                        <div class="sellerinfo-top">
                            <h2>Seller Information</h2>
                        </div>
                        <div class="sellerinfo-middle">
                            <div class="profile-name">
                                <img src="{{asset('storage/users-avatar/'. $seller->avatar)}}" alt="profile">
                                <h3>{{ $seller->name }}</h3>
                            </div>
                        </div>

                        <div class="sellerinfo-bottom">
                            <h5>Joined Younder in {{ $joinedYear }}</h5>
                        </div>
                        <div class="report-listing">
                            <button id="reportTriggerBtn" class="reportTriggerBtn" onclick="reportTriggerBtn()">Report This Product</button>
                        </div>
                    </div>
                    
                </div>
                        <div id="tab-other" class="tab-content">
                            @forelse($reviews as $review)
                            <div class="card">
                                <div class="right-card">
                                <div class="header">
                                <div class="image-placeholder">
                                    <img src="{{asset('storage/users-avatar/'. $review->avatar)}}" alt="">
                                </div>
                                
                                <div class="user-contents">
                                    <div class="header-name">
                                    <h3>{{$review->name}} {{$review->last_name}}</h3>
                                    <p>{{ $review->formatted_date }}</p>
                                </div>
                                <div class="star" style="color:gold;">
                                    @if($review->rating == 1)
                                        &starf;&#9734;&#9734;&#9734;&#9734;
                                    @elseif($review->rating == 2)
                                        &starf;&starf;&#9734;&#9734;&#9734;
                                    @elseif($review->rating == 3)
                                        &starf;&starf;&starf;&#9734;&#9734;
                                    @elseif($review->rating == 4)
                                        &starf;&starf;&starf;&starf;&#9734;
                                    @elseif($review->rating == 5)
                                        &starf;&starf;&starf;&starf;&starf;
                                    @endif
                                </div>
                                </div>
                                </div>
                                <div class="message">
                                <p>{{ $review->comment }}</p>
                                </div>
                        
                            </div>
                            
                        </div>
                        @empty
                        <p class="NoRating">No Rating Available!</p>
                        @endforelse
                            
                        
                    </div>
                <!-- END HERE -->
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
                <!-- Hidden Items Para Ma-send ko sa backend -->
                <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                <input type="hidden" name="unit_price" value="{{ $products->price }}">
                <input type="hidden" name="total_price" id="total_price_Order">
                <input type="hidden" name="quantity" id="quantity_Order">
                <input type="hidden" name="action_type" value="cart">
                <input type="hidden" name="voucher_id" id="voucherAddToCart">
                <input type="hidden" name="voucher_amount" id="voucherAddToCartAmount">
                <!----------------------------------------->
                <div class="img-placeholder"><img src="{{ asset('img/confirmation-logo.svg') }}" alt=""
                        style="width: 179px;"></div>
                <h2>{{ $products->name }}</h2>
                <p>Price per unit: ₱<span id="unitPrice">{{ number_format($products->price, 2) }}</span></p>
                <div class="quantity-div">
                    <label>Quantity:</label>
                    <input type="number" id="quantity" value="1" min="1"
                        max="{{ number_format($products->stock, 2) }}">
                </div>


                @if ($isPBEN)
                    <div class="voucher-div">
                        <label>Apply Voucher</label>
                        <select id="voucher" name="voucher_id" onchange="myFunction(event)">
                            <option disabled selected>No Voucher</option>
                            @foreach ($availableVouchers as $voucher)
                                <option value="{{ $voucher->id }}" data-amount="{{ $voucher->amount }}">
                                    ₱{{ number_format($voucher->amount, 2) }} Off
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <p>Total: ₱<span id="totalPrice"></span></p>
                <div class="btnGroup">
                    <button onclick="submitAction('cart')">Confirm Add to Cart</button>
                    <a class="close-btn" href="">Close</a>

                </div>

            </div>
        </div>
    </form>



    <!-- MODAL TO GUYS PERO PANG BUY NOW-->
    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <div class="modal hidden" id="buyModal">
            <div class="modal-blur-background"></div>
            <div class="modalContent">
                <!-- Hidden Items Para Ma-send ko sa backend -->
                <input type="hidden" name="product_id" value="{{ $products->product_id }}">
                <input type="hidden" name="unit_price" value="{{ $products->price }}">
                <input type="hidden" name="total_price" id="total_price_BuyNow">
                <input type="hidden" name="quantity" id="quantity_BuyNow">
                <input type="hidden" name="action_type" value="buy_now">
                <input type="hidden" name="voucher_id" id="voucherBuyNow">
                <input type="hidden" name="voucher_amount" id="voucherBuyNowAmount">
                <!----------------------------------------->
                <div class="img-placeholder"><img src="{{ asset('img/confirmation-logo.svg') }}" alt=""
                        style="width: 179px;"></div>
                <h2>{{ $products->name }}</h2>
                <p>Price per unit: ₱<span id="unitPriceBuy">{{ number_format($products->price, 2) }}</span></p>
                <div class="quantity-div">
                    <label>Quantity:</label>
                    <input type="number" id="quantityBuy" value="1" min="1">
                </div>


                @if ($isPBEN)
                    <div class="voucher-div">
                        <label>Apply Voucher</label>
                        <select id="voucherBuySelect" name="voucher_id" onchange="myFunctionBuy(event)">
                            <option disabled selected>No Voucher</option>
                            @foreach ($availableVouchers as $voucher)
                                <option value="{{ $voucher->id }}" data-amount="{{ $voucher->amount }}">
                                    ₱{{ number_format($voucher->amount, 2) }} Off
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <p>Total: ₱<span id="totalPriceBuy"></span></p>
                <div class="btnGroup">
                    <button onclick="submitAction('cart')">Confirm Buy Now</button>
                    <a class="close-btn-buy" href="">Close</a>

                </div>
            </div>
        </div>

    </form>


    <!-- modal to para sa mga pop ups like error and success -->
    @if (session('Failed'))
        <div class="modal" id="FailedModal">
            <div class="modal-blur-background"></div>
            <div class="modalContentFailed">
                <div class="top">
                    <div class="errorIcon"><img src="{{ asset('img/ErrorIcon.svg') }}" alt="profile"></div>
                </div>
                <div class="middle">
                    <h1>Oops!</h1>
                    <h5>It seems that this product is already in your cart. Check out you cart and see the product.</h5>
                </div>
                <div class="bottom">
                    <button onclick="closeFailedModal()">Okay!</button>
                </div>


            </div>
        </div>
        </div>
    @endif

    @if (session('success'))
        <div class="modal" id="SuccessModal">
            <div class="modal-blur-background"></div>
            <div class="modalContentSuccess">
                <div class="top-success">
                    <div class="errorIcon"><img src="{{ asset('img/SuccessIcon.svg') }}" alt="profile"></div>
                </div>
                <div class="middle-success">
                    <h1>Success!</h1>
                    <h5>Product added to your cart!</h5>
                </div>
                <div class="bottom-success">
                    <button onclick="closeSuccessModal()">Okay!</button>
                </div>
            </div>
        </div>
        </div>
    @endif
    <!-- MODAL FOR REPORTS -->
    <div id="myModalReport" class="modalReport">
        <div class="modal-contentReport">
            <h3>Report This Product</h3>
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="report_id" value="{{ $products->product_id }}" >
            <textarea name="message" placeholder="Write your report message here..." rows="5" required></textarea>
            <div class="report-buttons">
                <button type="submit" class="submitReportBtn">Submit Report</button>
                <button type="button" class="cancelReportBtn" onclick="closeReportModal()">Cancel</button>
            </div>
        </form>
        </div>
    </div>
    <script>
        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = x.length
            }
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            x[slideIndex - 1].style.display = "block";
        }

        function myFunction(e) {
            const selectedVoucherId = e.target.value;
            document.getElementById("voucherAddToCart").value = selectedVoucherId;
            console.log('Selected voucher id:', selectedVoucherId);
        }

        function myFunctionBuy(e) {
            const selectedVoucherIdBuy = e.target.value;
            document.getElementById("voucherBuyNow").value = selectedVoucherIdBuy;
            console.log('Selected voucher id:', selectedVoucherIdBuy);
        }
        const failedModal = document.getElementById("FailedModal");

        function closeFailedModal() {
            failedModal.classList.add("hidden");
        }
        const successModal = document.getElementById("SuccessModal");

        function closeSuccessModal() {
            successModal.classList.add("hidden");
        }
        // tabs
        document.getElementById("tabBtnDetails").addEventListener("click", function() {
            document.getElementById("tab-details").classList.add("active-tab-content");
            document.getElementById("tab-other").classList.remove("active-tab-content");

            this.classList.add("active-tab");
            document.getElementById("tabBtnOther").classList.remove("active-tab");
        });

        document.getElementById("tabBtnOther").addEventListener("click", function() {
            document.getElementById("tab-other").classList.add("active-tab-content");
            document.getElementById("tab-details").classList.remove("active-tab-content");

            this.classList.add("active-tab");
            document.getElementById("tabBtnDetails").classList.remove("active-tab");
        });

        // link to papunta sa chat ni seller
        document.getElementById('goToSellerChat').addEventListener('click', function () {
        window.location.href = "{{ url('/Yonder/Chat/' . $seller->id) }}";
        });

        

        // for report modal 
        var modal = document.getElementById("myModalReport");
        var btn = document.getElementById("reportTriggerBtn");
        var span = document.getElementsByClassName("cancelReportBtn")[0];
        function reportTriggerBtn() {
            document.getElementById('myModalReport').style.display = 'flex';
        }
        function closeReportModal() {
            document.getElementById('myModalReport').style.display = 'none';
        }

    </script>
@endsection
