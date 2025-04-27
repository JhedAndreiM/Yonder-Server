<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/addToCart.css')
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
          <h1>My Cart</h1>
        </div>
        <div class="container">
            <!-- START TO NG CARD! RAHHH -->
            <div class="card">
                <div class="card-image">
                    <img class="image-placeholder"src="{{ asset('img/default-product.png') }}" alt="No image available">
                </div>
                <div class="card-details">
                    <h2>Product Name</h1>
                    <h4>Stocks: </h4>
                    <h4>Price: </h4>
                </div>
                <div class="card-functions"></div>
            </div>
             <!-- END NG CARD! RAHHH -->
        </div>
      </div>
</body>
</html>