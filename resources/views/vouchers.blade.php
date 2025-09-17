@extends('Front_layouts.app')

@section('title', 'My Vouchers')
@section('head')
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet"
  />
  @vite('resources/css/myVoucher.css')
@endsection
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
            <img class="notCurrent" src="{{asset('img/MyListings.svg')}}" alt="" />
            <h2 class="notCurrent">My Listings</h2>
          </div>
          <div class="myVouchers">
            <img class="current" src="{{asset('img/MyVouchers.svg')}}" alt="" />
            <h2 class="current">My Vouchers</h2>
          </div>
          <div class="mySales">
            <img class="notCurrent" src="{{asset('img/money-icon.svg')}}" alt="" />
            <h2 class="notCurrent">Seller Dashboard</h2>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="nav">
          <h1>My Voucher(s):</h1>
          <button class="redeemButton"><img src="{{asset('img/MyVouchers.svg')}}" alt="">Redeem</button>
        </div>
            @if (session('voucher_success'))
                <div class="alert alert-success">{{ session('voucher_success') }}</div>
            @endif
            @if (session('voucher_error'))
                <div class="alert alert-failed">{{ session('voucher_error') }}</div>
            @endif
        <div class="items">
          @include('partials.showVoucher', ['voucher' => $voucher])
      </div>
    </div>

    <!-- Para 'to sa popup Modal -->
      <div class="redeemModal" id="redeemModal">
        <div class="redeemModalContent">
          <h2 class="modalTitle">Redeem Vouchers</h2>
          <div class="creditBox">
            <p>Your Credits: <span id="userCredits">{{$userCredit}}</span></p>
          </div>

            <div class="voucherList">
            @foreach($voucherList as $voucher)
            <form action="{{route('redeem.vouchers')}}" method="POST">
            @csrf
              <div class="voucherCard">
                <div class="voucherInfo">
                  <h3>P {{$voucher->amount}} Amount</h3>
                  <p class="voucherCost">Cost: {{$voucher->price}} Credits</p>
                  <input type="hidden" name="voucherAmount" value="{{$voucher->amount}}">
                  <input type="hidden" name="voucherCost" value="{{$voucher->price}}">
                </div>
                <button class="redeemBtn">Redeem</button>
              </div>
            </form>
            @endforeach
            </div>

        </div>
      </div>
    <!-- eend -->
    <script>

  //  Profile Nav Bar End

  // modal redeem

  const redeemModal = document.querySelector('.redeemModal');
  const redeemModalBtn = document.querySelector('.redeemButton');
  redeemModalBtn.addEventListener('click', () => {
    console.log('wtf');
    redeemModal.style.display = "flex";
  });
  window.addEventListener('click', (e) =>{
    if(e.target === redeemModal){
      redeemModal.style.display = "none";
    }
  });

      // for links
      const myPurchases = document.querySelectorAll('.myPurchases');
      myPurchases.forEach(button =>{
        button.addEventListener('click', function() {
          window.location.href = "{{ route('student.profile') }}";
        });
      });
      const myListings = document.querySelectorAll('.myListings');
      myListings.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('listing.seller') }}";
        });
      });
      const mySales = document.querySelectorAll('.mySales');
      mySales.forEach(button =>{
        button.addEventListener('click', function() {
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
