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
}
@media (max-width: 1030px) {
    .addDiv{
        right: 9%;
    }
}
@media (max-width: 720px) {
    .addDiv{
        right: 15%;
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
            <div class="vertical-line"></div>
            <div class="profilePlace"><img class="profile_link"
                    src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" id="nav-profile">
            </div>

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
    </script>
</body>

</html>
