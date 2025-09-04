<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Vouchers</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/myVoucher.css')
  </head>
  <body>
 <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft" id="logoClick"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

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
        <a href="{{ route('show.wishlist') }}">
            <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="Wishlist"/>
        </a>
        <a href="{{ route('show.cart') }}">
            <img class="hover" src="{{ asset('img/cart.png') }}" alt="Cart"/>
        </a>
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li data-url="{{ route('student.profile') }}">My Profile</li>
        <li data-url="{{ route('account.page') }}">Settings</li>
        <li data-url="{{ route('logout') }}">Logout</li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->

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
            <h2 class="notCurrent">My Sales</h2>
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
        //  Profile Nav Bar
       document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.querySelector(".notificationBtn");
    const notifDropdown = document.getElementById("notificationDropdown");
    const profileBtn = document.querySelector(".profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");
    const closeNotif = document.querySelector(".closeButton");
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const cartButton = document.querySelectorAll('.cartBtn');
    let category = 'featured';

    document.querySelectorAll(".mainFilterButtons").forEach(button => {
    button.addEventListener("click", () => {
        // Remove 'current' from all filter buttons
        document.querySelectorAll(".mainFilterButtons").forEach(btn => {
            btn.classList.remove("current");
        });
        let url='?page=${page}';
        button.classList.add("current");

        category = button.dataset.category;
        console.log('Clicked category:', category);
        updateFilters();
    });
});
notifBtn.addEventListener("click", function () {
      notifDropdown.style.display = notifDropdown.style.display === "none" ? "block" : "none";
      profileDropdown.style.display = "none"; 
      console.log("clicked");
    });

    profileBtn.addEventListener("click", function () {
      profileDropdown.style.display = profileDropdown.style.display === "none" ? "block" : "none";
      notifDropdown.style.display = "none"; // close notifications if open
    });
    if(closeNotif){
    closeNotif.addEventListener("click", function () {
      notifDropdown.style.display = "none";
    });
    }
    document.getElementById('logoClick').addEventListener('click', function() {
    window.location.href = "{{ route('student.dashboard') }}";
    });
    // Optional: Close dropdowns if clicked outside
    window.addEventListener("click", function (e) {
      if (!e.target.closest(".dropdown-container")) {
        notifDropdown.style.display = "none";
        profileDropdown.style.display = "none";
      }
    });

        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });
         // cart button
        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
                
            })
        });

  });

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
  </body>
</html>
