<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/vouchers.css')
  </head>
  <body>
    <header>
        <h1 class="webName">Yonder</h2>
    <div class="left-nav">
        <img class="wishlistBtn" src="IMG/cart.svg" alt="">
        <img class="wishlistBtn" src="img/heart-icon.svg" alt="">
        <img class="notificationBtn" src="img/bell-icon.svg" alt="">
        <div class="vertical-line"></div>
        <img class="profile_link" src="img/profile-placeholder.svg" alt="">
    </div>
</header>
<div class="mainContainer">
    <div class="top">
        <h1>Profile</h1>
    </div>
    <div class="container">
        <div class="leftPart">
            <div class="leftPartItems">
                <div class="profile"></div>
                <h3 class="h3">{{ Auth::user()->name }}</h3>
            </div>
            <hr>
            <div class="leftPartItems2">
                <a href="{{ route('profile.page') }}" >My Account</a>
                    <a href="{{ route('profileListings.page') }}">My Listings</a>
                    <a href="{{ route('vouchers.page') }}" class="current">My Vouchers</a>
            </div>
        </div>
        <div class="rightPart">
            <h2 class="yourListing">Your Voucher(s):</h2>
            <div class="cardContainer">
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div><div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div><div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div><div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"></div>
                        <h3 class="voucherValue">PHP 100 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 100 OFF for single use only.</h4>
                    <p class="use">Use</p>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
