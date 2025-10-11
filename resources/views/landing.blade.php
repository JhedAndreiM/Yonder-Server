
@php
    use Illuminate\Support\Facades\DB;

    $faqs = DB::table('faq_questions')
        ->orderBy('created_at', 'asc')
        ->limit(4)
        ->get();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonder</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap');
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
        <nav class="navbar navbar-expand-lg py-3">
          <div class="container-fluid px-4">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img src="img/YonderLogo.svg" alt="Logo" height="40" />
            </a>

            <!-- Hamburger for mobile -->
            <button
              class="navbar-toggler"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#mainNavbar"
              aria-controls="mainNavbar"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="mainNavbar">
              <!-- Middle: Nav links -->
              <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 text-center">
                <li class="nav-item">
                  <a
                    class="nav-link active fw-semibold"
                    aria-current="page"
                    href="#"
                    >Home</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="{{ route('about.us') }}">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="{{ route('FAQs') }}">FAQs</a>
                </li>
              </ul>

              <!-- Right: Login -->
              <div class="d-flex">
                <a
                  href="{{ route('login.form') }}"
                  class="btn rounded-pill px-4"
                  >Login</a
                >
              </div>
            </div>
          </div>
        </nav>
    <section class="pageOne">
        <div class="section-content">
            <div class="section-text" data-aos="fade-right"> 
                <h1>Connecting students <br>through seamless buying, <br>selling, and trading</h1>
                <h5>Welcome to Yonder – Your university-exclusive marketplace! Buy, sell, <br>and trade everything you need within your campus community. Safe, <br>easy, and made just for students like you. Start exploring today!</h5>
                <a class="getStartedBtn"href="{{ route('login.form') }}">Get Started</a>
            </div>
            <div class="section-img" data-aos="fade-left">
                <img class="header-img" src="{{ asset('img/ecommerce-campaign-animate.svg') }}" alt="" width="600" height="700">
            </div>
        </div>
    </section>
    <section class="pageTwo">
        <div class="info"><h1 class="pageTwoHeader" data-aos="fade-right">Our Services</h1>
            <p class="pageTwoText" data-aos="fade-right">At Yonder, we offer a seamless platform where students can buy and sell items such as books, gadgets, and more within their 
            university. Our secure system ensures safe and verified transactions, connecting you with trusted buyers and sellers from your own campus. </p>
        </div>
            <div class="center">
            
            <div class="card-container">
@php
    $cardNames = ['One', 'Two', 'Three', 'Four'];
@endphp

@foreach ($faqs as $faq)
    @php
        $index = ($loop->iteration - 1) % 4; // 0 → 3
        $cardWord = $cardNames[$index];

        // Pick arrow color
        $arrow = in_array($index, [1, 2]) 
            ? asset('img/arrow-black.svg') 
            : asset('img/arrow-white.svg');

        // Pick link color
        $linkColor = in_array($index, [1, 2]) ? 'black' : 'white';
    @endphp

    <div data-aos="fade-up">
        <div class="card card{{ $loop->iteration }}">
            <div class="card{{ $cardWord }}-top">
                <h1>{{ $faq->question }}</h1>
            </div>

            <div class="card{{ $cardWord }}-middle card-middle">
                <p class="faq-answer" data-full="{{ $faq->answer }}">
                    {{ $faq->answer }}
                </p>
                <span class="toggle-btn" style="display:none">See more</span>
            </div>

            <div class="card{{ $cardWord }}-bottom">
                <img src="{{ $arrow }}" alt="">
                <a href="{{ route('FAQs', ['faq_id' => $faq->id]) }}" 
                class="cardLinks" 
                style="color:{{ $linkColor }}">
                    <h2>FAQ</h2>
                </a>
            </div>
        </div>
    </div>
