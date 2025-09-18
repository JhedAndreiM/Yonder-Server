@auth
      @if(auth()->user()->role === 'student')
<div class="navBar">
  <div class="navBarLeft" id="logoClick">
    <img src="{{ asset('img/logo.svg') }}" alt="" />
  </div>

  <div class="navBarRight">
    <img class="hover faqsBtn" src="{{ asset('img/help.png') }}" alt="" />

    <!-- Notifications -->
    <div class="dropdown-container">
      <div id="notif-icon" class="notif-bell">
        <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="Notifications" />
        @if(!empty($unreadCount) && $unreadCount > 0)
          <span class="notif-badge">{{ $unreadCount }}</span>
        @endif
      </div>

      <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-header">
          <h3>Notifications</h3>
        </div>

        <div class="notification-list">
          @if ($notifications->isEmpty())
            <p style="padding-left:10px;">No notifications</p>
          @else
            @foreach ($notifications as $notification)
              <div class="notification {{ $notification['is_read'] ? '' : 'unread' }}">
                <div class="notification-content">
                  <h1>
                    @if($notification['title'] === "Product Approved")
                      <span style="color:Green;">{{ $notification['title'] }}</span>
                    @elseif($notification['title'] === "Product Rejected")
                      <span style="color:red;">{{ $notification['title'] }}</span>
                    @else
                      {{ $notification['title'] }}
                    @endif
                  </h1>
                  <div class="Message">{{ $notification['message'] }}</div>
                </div>
                <div class="notification-time">{{ $notification['time_ago'] }}</div>
              </div>

              @if($loop->iteration == 10)
                <div class="see-more-btn" id="see-more-btn" data-offset="10">See More</div>
              @endif
            @endforeach
          @endif
        </div>
      </div>
    </div>

    <!-- Wishlist -->
    <a href="{{ route('show.wishlist') }}">
      <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="Wishlist"/>
    </a>

    <!-- Cart -->
    <a href="{{ route('show.cart') }}">
      <img class="hover" src="{{ asset('img/cart.png') }}" alt="Cart"/>
    </a>

    <!-- Profile -->
    <div class="dropdown-container">
      <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="Profile" />
      <div class="profile-dropdown" id="profileDropdown" style="display: none;">
        <ul>
          <li data-url="{{ route('student.profile') }}">
            <span class="icon"><i class="fa-solid fa-user"></i></span>
            <span class="label">My Profile</span>
            <span class="chevron">›</span>
          </li>
          <li data-url="{{ route('account.page') }}">
            <span class="icon"><i class="fa-solid fa-gear"></i></span>
            <span class="label">Settings</span>
            <span class="chevron">›</span>
          </li>
          <li data-url="{{ route('logout') }}">
            <span class="icon"><i class="fa-solid fa-door-open"></i></span>
            <span class="label">Logout</span>
            <span class="chevron">›</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
    <!-- nav bar -->
@elseif(auth()->user()->role === 'organization')
<div class="navBar">
  <div class="navBarLeft" id="logoClick">
    <img src="{{ asset('img/logo.svg') }}" alt="" />
  </div>

  <div class="navBarRight">
    <img class="hover faqsBtn" src="{{ asset('img/help.png') }}" alt="" />

    <!-- Notifications -->
    <div class="dropdown-container">
      <div id="notif-icon" class="notif-bell">
        <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="Notifications" />
        @if(!empty($unreadCount) && $unreadCount > 0)
          <span class="notif-badge">{{ $unreadCount }}</span>
        @endif
      </div>

      <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-header">
          <h3>Notifications</h3>
        </div>

        <div class="notification-list">
          @if ($notifications->isEmpty())
            <p style="padding-left:10px;">No notifications</p>
          @else
            @foreach ($notifications as $notification)
              <div class="notification {{ $notification['is_read'] ? '' : 'unread' }}">
                <div class="notification-content">
                  <h1>
                    @if($notification['title'] === "Product Approved")
                      <span style="color:Green;">{{ $notification['title'] }}</span>
                    @elseif($notification['title'] === "Product Rejected")
                      <span style="color:red;">{{ $notification['title'] }}</span>
                    @else
                      {{ $notification['title'] }}
                    @endif
                  </h1>
                  <div class="Message">{{ $notification['message'] }}</div>
                </div>
                <div class="notification-time">{{ $notification['time_ago'] }}</div>
              </div>

              @if($loop->iteration == 10)
                <div class="see-more-btn" id="see-more-btn" data-offset="10">See More</div>
              @endif
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <!-- Profile -->
    <div class="dropdown-container">
      <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="Profile" />
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
 @endif
 @endauth
     @guest
 <nav class="navbar navbar-expand-lg py-3">
          <div class="container-fluid px-4">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{url('/')}}">
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