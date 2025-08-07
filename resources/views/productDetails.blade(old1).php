<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/productDetails.css')
  </head>
  <body>
    <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

      <div class="navBarMiddle">
        <div class="searchBtnImg"><img id="magnifying"class="searchBtn" src="{{ asset('img/search-icon.svg') }}" alt="" /></div>
        <div class="searchInput"><input id="searchInput" class="search" type="text" placeholder="search" /></div>
      </div>

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
        <img class="hover wishlistBtn" src="{{ asset('img/wishlist.png') }}" alt=""/>
        <img class="hover cartBtn" src="{{ asset('img/cart.png') }}" alt="" />
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li><a href="">My Profile</a></li>
        <li><a href=" ">Wishlist</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->

    <div class="container">
      <div class="productPictures"></div>
      <div class="productDetails">
        <h2>{{ $products->name }}</h2>
        <div class="detailsTop">
          <div class="detailsLeft">
            <div class="priceAndReviews">
              <p class="price">P{{ number_format($products->price, 2) }}</p>
              <img src="{{ asset('img/rating.svg') }}" alt="" />
              <p class="ratings">4.7</p>
              <a href="">(see reviews)</a>
            </div>
            @php
                        use Carbon\Carbon;
                        $created = Carbon::parse($products->created_at);
                        $diffInDays = $created->diffInDays(now());
                        $roundedValue = (int) round($diffInDays);
                    @endphp

                    @if ($roundedValue > 7)
                        <p class="product-listing">Listed more than 7 days ago</p>
                    @elseif($roundedValue === 0)
                        <p class="product-listing">Listed today</p>
                    @elseif($roundedValue === 1)
                        <p class="product-listing">Listed 1 day ago</p>
                    @else
                        <p class="product-listing">Listed {{ $roundedValue }} days ago</p>
                    @endif

                    @php
                        $pbenUser = \App\Models\User::where('email', 'pben@bpsu.edu.ph')->first();
                        $isPBEN = $pbenUser && $products->user_id === $pbenUser->id;
                    @endphp
            <p class="stocks">100 stocks</p>
          </div>
          <div class="detailsRight">
            <h3 class="qty">Quantity</h3>
            <div class="qtyButtons">
              <img src="imgs/minus.svg" alt="" />
              <p class="numberQty">2</p>
              <img src="imgs/plus.svg" alt="" />
            </div>
            <div class="buttonCartAndBuy">
              <button class="addToCart">Add to cart</button>
              <button class="buy">Buy</button>
            </div>
          </div>
        </div>

        <div class="productAttr">
          <h3>Details</h3>
          <p>Condition: New</p>
          <p>Condition: New</p>
          <p>Condition: New</p>
          <p>Condition: New</p>
        </div>

        <div class="description">
          <h3>Description</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
            est adipisci distinctio nulla, libero facilis saepe illum sunt
            doloribus soluta, obcaecati ex nesciunt laboriosam accusamus
            cupiditate commodi qui ullam exercitationem corrupti autem quod. Est
            earum aspernatur eaque pariatur placeat iusto distinctio sed
            voluptates, quibusdam facere mollitia ducimus laboriosam minus?
            Aliquam.
          </p>
        </div>

        <div class="sellerInfo">
          <div class="sellerTop">
            <h3>Seller Information</h3>
            <a href="">see profile</a>
          </div>
          <div class="profile">
            <img src="imgs/profile.png" alt="" />
            <div class="profileInfo">
              <p class="name">Jun Vincent</p>
              <p class="level">Student</p>
            </div>
            <div class="rating">
              <img src="imgs/rating.png" alt="" />
              <p>4.7</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