@endforeach
                
                <!-- <div data-aos="fade-up">
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
                            <a href="{{ route('FAQs') }}" class="cardLinks" style="color:black"><h2>FAQ</h2></a>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-up">
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
                            <a href="{{ route('FAQs') }}" class="cardLinks" style="color:black"><h2>FAQ</h2></a>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-up">
                    <div class="card card4">
                    <div class="cardFour-top">
                            <h1>Exclusive Prizes</h1>
                            <img src="{{ asset('img/coupons.svg') }}" alt="">
                        </div>
                        <div class="cardFour-middle">
                            <p>Get the chance to win exclusive prizes from official BPSU shops when you buy through Yonder!</p>
                        </div>
                        <div class="cardFour-bottom">
                            <img src="{{ asset('img/arrow-white.svg') }}" alt="">
                            <a href="{{ route('FAQs') }}" class="cardLinks" style="color:white"><h2>FAQ</h2></a>
                        </div>
                    </div>
                </div> -->
            </div>
                
                

            </div>
    </section>
    <section class="secdivider">
<div class="divider">
  <div class="dividerOne" data-aos="fade-left">
    <div class="scrolling-text">
      <span>TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅</span>
      <span>TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅</span>
      <span>TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅</span>
      <span>TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅</span>
    </div>
  </div>
  <div class="dividerTwo" data-aos="fade-right">
    <div class="scrolling-text">
      <span>BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅</span>
      <span>BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅</span>
      <span>BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅</span>
      <span>BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅</span>
      <span>BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅ BUY ⋅ SELL ⋅ TRADE ⋅</span>
    </div>
  </div>
</div>
</section>
    
    <section class="pageThree">
    <div data-aos="fade-up">
    <div class="pageThree-container">
        <div class="pageThree-top">
            <h1>Let's make things happen!</h1>
        </div>
        <div class="pageThree-middle">
            <p>Get started with Yonder today and enjoy a hassle-free way to buy, sell, and trade within your campus—saving money, finding great deals, and connecting with fellow students has never been this easy!</p>
        </div>
        <div class="pageThree-bottom">
            <a href="{{route('login.form')}}" class="cardLinks" style="color:black"><h2>Get Started</h2></a>
            <img src="{{ asset('img/arrow-black.svg') }}" alt="">
        </div>
    </div>
    </div>
    </section>
<footer>
    <div class="footer-container">
        <!-- Navigation Links -->
        <div class="footer-links">
            <div class="footer-column">
                <h3>Discover</h3>
                <ul>
                    <li><a href="{{ route('about.us') }}">About Us</a></li>
                    <li><a href="{{ route('about.us') }}#section-2">Mission and Vision</a></li>
                    <li><a href="https://storyset.com/people" target="_blank">People illustrations</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="{{ route('FAQs') }}">FAQs</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-column footer-column-no-underline">
                <h3>&nbsp;</h3>
                <ul>
                    <li><a href="#">Send us an email</a></li>
                    <li><a href="#">Community Guidelines</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Logo Section -->
        <div class="footer-logo-section">
            <img src="{{ asset('img/BPSU-logo.svg') }}" alt="Yonder Logo">
        </div>
    </div>
    
    <!-- Copyright Section -->
    <div class="footer-copyright">
        <p>© 2025 Yonder. All rights reserved. Terms of Service ~ Privacy Policy</p>
    </div>
</footer>

    <div class="slide-nav">
        <div class="nav-content">
            <div class="nav-uniTradeTop">
                <img class="menu-buttonNav" src="{{ asset('img/Menu.svg') }}" alt="">
                <h1>Yonder</h1>
            </div>
            <div class="nav-list">
                <ul class="slide-top">
                    <div class="slide-highlight">aaa</div>
                    <li class="navHome">Home</li>
                    <li><a href="{{ route('about.us') }}">About</a></li>
                    <li>FAQ</li>
                </ul>
                <hr class="solid">
                <ul class="slide-bottom">
                    <li class="navEnter">Log in</li>
                </ul>
            </div>
            
        </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 1000,   // animation duration (ms)
        once: true        // animate only once
    });
    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".card-middle").forEach(function (container) {
        const answer = container.querySelector(".faq-answer");
        const btn = container.querySelector(".toggle-btn");

        const lineHeight = parseFloat(getComputedStyle(answer).lineHeight);
        const twoLinesHeight = lineHeight * 2;

        if (answer.scrollHeight > twoLinesHeight + 2) {
            btn.style.display = "inline-block";
        }

        btn.addEventListener("click", function () {
            answer.classList.toggle("expanded");

            if (answer.classList.contains("expanded")) {
                btn.textContent = "See less";
            } else {
                btn.textContent = "See more";
            }
        });
    });
});
</script>

</body>
</html>
