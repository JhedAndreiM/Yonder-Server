@php
    $converter = new League\CommonMark\CommonMarkConverter();
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/FAQs.css')
    @vite('resources/js/FAQs.js')
    <style>
      body {
      background-image: url("{{ asset('img/background.svg') }}");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      }
  </style>
  </head>
  <body>
    <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>
      <div class="navBarRight">
        @auth
        <!-- Items for logged-in users -->
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
        <li><a href="{{ route('student.profile') }}">My Profile</a></li>
        <li><a href="{{route('account.page')}}">Settings</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
        @endauth

        @guest
            <a href="{{ route('select.role') }}" class="authBtn">Login</a>
        @endguest
      </div>
      </div>

    <!-- nav bar -->

    <div class="imgContainer">
      <img class="gradient" src="{{ asset('img/footer.svg') }}" alt="" />
      <div class="overlay-text">Hi, how can we help?</div>
      <div class="searchContainer">
        <input class="" type="text" placeholder="search" />
        <button class="searchButton">
          <img src="{{ asset('img/search-iconWhite.svg') }}" alt="" />
        </button>
        <div class="spinner" style="display:none;"></div>
      </div>
    </div>

    <div class="mainContainer">
    <div class="leftPart">
    @foreach($categories as $category)
    <section>
        <div class="mainCatergory">
            <h2>{{ $category->name }}</h2>
            <img class="arrow {{ $loop->first ? 'rotate' : '' }}" src="{{ asset('img/arrow.svg') }}" alt="" />
        </div>
        <div class="subQuestions" style="display: {{ $loop->first ? 'block' : 'none' }}">
            @foreach($category->faqs as $faq)
            <div class="question" data-id="{{$faq->id}}">
                <h3 class="{{ $loop->parent->first && $loop->first ? 'active' : '' }}">
                    {{ $faq->question }}
                </h3>
                <p class="answer" style="display: none;">{{ $faq->answer }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach
    </div>

      <!-- Right Part -->
      <div class="rightPart">
        <h2>What is Yonder?</h2>
        <p>
          Yonder is our university’s own online marketplace. You can buy, sell,
          and trade items safely with other students and staff.
        </p>
      </div>
    </div>

    <script src="FAQS.js"></script>
    <script>
            // notifs
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
    </script>
  </body>
</html>
