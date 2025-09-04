@php
    $converter = new League\CommonMark\CommonMarkConverter();
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQS</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
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
    @auth
      @if(auth()->user()->role === 'student')
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
          <li><a href="{{ route('student.profile') }}">My Profile</a></li>
          <li><a href="{{route('account.page')}}">Settings</a></li>
          <li><a href="{{ route('logout') }}">Logout</a></li>
        </ul>
      </div>
    </div>
        </div>
      </div>

      <!-- nav bar -->
      @elseif(auth()->user()->role === 'organization')
       <!-- nav bar -->
        <div class="navBar">
            <div class="navBarLeft">
                <div class="navBarLeft" id="logoClick"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>
            </div>
            <div class="navBarRight">
                <div class="dropdown-container">
                    <img
                        class="hover notificationBtn"
                        src="{{ asset('img/notif.png') }}"
                        alt=""
                    />
                    <div
                        class="notification-dropdown"
                        id="notificationDropdown"
                        style="display: none"
                    >
                        <div class="notification-header">
                            <h3>Notifications</h3>
                        </div>
                        <div class="notification-list">
                            @if ($notifications->isEmpty())
                            <p style="padding-left: 10px">No notifications</p>
                            @else @foreach ($notifications as $notification)
                            <div class="notification">
                                <div class="title">
                                    <h1>
                                        @if($notification['title'] === "Product
                                        Approved")
                                        <span style="color: Green"
                                            >{{ $notification['title'] }}</span
                                        >
                                        @elseif($notification['title'] ===
                                        "Product Rejected")
                                        <span style="color: red"
                                            >{{ $notification['title'] }}</span
                                        >
                                        @else {{ $notification['title'] }}
                                        @endif
                                    </h1>
                                </div>
                                <div class="Message">
                                    {{ $notification['message'] }}
                                </div>
                                <div class="time">
                                    {{ $notification['time_ago'] }}
                                </div>
                            </div>
                            @endforeach @endif
                        </div>
                    </div>
                </div>
                <div class="dropdown-container">
                    <img
                        class="hover profileBtn"
                        src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}"
                        alt=""
                    />
                    <div
                        class="profile-dropdown"
                        id="profileDropdown"
                        style="display: none"
                    >
                        <ul>
                            <li><a href="{{route('account.page')}}">Accounts</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- nav bar -->
      @endif
    @endauth
    @guest
 <nav class="navbar navbar-expand-lg py-3">
          <div class="container-fluid px-4">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img src="img/logo.svg" alt="Logo" height="40" />
            </a>

            <!-- Hamburger for mobile -->
            <button
              class="navbar-toggler"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#mainNavbar"
              aria-controls="mainNavbar"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="mainNavbar">
              <!-- Middle: Nav links -->
              <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 text-center">
                <li class="nav-item">
                  <a
                    class="nav-link fw-semibold"
                    aria-current="page"
                    href="{{ route('landing') }}"
                    >Home</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="{{ route('about.us') }}">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold active" href="{{ route('FAQs') }}">FAQs</a>
                </li>
              </ul>

              <!-- Right: Login -->
              <div class="d-flex">
                <a
                  href="{{ route('select.role') }}"
                  class="btn rounded-pill px-4"
                  >Login</a
                >
              </div>
            </div>
          </div>
        </nav>
    @endguest


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
      @if(auth()->check())
          @if(auth()->user()->role === 'student')
              window.location.href = "{{ route('student.dashboard') }}";
          @elseif(auth()->user()->role === 'organization')
              window.location.href = "{{ route('organization.dashboard') }}";
          @elseif(auth()->user()->role === 'admin')
              window.location.href = "{{ route('admin.dashboard') }}";
          @endif
      @else
          window.location.href = "{{ route('landing') }}";
      @endif
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
