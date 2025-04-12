<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Listing</title>
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
     @vite('resources/css/createListing.css')
</head>
<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <h1 class="webName">Yonder</h2>
        <div class="left-nav">
        <img class="wishlistBtn" src="{{ asset('img/cart.svg') }}" alt="">
        <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
        <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
        <div class="vertical-line"></div>
        <img class="profile_link" src="{{ asset('img/profile-placeholder.svg') }}" alt="">
        </div>
    </header>
    <div class="container">
        <div class="left">
            
        </div>
        <div class="right"></div>
    </div>
</body>
</html>