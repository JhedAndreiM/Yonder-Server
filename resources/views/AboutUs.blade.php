<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us</title>
    <link rel="stylesheet" href="{{ asset('css/AboutUs.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/AboutUs.css')
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
      <img class="gradient" src="{{ asset('img/gradient.svg ') }}" alt="" />
      <h2>About Us</h2>
    </div>
    <p>
      What started as a simple idea among students became a platform built with
      late nights,<span
        >shared passion, and a common goal: to connect our BPSU community like
        never before.</span
      >
    </p>
    <div class="mainContainer">
      <div class="container">
        <div class="img"><img src="{{ asset('img/image 8.png') }}" alt="" /></div>
        <div class="contents">
          <h3>Our Plan</h3>
          <h5>
            Every great project starts with a vision and ours was to create a
            secure, user-friendly marketplace tailored for the BPSU community.
            Our plan was carefully crafted to ensure that each phase of
            development served our users’ needs while reflecting our team's
            technical growth.
          </h5>
          <p>
            “Our plan wasn’t perfect—but our perseverance was. Every step taught
            us something new, and we’re excited to keep learning, building, and
            serving our campus.”
          </p>
        </div>
      </div>

      <div class="secondContainer">
        <div class="leftContent">
          <h3>Mission</h3>
          <h5>
            Our mission is to empower the Bataan Peninsula State University
            community by building a secure, accessible, and student-focused
            digital marketplace. We aim to simplify commerce within the campus
            by bridging the gap between students and university-associated
            businesses through a platform that values trust, convenience, and
            innovation. Yonder is designed not just to facilitate transactions,
            but to promote student entrepreneurship, financial literacy, and
            digital transformation within the university ecosystem.
          </h5>
          <h3>Vision</h3>
          <h5>
            To become the leading university-exclusive marketplace in the
            Philippines—one that fosters a culture of digital entrepreneurship,
            trust, and self-sustainability within academic communities. We
            envision a future where every student and university business can
            connect seamlessly through a secure, modern platform tailored to
            their needs, empowering them to grow, trade, and thrive in a
            digital-first world.
          </h5>
        </div>
        <div class="portImg">
          <img src="{{ asset('img/image 7.png') }}" alt="" />
        </div>
      </div>
      <h3 class="team">The Team</h3>
      <div class="solo">
        <img src="{{ asset('img/junSolo.png') }}" alt="" />
        <img src="{{ asset('img/jhedSolo.png') }}" alt="" />
        <img src="{{ asset('img/telSolo.png') }}" alt="" />
        <img src="{{ asset('img/irisSolo.png') }}" alt="" />
      </div>
      <p class="end">
        We are a team of IT students from Bataan Peninsula State University who
        worked together to create Yonder—a marketplace made for our fellow
        students. With hard work, teamwork, and a goal to help our campus, we
        built a platform that makes buying, selling, and trading easier and
        safer for everyone at BPSU.
      </p>
    </div>
  </body>
</html>
