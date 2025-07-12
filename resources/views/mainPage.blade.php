<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/homepage.css')
    @vite('resources/js/homepage.js')
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
        <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="" />
        <img class="hover" src="{{ asset('img/cart.png') }}" alt="" />
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li><a href="">My Profile</a></li>
        <li><a href=" ">Wishlist</a></li>
        <li><a href="">Logout</a></li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->
    <div class="floating">
      <img src="{{ asset('img/add(2).svg') }}" alt="" />
      <img src="{{ asset('img/message.png') }}" alt="" />
    </div>

    <div class="container">
      <div class="filter">
        <h2>Filter</h2>
        <h3>Price</h3>
        <div class="filterBtn">
          <input id="min"class="input-min priceInput" type="number" placeholder="Min" min="0" data-filter-type="condition">
          <input id="max"class="input-max priceInput" type="number" placeholder="Max" min="0" data-filter-type="condition">
        </div>
        <h3>Colleges</h3>
        <div class="filterBtn">
          <button class="filter-btn" data-filter="ccst" data-filter-type="condition">CCST</button>
          <button class="filter-btn" data-filter="cea" data-filter-type="condition">CEA</button>
          <button class="filter-btn" data-filter="cba" data-filter-type="condition">CBA</button>
          <button class="filter-btn" data-filter="ctech" data-filter-type="condition">CTECH</button>
          <button class="filter-btn" data-filter="cahs" data-filter-type="condition">CAHS</button>
          <button class="filter-btn" data-filter="cas" data-filter-type="condition">CAS</button>
        </div>
      </div>
      <div class="content">
        <div class="textContent">
          <div class="left">
            <h3 class="current">Featured Items</h3>
            <h3 class="notCurrent">Student Organization</h3>
            <h3 class="notCurrent">Marketplace</h3>
          </div>
          <div class="right">
            <div id="sort-dropdown" class="sort-dropdown">
                    <select name="sort-by" id="sort-by">
                      <option value="" disabled selected hidden>Sort Here</option>
                      <option value="lowToHigh">Price: Low to High</option>
                      <option value="highToLow">Price: High to Low</option>
                      <option value="newFirst">Newest First</option>
                      <option value="oldFirst">Oldest First</option>
                    </select>
                  </div>
          </div>
        </div>
        <div class="items">
          <img class="banner" src="{{ asset('img/banner.png') }}" alt="" />
          <div class="cardContainer">
            <div class="card-container infinite-scroll" id="product-container">
            @include('partials.productList', ['products' => $products])
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
