@extends('Front_layouts.default')

@section('head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    
    
    @vite('resources/css/profile.css')

@endsection

@section('maincontent')
    <div class="mainContainer">
        <div class="top">
            <h1>Profile</h1>
        </div>
        <div class="container">
            <div class="leftPart">
                <div class="leftPartItems">
                    <div class="profilePlace_profile"><img class="profile_link_profile" src="{{ asset('storage/users-avatar/'. Auth::user()->avatar) }}" alt="" id="nav-profile"></div>
                    <h3 class="h3">{{ Auth::user()->name }}</h3>
                </div>
                <hr>
                <div class="leftPartItems2">
                    <a href="{{ route('profile.page') }}" class="current">My Account</a>
                    <a href="{{ route('profileListings.page') }}">My Listings</a>
                    <a href="{{ route('vouchers.page') }}">My Vouchers</a>
                </div>
            </div>            
            <div class="rightPart">
                <div class="categories">
                    <a href="" class="current">All</a>
                    <a href="">Pending</a>
                    <a href="">To recieve</a>
                    <a href="">Cancelled</a>
                    <a href="">Completed</a>
                </div>
                <div class="itemsContainer">
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="itemsTop">
                            <a href="" class="sellerName">Seller Name</a>
                            <p>Pending</p>
                        </div>
                        <div class="itemsBottom">
                            <div class="placeholder"></div>
                            <div class="itemsBottomLeft">
                                <h2 class="productName">Product Name</h2>
                                <h3 class="amount">Amount</h3>
                                <h3 class="price">Price</h3>
                            </div>
                            <div class="itemsBottomRight">
                                <h2 class="totalPrice">Total Amount: xxxx</h1>
                                <button>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>   
    </div>
@endsection