@if (Auth::user()->role=='student')
@include('Chatify::layouts.headLinks')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<!-- nav bar -->
<!-- 
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
    </div> -->

    <!-- nav bar -->
    <style>
/* START OF NAVBAR */

.navBar {
  position: fixed;
  top:-10px;
  justify-content: space-between;
  align-items: center;
  display: flex;
  margin: 30px auto 40px auto;
  width: 90%;
  height: 85px;
  border-radius: 100px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  padding-left: 30px;
  padding-right: 30px;
}

.navBarRight {
  display: flex;
  align-items: center;
  gap: 40px;
}
.navBarMiddle{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 500px;
  border-radius: 15px;
  font-size: 24px;
  border-color: #d7d7d8;
  border-style: solid;
  border-width: 2px;
}
.searchBtnImg{
  flex: 1;
  display: flex;
  align-items: center;
  flex-direction: column;
}
.searchInput{
  flex: 10;
  border: none;
}
.searchInput input{
  border: none;
  width: 97%;
}
input {
  height: 50px;
  border-radius: 15px;
  border-color: #d7d7d8;
  border-style: solid;
  font-size: 24px;
}
.mainFilterButtons{
  padding: 0px;
  border: none;
  box-shadow: none;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 0px;
}
button.mainFilterButtons:hover {
  animation: none !important;
  transition: none !important;
}
input::placeholder {
  font-weight: 100;
  opacity: 50%;
}

textarea:focus,
input:focus {
  outline: none;
}
.profileBtn{
  display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-content: center;
    justify-content: center;
}
.hover,
.searchBtn {
  transition: transform 0.3s ease;
}

@keyframes bounceTilt {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-8px) rotate(-10deg);
  }
  100% {
    transform: translateY(0) rotate(0deg);
  }
}

.hover:hover,
.searchBtn:hover {
  cursor: pointer;
  animation: bounceTilt 0.4s ease-in-out;
}

.navBarRight {
  display: flex;
  align-items: center;
  gap: 40px;
}

/* for notifs */

.dropdown-container {
  position: relative;
  display: inline-block;
  z-index: 1000000 !important;
}

