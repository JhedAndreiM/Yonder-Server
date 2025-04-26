<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wishlist</title>
    @vite('resources/css/wishlist.css')
    <style>
      body {
      background-image: url("{{ asset('img/background.svg') }}");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      }
      .mySlides {
          display:none;
      }
  </style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <header>
      <h1 class="webName">Yonder</h1>
      <div class="left-nav">
        <img class="wishlistBtn" src="IMG/cart.svg" alt="" />
        <img class="wishlistBtn" src="img/heart-icon.svg" alt="" />
        <img class="notificationBtn" src="img/bell-icon.svg" alt="" />
        <div class="vertical-line"></div>
        <img class="profile_link" src="img/profile-placeholder.svg" alt="" />
      </div>
    </header>
    <div class="mainContainer">
      <div class="top">
        <h1>Wishlist</h1>
      </div>
      <div class="container">
        @include('partials.wishlistProducts', ['wishlistItems' => $wishlistItems])
      </div>
    </div>
  </body>
</html>
