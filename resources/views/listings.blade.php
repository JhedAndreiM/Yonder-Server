@extends('Front_layouts.app')

@section('title', 'My Listings')
@section('head')

@endsection
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
  rel="stylesheet"
  />
@vite('resources/css/myListings.css')
@section('content')
<div class="mainContainer">
      <div class="left">
        <div class="profile">
          <div class="top">
            <div class="name">
              <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
              <h3>{{Auth::user()->name .' ' . Auth::user()->last_name}}</h3>
            </div>
            <div class="ratings">
              <img class="ratingLogo" src="{{ asset('img/rating.svg') }}" alt="" />
              <p class="rating">{{ number_format($sellerRating->avg_rating, 1) }}</p>
            </div>
          </div>
          <div class="myPurchases">
            <img class="notCurrent" src="{{asset('img/MyPurchases.svg')}}" alt="" />
            <h2 class="notCurrent">My Purchases</h2>
          </div>
          <div class="myListings">
            <img class="current" src="{{asset('img/MyListings.svg')}}" alt="" />
            <h2 class="current">My Listings</h2>
          </div>
          <div class="myVouchers">
            <img class="notCurrent" src="{{asset('img/MyVouchers.svg')}}" alt="" />
            <h2 class="notCurrent">My Vouchers</h2>
          </div>
          <div class="mySales">
            <img class="notCurrent" src="{{asset('img/money-icon.svg')}}" alt="" />
            <h2 class="notCurrent">My Sales</h2>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="nav">
          <h1>My Listing(s):</h1>
        </div>
        <div class="items">
          @include('partials.myListing',['products' => $products])
        </div>
      </div>
    </div>
    <script>
        
        // for links
        const myPurchases = document.querySelectorAll('.myPurchases');
        myPurchases.forEach(button =>{
          button.addEventListener('click', function() {
            console.log('clicked');
            window.location.href = "{{ route('student.profile') }}";
          });
        });
        const myVouchers = document.querySelectorAll('.myVouchers');
        myVouchers.forEach(button =>{
            button.addEventListener('click', function() {
              console.log('clicked');
                window.location.href = "{{ route('show.vouchers') }}";
            });
        });    
        const mySales = document.querySelectorAll('.mySales');
        mySales.forEach(button =>{
        button.addEventListener('click', function() {
          console.log('clicked');
            window.location.href = "{{ route('student.sales') }}";
          });
        }); 
      document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
    </script>
@endsection
