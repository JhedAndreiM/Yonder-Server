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
    @vite('resources/css/vouchers.css')
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
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
                <a href="{{ route('student.profile') }}" >My Purchases</a>
                    <a href="{{ route('listing.seller') }}">My Listings</a>
                    <a href="{{ route('show.vouchers') }}" class="current">My Vouchers</a>
                    <a href="{{ route('student.sales') }}">My Sales</a>
            </div>
        </div>
        <div class="rightPart">
            <h2 class="yourListing">Your Voucher(s):</h2>
            <div class="cardContainer">
                @include('partials.showVoucher', ['voucher' => $voucher])
                
            </div>
        </div>
    </div>
@endsection