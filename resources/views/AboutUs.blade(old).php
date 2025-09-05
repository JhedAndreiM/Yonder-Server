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
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    @vite('resources/css/AboutUs.css')
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg py-3">
          <div class="container-fluid px-4">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img src="img/logo.svg" alt="Logo" height="40" />
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
                    class="nav-link fw-semibold"
                    aria-current="page"
                    href="{{ url('/') }}"
                    >Home</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold active" href="{{ route('about.us') }}">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="{{ route('FAQs') }}">FAQs</a>
                </li>
              </ul>

              <!-- Right: Login -->
              <div class="d-flex">
                <a
                  href="{{ route('select.role') }}"
                  class="btn rounded-pill px-4"
                  >Login</a
                >
              </div>
            </div>
          </div>
        </nav>
    <div class="top">
      <img class="gradient" src="{{ asset('img/gradient.svg ') }}" alt="" />
      <h2>About Us</h2>
    </div>
    <p data-aos="fade-up">
      What started as a simple idea among students became a platform built with
      late nights,<span
        >shared passion, and a common goal: to connect our BPSU community like
        never before.</span
      >
    </p>
    <div class="mainContainer">
      <div class="container">
        <div class="img" data-aos="fade-up"><img src="{{ asset('img/image 8.png') }}" alt="" /></div>
        <div class="contents" >
          <h3 data-aos="fade-up" >Our Plan</h3>
          <h5 data-aos="fade-up">
            Every great project starts with a vision and ours was to create a
            secure, user-friendly marketplace tailored for the BPSU community.
            Our plan was carefully crafted to ensure that each phase of
            development served our users’ needs while reflecting our team's
            technical growth.
          </h5>
          <p data-aos="fade-up">
            “Our plan wasn’t perfect—but our perseverance was. Every step taught
            us something new, and we’re excited to keep learning, building, and
            serving our campus.”
          </p>
        </div>
      </div>

      <div class="secondContainer">
        <div class="leftContent">
          <h3 data-aos="fade-up">Mission</h3>
          <h5 data-aos="fade-up">
            Our mission is to empower the Bataan Peninsula State University
            community by building a secure, accessible, and student-focused
            digital marketplace. We aim to simplify commerce within the campus
            by bridging the gap between students and university-associated
            businesses through a platform that values trust, convenience, and
            innovation. Yonder is designed not just to facilitate transactions,
            but to promote student entrepreneurship, financial literacy, and
            digital transformation within the university ecosystem.
          </h5>
          <h3 data-aos="fade-up">Vision</h3>
          <h5 data-aos="fade-up">
            To become the leading university-exclusive marketplace in the
            Philippines—one that fosters a culture of digital entrepreneurship,
            trust, and self-sustainability within academic communities. We
            envision a future where every student and university business can
            connect seamlessly through a secure, modern platform tailored to
            their needs, empowering them to grow, trade, and thrive in a
            digital-first world.
          </h5>
        </div>
        <div class="portImg" data-aos="fade-up">
          <img src="{{ asset('img/image 7.png') }}" alt="" />
        </div>
      </div>
      <h3 class="team" data-aos="fade-up">The Team</h3>
      <div class="solo" data-aos="fade-up">
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
  </body>
</html>
