@if (Auth::user()->role=='student')
@include('Chatify::layouts.headLinks')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <a href="{{ route('custom.home') }}"><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
            </div>
            <div class="left-nav">
                
                <img class="cartBtn" src="{{ asset('img/cart.svg') }}" alt="">
                <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
                <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
                <div class="vertical-line"></div>
                <div class="profilePlace"><img class="profile_link"
                        src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt=""
                        id="nav-profile"></div>
                    
                <div class="sub-menu-wrapper" id="subMenu">
                    <div class="sub-menu">
                        <a href="{{ route('student.profile') }}" class="sub-menu-link">
                            <i class="fa-solid fa-user"></i>
                            <p>Profile</p>
                        </a>
                        <a href="{{ route('account.page') }}" class="sub-menu-link">
                            <i class="fa-solid fa-gear"></i>
                            <p>Accounts</p>

                        </a>
                        <a href="{{ route('logout') }}" class="sub-menu-link">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <p>Logout</p>
                        </a>
                    </div>
                </div>
            </div>

    </header>
    <style>
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            padding-top: 2rem;
            padding-bottom: 2rem;
            justify-content: center;
            align-items: center;
            font-family: "Raleway", sans-serif;
            background-color: white;
            border-bottom: solid rgb(0, 0, 0) 2px;
        }
        .profilePlace {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile_link {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-content: center;
            justify-content: center;
            border: solid 1px black;
        }
        .mobile-only {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .notificationBtn,
        .wishlistBtn,
        .vertical-line,
        .cartBtn {
            cursor: pointer;
        }
        .vertical-line {
            border-left: 1px solid grey;
            height: 60px;
            margin-left: -3px;
            top: 0;
        }
        .left-nav {
            position: fixed;
            right: 0%;
            width: 25%;
            display: flex;
            justify-content: space-evenly;
        }

        .profile_link {
            cursor: pointer;
        }
        .sub-menu-wrapper {
            position: absolute;
            top: 90%;
            right: 10%;
            width: 225px;
            display: none;
            transition: 0.5 ease;
        }
        .sub-menu-wrapper.active {
            display: block;
            transition: 0.5 ease;
        }
        .sub-menu {
            position: relative;
            background-color: white;
            padding: 20px;
            margin: 10px;
            border: solid 2px black;
            border-radius: 11px;
        }
        .sub-menu-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: black;
            margin: 12px 0;
        }
        .sub-menu-link p {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sub-menu-link i {
            width: 45px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 15px;
            padding-bottom: 15px;
            background-color: rgb(207, 207, 207);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            justify-content: center;
        }
        .sub-menu::after {
            content: "";
            position: absolute;
            top: -12px;
            right: 7%;
            width: 0;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-bottom: 12px solid black;
        }
        .webName {
            position: absolute;
            flex: 0.7;
            font-size: 2rem;
            left: 5%;
            top: 20%;
            color: #ae0505;
        }

        .profilePlace {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile_link {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-content: center;
            justify-content: center;
            border: solid 1px black;
        }
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
        document.addEventListener('DOMContentLoaded', function() {

            const wishlistButtons = document.querySelectorAll('.wishlistBtn');
            const cartButton = document.querySelectorAll('.cartBtn');
            // wishlist button
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    window.location.href = "{{ route('show.wishlist') }}";
                });
            });

            cartButton.forEach(button => {
                button.addEventListener('click', function() {
                    window.location.href = "{{ route('show.cart') }}";
                })
            })
        });
    </script>
@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
<!--=================== IF ORGANIZATION =========================-->
@elseif (Auth::user()->role=='organization')
@include('Chatify::layouts.headLinks')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <a href="{{ route('custom.home') }}"><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
            </div>
            <div class="left-nav">
                
                <div class="profilePlace"><img class="profile_link"
                        src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt=""
                        id="nav-profile"></div>
                    
                <div class="sub-menu-wrapper" id="subMenu">
                    <div class="sub-menu">
                        <a href="{{ route('accounts.page') }}" class="sub-menu-links">
                            <i class="fa-solid fa-gear"></i>
                            <p>Account</p>

                        </a>
                        <a href="{{ route('logout') }}" class="sub-menu-links">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <p>Logout</p>
                        </a>
                    </div>
                </div>
            </div>

    </header>
    <style>
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            padding-top: 2rem;
            padding-bottom: 2rem;
            justify-content: center;
            align-items: center;
            font-family: "Raleway", sans-serif;
            background-color: white;
            border-bottom: solid rgb(0, 0, 0) 2px;
        }
        .profilePlace {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile_link {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-content: center;
            justify-content: center;
            border: solid 1px black;
        }
        .mobile-only {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .notificationBtn,
        .wishlistBtn,
        .vertical-line,
        .cartBtn {
            cursor: pointer;
        }
        .vertical-line {
            border-left: 1px solid grey;
            height: 60px;
            margin-left: -3px;
            top: 0;
        }
        .left-nav {
            position: fixed;
            right: 0%;
            width: 25%;
            display: flex;
            justify-content: space-evenly;
        }

        .profile_link {
            cursor: pointer;
        }
        .sub-menu-wrapper {
            position: absolute;
            top: 90%;
            right: 10%;
            width: 225px;
            display: none;
            transition: 0.5 ease;
        }
        .sub-menu-wrapper.active {
            display: block;
            transition: 0.5 ease;
        }
        .sub-menu {
            position: relative;
            background-color: white;
            padding: 20px;
            margin: 10px;
            border: solid 2px black;
            border-radius: 11px;
        }
        .sub-menu-links {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: black;
            margin: 12px 0;
        }
        .sub-menu-links p {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sub-menu-links i {
            width: 45px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 15px;
            padding-bottom: 15px;
            background-color: rgb(207, 207, 207);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            justify-content: center;
        }
        .sub-menu::after {
            content: "";
            position: absolute;
            top: -12px;
            right: 7%;
            width: 0;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-bottom: 12px solid black;
        }
        .webName {
            position: absolute;
            flex: 0.7;
            font-size: 2rem;
            left: 5%;
            top: 20%;
            color: #ae0505;
        }

        .profilePlace {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .profile_link {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-content: center;
            justify-content: center;
            border: solid 1px black;
        }
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
        document.addEventListener('DOMContentLoaded', function() {

            const wishlistButtons = document.querySelectorAll('.wishlistBtn');
            const cartButton = document.querySelectorAll('.cartBtn');
            // wishlist button
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    window.location.href = "{{ route('show.wishlist') }}";
                });
            });

            cartButton.forEach(button => {
                button.addEventListener('click', function() {
                    window.location.href = "{{ route('show.cart') }}";
                })
            })
        });
    </script>
@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')

@endif
