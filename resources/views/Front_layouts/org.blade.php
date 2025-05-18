<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .menu-button {
            display: none;
        }

        .webName {
            position: absolute;
            flex: 0.7;
            font-size: 2rem;
            left: 5%;
            top: 0%;
            color: #ae0505;
        }

        .nav-search {
            width: 33.5rem;
            display: flex;
            justify-content: center;
            flex-direction: row;
            border: 1px #1d1d1d;
            border-style: solid;
            box-shadow: 0px 4px 0px 0px #1d1d1d;
            padding-right: 0.3rem;
            padding-left: 1rem;
            border-radius: 46px;
            height: 3.3rem;
            align-items: center;
        }

        nav {
            display: flex;
            width: 100%;
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

        /* para to sa isang sort (mobile)*/
        .filter-icon {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
        }

        nav button {
            background-color: #0d62ff;
            color: white;
            border: none;
            width: 7rem;
            height: 2.5rem;
            border-radius: 20px;
        }

        .nav-search input {
            width: 100%;
            font-size: 1.25rem;
            border: none;
        }

        input:focus {
            outline: none;
            border: none;
            box-shadow: none;
        }

        .vertical-line {
            border-left: 1px solid grey;
            height: 90px;
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
            padding: 10px;
            background-color: rgb(207, 207, 207);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            justify-content: center;
        }

        .sub-menu::after {
            /*content: "";*/
            position: absolute;
            top: -12px;
            right: 7%;
            width: 0;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-bottom: 12px solid black;
        }

        .fa-magnifying-glass {
            display: none;
        }

        .webName {
            position: absolute;
            flex: 0.7;
            font-size: 2rem;
            left: 5%;
            top: 35%;
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

        .messageButton img {
            width: 70px;
            height: 70px;
            position: absolute;
            bottom: 0%;
            right: 0%;
        }

        .addDiv {
            width: 70px;
            height: 70px;
            position: absolute;
            bottom: 0%;
            right: 5%;
        }
        .bot-ButtonGroup{
            position: absolute;
            bottom: 5%;
            right: 5%;
            position: relative;
        }
        .add-button{
        display: none;
        }
        .listing_links{
        display: none;
        }
        /* notification */
/* Base styles for notification system */
.notification-dropdown {
    position: absolute;
    top: 60px;
    right: 150px;
    width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    max-width: 90vw; 
    border: solid 1px black;
    border-radius: 11px;
}

.notification-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
}
.closeButton{
    display: none;
}
.notification-header h3 {
    margin: 0;
    font-size: 1.1rem;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
}

.notification-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    word-wrap: break-word; /* Ensures long text doesn't overflow */
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-title {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 0.95rem;
}

.notification-message {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
}

.notification-time {
    color: #999;
    font-size: 0.8rem;
    margin-top: 5px;
}

/* Custom scrollbar for webkit browsers */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}
.notification{
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
    padding: 5px;
    max-height: 200px;
    overflow: hidden;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}
.notification .Message {
    display: -webkit-box;
    -webkit-line-clamp: 6; /* Adjust number of visible lines */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 250px;
}
.notification h1{
    font-size: 1.1rem;
}
.time{
    display: flex;
    justify-content: flex-end;
}
        @media (max-width: 1560px) {
    .addDiv{
        right: 6%;
    }
    .add-button{
    display: flex;
}
.listing_links{
        display: flex;
        }
}
@media (max-width: 1430px) {
    .addDiv{
        right: 7%;
    }
    .notification-dropdown {
        right: 90px;
    }
}
@media (max-width: 1030px) {
    .addDiv{
        right: 9%;
    }
    .notification-dropdown {
       right: 50px;
    }
}
@media (max-width: 720px) {
    .addDiv{
        right: 15%;
    }
    .notification-dropdown {
        right: 50px;
    }
    .notification-dropdown {
        position: fixed;
        top: auto;
        bottom: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
        height: 60vh; /* Takes up 60% of viewport height on mobile */
        border-radius: 15px 15px 11px 11px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }

    .notification-header {
        padding: 20px 15px;
        position: sticky;
        top: 0;
        background: white;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 11px;
    }
}
@media (max-width: 520px) {
    .addDiv{
        right: 20%;
    }
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @yield('head')

</head>

<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <a href="{{ route('custom.home') }}"><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
        </div>
        <div class="left-nav">
            <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
            <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-header">
        <h3>Notifications</h3>
        <h3 class="closeButton">X</h3>
        </div>
        <div class="notification-list">
        @if ($notifications->isEmpty())
            <p>No notifications</p>
        @else
            @foreach ($notifications as $notification)
            <div class="notification">
            <div class="title">
            <h1>
                @if($notification['title']==="Product Approved")
                <span style="color:Green;">{{ $notification['title'] }}</span>
                @elseif($notification['title']==="Product Rejected")
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
            <div class="vertical-line"></div>
            <div class="profilePlace"><img class="profile_link"
                    src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" id="nav-profile">
            </div>

            <div class="sub-menu-wrapper" id="subMenu">
                <div class="sub-menu">
                    <a href="{{ route('accounts.page') }}" class="sub-menu-link">
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
    @yield('maincontent')
    <div class="bot-ButtonGroup">
        <div class="messageButton">
            <div class="">
            <a href="{{ route('Yonder/Chat') }}"><img src="{{ asset('img/message-icon-full.svg') }}" alt=""></a>
        </div>
    
            <a class="listing_links"href="{{ route('create.listing') }}"><div class="addDiv"><img class="add-button" src="{{ asset('img/add-button.svg') }}" alt=""></div></a>
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
        // notification 
    document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.querySelector('.notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Toggle dropdown
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = notificationDropdown.style.display === 'block';
        notificationDropdown.style.display = isVisible ? 'none' : 'block';
        
        // Add class to body to prevent scrolling when dropdown is open on mobile
        document.body.style.overflow = isVisible ? 'auto' : 'hidden';
        
        if (!isVisible) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
            closeDropdown();
        }
    });

    // Close dropdown when clicking the close button (mobile)
    const closeButton = document.querySelector('.closeButton');
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
                closeDropdown();
        });
    }

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    function closeDropdown() {
        notificationDropdown.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Add touch events for mobile
    let touchStartY = 0;
    let touchEndY = 0;

    notificationDropdown.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    }, false);

    notificationDropdown.addEventListener('touchmove', function(e) {
        touchEndY = e.touches[0].clientY;
        const diff = touchStartY - touchEndY;
        
        // If swiping down and at the top of the content
        if (diff < -50 && this.scrollTop === 0) {
            e.preventDefault();
            closeDropdown();
        }
    }, false);

});
    </script>
</body>

</html>
