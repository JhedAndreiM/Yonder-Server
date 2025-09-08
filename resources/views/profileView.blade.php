<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile view</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/profileView.css')
    @vite('resources/js/profileView.js')
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

    <section class="first">
      <div class="profile">
        <img src="{{asset('img/bannerProfile.png')}}" alt="" />
      </div>
      <div class="avatarAndRating">
        <img class="avatar" src="{{asset('storage/users-avatar/'. $user->avatar )}}" alt="" />
        <div class="rating">
          <img src="{{asset('imgs/ratingBlack.svg')}}" alt="" /> <label for="">{{ number_format($ratings->avg_rating ?? 0, 1) }}</label>
        </div>
      </div>
      <div class="nameAndButttons">
        <div class="name">
          <h1>{{$user->name}} {{$user->last_name}}</h1>
          <p>{{ ucfirst($user->role) }}</p>
        </div>
        <div class="buttons">
          <a href="{{ url('Yonder/Chat/' . $user->id) }}">
            <button  style="background-color:blue;" type="button">Message</button>
          </a>
          <button>Report</button>
        </div>
      </div>
    </section>

    <section class="second">
      <h2>Seller reviews ({{ $ratings->total_reviews}})</h2>
      <div class="reviews-container">
        @forelse($reviews as $review)
        <div class="review-card">
          <div class="review-header">
            <img class="review-avatar" src="{{asset('storage/users-avatar/'. $review->avatar)}}" alt="User Avatar" />
            <div class="review-info">
              <h3>{{$review->name}} {{$review->last_name}}</h3>
              <p class="review-date">{{ $review->formatted_date }}</p>
            </div>
          </div>
          <div class="review-stars">
            @if($review->rating == 1)
              ⭐
            @elseif($review->rating == 2)
              ⭐⭐
            @elseif($review->rating == 3)
               ⭐⭐⭐
            @elseif($review->rating == 4)
              ⭐⭐⭐⭐
            @elseif($review->rating == 5)
              ⭐⭐⭐⭐⭐
            @endif
          </div>
          <p class="review-text">
            {{ $review->comment }}
          </p>
        </div>
        @empty
          <p class="NoRating">No Reviews Available!</p>
        @endforelse
        <!-- Add more review cards here -->
      </div>
    </section>

    <section class="third">
      <h2>{{$user->name}} {{$user->last_name}}'s Listings</h2>
      <div class="top-bar">
        <div class="search-box">
          <input id="searchInput" type="text" placeholder="Search products..." data-user-id="{{ $user->id }}" />
          <span class="search-icon"
            ><img src="{{asset('img/search-icon.svg')}}" alt=""
          /></span>
        </div>

        <div class="filters">
          <select id="stockFilter">
            <option>Available & in stock</option>
            <option>Out of stock</option>
          </select>
          <select id="sortFilter">
            <option>Sort by</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
          </select>
        </div>
      </div>

      <!-- Product Grid -->
      <div class="product-grid">
        @include('partials.stalkableProfile',['products' => $products])
      </div>
    </section>
    <script>
      const scroller = document.querySelector('.reviews-container');
if (scroller) {
  scroller.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      scroller.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, { passive: false });
}
    </script>
  </body>
</html>
