@extends('Front_layouts.default')

@section('head')

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/listings.css')

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
                <a href="{{ route('profile.page') }}" >My Account</a>
                    <a href="{{ route('profileListings.page') }}" class="current">My Listings</a>
                    <a href="{{ route('vouchers.page') }}">My Vouchers</a>
            </div>
        </div>
        <div class="rightPart">
            <h2 class="yourListing">Your Listing(s):</h2>
            <div class="cardContainer">
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
                <div class="card">
                    <div class="placeholder"></div>
                    <h3 class="price">PHP 100</h3>
                    <h4 class="productName">Brand new id lace JPSME (Limited stocks)</h4>
                    <h4 class="stocks">Stocks: 100</h4>
                    <div class="click">
                        <p class="remove">Remove</p>
                        <p class="edit">Edit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
