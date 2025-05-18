<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FAQs</title>
    <link rel="stylesheet" href="FAQs.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/FAQs.css')
    @vite('resources/js/FAQs.js')
  </head>
  <body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <a href="/"><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
        <div class="nav-container">
        <nav>
            <ul class="navLinks">
            <div class="ovalHover"></div>
                <li class="navHome"><a href="/">Home</a></li>
                <li class="navAbout"><a href="{{ route('about.us') }}">About</a></li>
                <li class="navFAQ"><a href="{{ route('FAQs') }}">FAQ</a></li>
                
            </ul>
        </nav>
        </div>
        <div class="button-group">
            <a href="{{ route('select.role') }}">
                <button class="Login"  style="cursor: pointer;">Login</button>
            </a>
        </div>
        
    </header>
    <div class="top">
      <img class="gradient" src="{{ asset('img/gradient.svg') }}" alt="" />
      <h2>FAQs</h2>
    </div>
    <h3>
      Got questions?<br />
      <span>We've got quick answers!</span>
    </h3>
    <div class="container">
      <div class="leftPart">
        <div class="box">
          <div class="box-header">
            <h4>What is Yonder?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Yonder is an exclusive online platform for Bataan Peninsula State
            University students, faculty, and staff to buy, sell, or trade items
            within the university community.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>Who can use the marketplace?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Only verified BPSU students, faculty, and staff with a university
            email can access and use the platform.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>Is it free to post items?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Yes! Posting items for sale or trade on the marketplace is
            completely free.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>What kinds of items can I sell?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            You can sell school supplies, gadgets, books, uniforms, and other
            personal items—just make sure they comply with university policies.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>How do I contact a seller or buyer?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Use the built-in messaging system to safely communicate within the
            platform.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>Is the marketplace safe to use?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">Is the marketplace safe to use?</p>
        </div>
      </div>
      <div class="rightPart">
        <div class="box">
          <div class="box-header">
            <h4>How long will my listing stay active?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Listings remain as long as the user do not delete them or still have
            remaining stocks.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>Can I delete or edit my post after publishing it?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Absolutely! You can edit or remove your listings anytime through
            your profile dashboard.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>Are transactions handled through the platform?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            No, transactions are done directly between users. We recommend
            meeting on campus and using safe payment methods.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>What are vouchers in the BPSU Marketplace?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Vouchers are digital discount codes or promotional credits that can
            be applied to purchases within the marketplace.
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>How do I get a voucher?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            Vouchers can be earned buy making 5 purchases or transactions with
            PBEN,
          </p>
        </div>
        <div class="box">
          <div class="box-header">
            <h4>How do I use a voucher?</h4>
            <img class="dropdown" src="{{ asset('img/dropdown.svg') }}" alt="" />
          </div>
          <p class="box-content">
            After clicking “add to cart” or “buy”, select the voucher. The
            discount will be automatically applied to your total.
          </p>
        </div>
      </div>
    </div>
    <script src="FAQs.js"></script>
  </body>
</html>