.notification-dropdown{
  position: absolute;
  top: 100%;
  right: -200;
  background: white;
  border: 1px solid #ccc;
  width: 300px;
  max-height: 300px;
  overflow-y: auto;
  z-index: 9910000009;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.notification .Message{
  padding-bottom: 1rem;
}
.notification h1{
  font-size: 1.5rem;
}
.profile-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  width: 250px;
  max-height: 300px;
  overflow-y: auto;
  z-index: 10000;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.notification-header {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  font-weight: bold;
  background-color: #f1f1f1;
}
.wishlist-icon{
  width: 20px;
  height: 17px;
}
.notification {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.profile-dropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.profile-dropdown li {
  padding: 10px;
  border-bottom: 1px solid #eee;
  margin: 0px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.profile-dropdown li:hover {
  background-color: #f5f5f5;
}
.profile-dropdown li a {
  text-decoration: none;
  color: #333;
  font-size: 1rem;
  font-weight: normal;
}
.navBarLeft{
  cursor: pointer;
}
/* END OF NAVBAR */
    </style>
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    
                    <a href="{{ route('custom.home') }}"><i class="fas fa-home"></i></a>
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Contacts</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section">
                <p class="messenger-title"><span>Favorites</span></p>
                <div class="messenger-favorites app-scroll-hidden"></div>
               </div>
               {{-- Saved Messages --}}
               <p class="messenger-title"><span>Your Space</span></p>
               {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
               {{-- Contact --}}
               <p class="messenger-title"><span>All Messages</span></p>
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <a href="#" class="user-name">
                        {{ config('chatify.name') }}
                    </a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>
<script>
        let subMenu = document.getElementById("subMenu");
        $(document).on('click', '#nav-profile', function() {
            console.log('clicked1');
            subMenu.classList.toggle("active");
        });
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
    document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
    </script>
@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
<!--=================== IF ORGANIZATION =========================-->
@elseif (Auth::user()->role=='organization')
@include('Chatify::layouts.headLinks')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
 <!-- nav bar -->
        <!-- <div class="navBar">
            <div class="navBarLeft">
                <img src="{{ asset('img/logo.svg') }}" alt="" />
            </div>
            <div class="navBarRight">
                <img class="hover faqsBtn" src="{{ asset('img/help.png') }}" alt="" />
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
        </div> -->
        <!-- nav bar -->
    <style>
/* START OF NAVBAR */

.navBar {
  position: fixed;
  top:-10px;
  justify-content: space-between;
  align-items: center;
  display: flex;
  margin: 30px auto 40px auto;
  width: 90%;
  height: 85px;
  border-radius: 100px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  padding-left: 30px;
  padding-right: 30px;
}

.navBarRight {
  display: flex;
  align-items: center;
  gap: 40px;
}
.navBarMiddle{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 500px;
  border-radius: 15px;
  font-size: 24px;
  border-color: #d7d7d8;
  border-style: solid;
  border-width: 2px;
}
.searchBtnImg{
  flex: 1;
  display: flex;
  align-items: center;
  flex-direction: column;
}
.searchInput{
  flex: 10;
  border: none;
}
.searchInput input{
  border: none;
  width: 97%;
}
input {
  height: 50px;
  border-radius: 15px;
  border-color: #d7d7d8;
  border-style: solid;
  font-size: 24px;
}
.mainFilterButtons{
  padding: 0px;
  border: none;
  box-shadow: none;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 0px;
}
button.mainFilterButtons:hover {
  animation: none !important;
  transition: none !important;
}
input::placeholder {
  font-weight: 100;
  opacity: 50%;
}

textarea:focus,
input:focus {
  outline: none;
}
.profileBtn{
  display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-content: center;
    justify-content: center;
}
.hover,
.searchBtn {
  transition: transform 0.3s ease;
}

@keyframes bounceTilt {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-8px) rotate(-10deg);
  }
  100% {
    transform: translateY(0) rotate(0deg);
  }
}

.hover:hover,
.searchBtn:hover {
  cursor: pointer;
  animation: bounceTilt 0.4s ease-in-out;
}

.navBarRight {
  display: flex;
  align-items: center;
  gap: 40px;
}

/* for notifs */

.dropdown-container {
  position: relative;
  display: inline-block;
  z-index: 1000000 !important;
}

.notification-dropdown{
  position: absolute;
  top: 100%;
  right: -200;
  background: white;
  border: 1px solid #ccc;
  width: 300px;
  max-height: 300px;
  overflow-y: auto;
  z-index: 9910000009;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.notification .Message{
  padding-bottom: 1rem;
}
.notification h1{
  font-size: 1.5rem;
}
.profile-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  width: 250px;
  max-height: 300px;
  overflow-y: auto;
  z-index: 10000;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.notification-header {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  font-weight: bold;
  background-color: #f1f1f1;
}
.wishlist-icon{
  width: 20px;
  height: 17px;
}
.notification {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.profile-dropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.profile-dropdown li {
  padding: 10px;
  border-bottom: 1px solid #eee;
  margin: 0px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.profile-dropdown li:hover {
  background-color: #f5f5f5;
}
.profile-dropdown li a {
  text-decoration: none;
  color: #333;
  font-size: 1rem;
  font-weight: normal;
}
.navBarLeft{
  cursor: pointer;
}
/* END OF NAVBAR */
    </style>
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    
                    <a href="{{ route('custom.home') }}"><i class="fas fa-home"></i></a>
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Contacts</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section">
                <p class="messenger-title"><span>Favorites</span></p>
                <div class="messenger-favorites app-scroll-hidden"></div>
               </div>
               {{-- Saved Messages --}}
               <p class="messenger-title"><span>Your Space</span></p>
               {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
               {{-- Contact --}}
               <p class="messenger-title"><span>All Messages</span></p>
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <a href="#" class="user-name">
                        {{ config('chatify.name') }}
                    </a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>
<script>
        let subMenu = document.getElementById("subMenu");
        $(document).on('click', '#nav-profile', function() {
            console.log('clicked1');
            subMenu.classList.toggle("active");
        });
                        // notifs
            document.addEventListener("DOMContentLoaded", function () {
                const notifBtn = document.querySelector(".notificationBtn");
                const notifDropdown = document.getElementById(
                    "notificationDropdown"
                );
                const profileBtn = document.querySelector(".profileBtn");
                const profileDropdown =
                    document.getElementById("profileDropdown");
                const closeNotif = document.querySelector(".closeButton");
                const faqsBtn = document.querySelectorAll('.faqsBtn');
                let category = "featured";

                document
                    .querySelectorAll(".mainFilterButtons")
                    .forEach((button) => {
                        button.addEventListener("click", () => {
                            // Remove 'current' from all filter buttons
                            document
                                .querySelectorAll(".mainFilterButtons")
                                .forEach((btn) => {
                                    btn.classList.remove("current");
                                });
                            let url = "?page=${page}";
                            button.classList.add("current");

                            category = button.dataset.category;
                            console.log("Clicked category:", category);
                            updateFilters();
                        });
                    });
                notifBtn.addEventListener("click", function () {
                    notifDropdown.style.display =
                        notifDropdown.style.display === "none"
                            ? "block"
                            : "none";
                    profileDropdown.style.display = "none";
                    console.log("clicked");
                });

                profileBtn.addEventListener("click", function () {
                    profileDropdown.style.display =
                        profileDropdown.style.display === "none"
                            ? "block"
                            : "none";
                    notifDropdown.style.display = "none"; // close notifications if open
                });

                // Optional: Close dropdowns if clicked outside
                window.addEventListener("click", function (e) {
                    if (!e.target.closest(".dropdown-container")) {
                        notifDropdown.style.display = "none";
                        profileDropdown.style.display = "none";
                    }
                });

                faqsBtn.forEach(button=>{
                button.addEventListener('click', function(){
                    window.location.href= "{{route('FAQs')}}";
                    
                })
            });
            });
    </script>
@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')

@endif
