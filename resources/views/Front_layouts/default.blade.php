<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            /* Prevent body from scrolling */
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            height: 124px;
            display: flex;
            padding-top: 2rem;
            padding-bottom: 2rem;
            justify-content: center;
            align-items: center;
            font-family: "Raleway", sans-serif;
            background-color: white;
            border-bottom: solid rgb(0, 0, 0) 2px;
        }

        .menu-button {
            display: none;
        }

        .webName {
            position: absolute;
            flex: 0.7;
            font-size: 2rem;
            left: 5%;
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
            bottom: 10%;
            right: 5%;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @yield('head')

</head>

<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <a href="{{ route('custom.home') }}"><h1 class="webName">Yonder</h2></a>
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
                        <a href="{{ route('profile.page') }}" class="sub-menu-link">
                            <i class="fa-solid fa-user"></i>
                            <p>Profile</p>
                        </a>
                        <a href="" class="sub-menu-link">
                            <i class="fa-solid fa-gear"></i>
                            <p>Settings</p>

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
    <div class="messageButton">
        <a href="{{ route('Yonder/Chat') }}"><img src="{{ asset('img/message-icon-full.svg') }}" alt=""></a>
    </div>
    <script>
        let subMenu= document.getElementById("subMenu");
    $(document).on('click', '#nav-profile', function() {
        console.log('clicked1');
        subMenu.classList.toggle("active");
    });
        document.addEventListener('DOMContentLoaded', function () {
        
        const wishlistButtons = document.querySelectorAll('.wishlistBtn');
        const cartButton = document.querySelectorAll('.cartBtn');
        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });

        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
            })
        })
    });
    </script>
</body>

</html>
