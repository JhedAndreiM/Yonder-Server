@auth
      @if(auth()->user()->role === 'student')
<div class="navBar">
  <div class="navBarLeft" id="logoClick">
    <img src="{{ asset('img/YonderLogo.svg') }}" alt="" />
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
      <img class="hover" src="{{ asset('img/shoppingCart.svg') }}" alt="Cart"/>
    </a>

    <!-- Profile -->
    <div class="dropdown-container">
      <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="Profile" />
      <div class="profile-dropdown" id="profileDropdown" style="display: none;">
        <ul>
          <li data-url="{{ route('stalk.profile', Auth::id()) }}">
            <span class="icon"><i class="fa-solid fa-user"></i></span>
            <span class="label">My Profile</span>
            <span class="chevron">›</span>
          </li>
          <li data-url="{{ route('student.profile') }}">
            <span class="icon"><i class="fa-solid fa-wallet"></i></span>
            <span class="label">My Purchases</span>
            <span class="chevron">›</span>
          </li>
          <li data-url="{{ route('listing.seller') }}">
            <span class="icon"><i class="fa-solid fa-store"></i></span>
            <span class="label">My Shop</span>
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
  <img src="{{ asset('img/YonderLogo.svg') }}" alt="" />
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
 @elseif(auth()->user()->role === 'admin')
    <!-- nav bar -->
    <div class="navBar">
  <div class="navBarLeft" id="logoClick">
  <img src="{{ asset('img/YonderLogo.svg') }}" alt="" />
  </div>

  <div class="navBarRight">
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
            @php
              $groupedNotifs = [];
              $consecutiveGroup = [];
              $currentTitle = null;

              foreach ($notifications as $index => $notif) {
                  $groupableTitle = in_array($notif['title'], ["Product Approval", "User Report", "Product Report"]);
                  
                  // If this is a groupable notification and it's unread
                  if ($groupableTitle && !$notif['is_read']) {
                      // If we have a previous notification and it matches the current one
                      if ($currentTitle === $notif['title'] && !empty($consecutiveGroup)) {
                          $consecutiveGroup[] = $notif;
                      } else {
                          // If we had a previous group, add it to results
                          if (!empty($consecutiveGroup)) {
                              if (count($consecutiveGroup) > 1) {
                                  $groupedNotifs[] = [
                                      'type' => 'group',
                                      'title' => $currentTitle,
                                      'count' => count($consecutiveGroup),
                                      'time_ago' => $consecutiveGroup[0]['time_ago']
                                  ];
                              } else {
                                  // If only one notification, add it as single
                                  $groupedNotifs[] = array_merge($consecutiveGroup[0], ['type' => 'single']);
                              }
                          }
                          // Start new group
                          $consecutiveGroup = [$notif];
                          $currentTitle = $notif['title'];
                      }
                  } else {
                      // If we had a previous group, add it to results
                      if (!empty($consecutiveGroup)) {
                          if (count($consecutiveGroup) > 1) {
                              $groupedNotifs[] = [
                                  'type' => 'group',
                                  'title' => $currentTitle,
                                  'count' => count($consecutiveGroup),
                                  'time_ago' => $consecutiveGroup[0]['time_ago']
                              ];
                          } else {
                              // If only one notification, add it as single
                              $groupedNotifs[] = array_merge($consecutiveGroup[0], ['type' => 'single']);
                          }
                          $consecutiveGroup = [];
                      }
                      // Add current notification as single
                      $groupedNotifs[] = array_merge($notif, ['type' => 'single']);
                      $currentTitle = null;
                  }
              }
              
              // Handle any remaining group
              if (!empty($consecutiveGroup)) {
                  if (count($consecutiveGroup) > 1) {
                      $groupedNotifs[] = [
                          'type' => 'group',
                          'title' => $currentTitle,
                          'count' => count($consecutiveGroup),
                          'time_ago' => $consecutiveGroup[0]['time_ago']
                      ];
                  } else {
                      $groupedNotifs[] = array_merge($consecutiveGroup[0], ['type' => 'single']);
                  }
              }
            @endphp

            @foreach ($groupedNotifs as $index => $notification)
              @if($notification['type'] === 'group')
                <div class="notification unread">
                  <div class="notification-content">
                    <h1>{{ $notification['title'] }}</h1>
                    <div class="Message">
                      @if($notification['title'] === 'Product Approval')
                        {{ $notification['count'] }} products are currently waiting for approval.
                      @elseif($notification['title'] === 'User Report')
                        {{ $notification['count'] }} new user reports awaiting for review.
                      @elseif($notification['title'] === 'Product Report')
                        {{ $notification['count'] }} new product reports awaiting for review.
                      @endif
                    </div>
                  </div>
                  <div class="notification-time">{{ $notification['time_ago'] }}</div>
                </div>
              @else
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
              @endif

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
 @endif
 @endauth
     @guest
 <nav class="navbar navbar-expand-lg py-3">
          <div class="container-fluid px-4">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{url('/')}}">
              <img src="img/YonderLogo.svg" alt="Logo" height="40" />
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
                  href="{{ route('login.form') }}"
                  class="btn rounded-pill px-4"
                  >Login</a
                >
              </div>
            </div>
          </div>
        </nav>
    @endguest