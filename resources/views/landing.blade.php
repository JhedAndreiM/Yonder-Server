<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DINGLAS</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap');
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
</head>
<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <h1 class="webName">Yonder</h2>
        <div class="nav-container">
        <nav>
            <ul class="navLinks">
            <div class="ovalHover"></div>
                <li class="navHome"><a href="#"></a>Home</li>
                <li><a href="#"></a>About</li>
                <li><a href="#"></a>FAQs</li>
                
            </ul>
        </nav>
        </div>
        <div class="button-group">
            <a href="{{ route('select.role') }}">
                <button class="Login"  style="cursor: pointer;">Login</button>
            </a>
        </div>
        
    </header>
    <section class="pageOne">
        <div class="section-content">
            <div class="section-text">
                <h1>Connecting students <br>through seamless buying, <br>selling, and trading</h1>
                <h5>Welcome to UniTrade – Your university-exclusive marketplace! Buy, sell, <br>and trade everything you need within your campus community. Safe, <br>easy, and made just for students like you. Start exploring today!</h5>
                <a class="getStartedBtn"href="{{ route('select.role') }}">Get Started</a>
            </div>
            <div class="section-img">
                <img class="header-img"src="{{ asset('img/sectionOne-img.svg') }}" alt="">
            </div>
        </div>
    </section>
    <section class="pageTwo">
        <div class="info"><h1 class="pageTwoHeader">Our Services</h1>
            <p class="pageTwoText">At UniTrade, we offer a seamless platform where students can buy and sell items such as books, gadgets, and more within their 
            university. Our secure system ensures safe and verified transactions, connecting you with trusted buyers and sellers from your own campus. </p>
        </div>
            <div class="center">
            <div class="card-container">
                
                <div class="card card1">
                    <div class="cardOne-top">
                        <h1>In-App Messaging</h1>
                        <img src="{{ asset('img/message-icon.svg') }}" alt="">
                    </div>
                    <div class="cardOne-middle">
                        <p>Built-in in-app messaging system enables communication between buyers and sellers. </p>
                    </div>
                    <div class="cardOne-bottom">
                        <img src="{{ asset('img/arrow-white.svg') }}" alt="">
                        <h2>FAQ</h2>
                    </div>
                </div>
                
                <div class="card card2">
                <div class="cardTwo-top">
                        <h1>Buy and Sell</h1>
                        <img src="{{ asset('img/money-icon.svg') }}" alt="">
                    </div>
                    <div class="cardTwo-middle">
                        <p>Easily buy and sell books, gadgets, and other essentials within your university community.</p>
                    </div>
                    <div class="cardTwo-bottom">
                        <img src="{{ asset('img/arrow-black.svg') }}" alt="">
                        <h2>FAQ</h2>
                    </div>
                </div>
                
                <div class="card card3">
                <div class="cardThree-top">
                        <h1>Item Trading</h1>
                        <img src="{{ asset('img/trade-icon.svg') }}" alt="">
                    </div>
                    <div class="cardThree-middle">
                        <p>Trade items with fellow students, making it simple to exchange goods without the hassle.</p>
                    </div>
                    <div class="cardThree-bottom">
                        <img src="{{ asset('img/arrow-black.svg') }}" alt="">
                        <h2>FAQ</h2>
                    </div>
                </div>

                <div class="card card4">
                <div class="cardFour-top">
                        <h1>Exclusive Prizes</h1>
                        <img src="{{ asset('img/coupons.svg') }}" alt="">
                    </div>
                    <div class="cardFour-middle">
                        <p>Get the chance to win exclusive prizes from official BPSU shops when you buy through UniTrade!</p>
                    </div>
                    <div class="cardFour-bottom">
                        <img src="{{ asset('img/arrow-white.svg') }}" alt="">
                        <h2>FAQ</h2>
                    </div>
                </div>
            </div>
            </div>
    </section>
    <div class="divider">
    <div class="dividerOne">ADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE </div>
    <div class="dividerTwo">UY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY </div>
    </div>
    
    <section class="pageThree">
    <div class="pageThree-container">
        <div class="pageThree-top">
            <h1>Let's make things happen!</h1>
        </div>
        <div class="pageThree-middle">
            <p>Get started with UniTrade today and enjoy a hassle-free way to buy, sell, and trade within your campus—saving money, finding great deals, and connecting with fellow students has never been this easy!</p>
        </div>
        <div class="pageThree-bottom">
            <h2>Get Started</h2>
            <img src="{{ asset('img/arrow-black.svg') }}" alt="">
        </div>
    </div>
    </section>
    <footer>
        <div class="footerOne">
            
            <ul class="footerUniTrade">
                <li><h3>UniTrade</h3></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Our Services</a></li>
                <li><a href="#">Privacy and Policy</a></li>
            </ul>
        </div>

        <div class="footerTwo">
            
            <ul class="footerGetHelp">
                <li><h3>Get Help</h3></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Send us an email</a></li>
            </ul>
        </div>
        <div class="footerThree">
            <ul class="footerGetStarted">
                <li><h3>Get Started</h3></li>
                <li><a href="#">Sign In</a></li>
                <li class="invisible-item"></li>
                <li class="invisible-item"></li>
            </ul>
        </div>
        <div class="footerFour">
            <img src="{{ asset('img/BPSU-logo.svg') }}" alt="">
            <h1>UniTrade</h1>
        </div>
    </footer>

    <div class="slide-nav">
        <div class="nav-content">
            <div class="nav-uniTradeTop">
                <img class="menu-buttonNav" src="{{ asset('img/Menu.svg') }}" alt="">
                <h1>UniTrade</h1>
            </div>
            <div class="nav-list">
                <ul class="slide-top">
                    <div class="slide-highlight">aaa</div>
                    <li class="navHome"><img class="navSlide-logo" src="{{ asset('img/Home.svg') }}" alt="">Home</li>
                    <li><img class="navSlide-logo" src="{{ asset('img/Info Squared.svg') }}" alt="">About</li>
                    <li><img class="navSlide-logo" src="{{ asset('img/FAQ.svg') }}" alt="">FAQ</li>
                </ul>
                <hr class="solid">
                <ul class="slide-bottom">
                    <li class="navEnter"><img class="navSlide-logo" src="{{ asset('img/Enter.svg') }}" alt="">Log in</li>
                    <li><img class="navSlide-logo" src="{{ asset('img/Out.svg') }}" alt="">Get Started</li>
                </ul>
            </div>
            
        </div>
    </div>
</body>
</html>
