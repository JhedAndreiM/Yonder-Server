<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/AboutUs.css')
    @vite('resources/js/AboutUs.js')
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <style>
        body {
          background-image: url("{{ asset('img/background.svg') }}");
          background-repeat: no-repeat;
          background-size: contain;
          background-size: cover;
          background-attachment: fixed;
        }
    </style>
  </head>
  <body>
    <!-- nav bar -->

    <nav class="navbar navbar-expand-lg py-3">
      <div class="container-fluid px-4">
        <!-- Left: Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{url('/')}}">
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
                class="nav-link fw-semibold"
                aria-current="page"
                href="{{ url('/') }}"
                >Home</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link fw-semibold active"
                href="{{ route('about.us') }}"
                >About</a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link fw-semibold" href="{{ route('FAQs') }}"
                >FAQs</a
              >
            </li>
          </ul>

          <!-- Right: Login -->
          <div class="d-flex">
            <a href="{{ route('login.form') }}" class="btn rounded-pill px-4"
              >Login</a
            >
          </div>
        </div>
      </div>
    </nav>

    <!-- nav bar -->

    <!-- snap-scrolling sections -->
    <main class="snap-container" aria-label="Page sections">
      <section id="section-1" class="section about-section">
        <h3 class="about-label">About Us</h3>
        <h1 class="about-heading">
          Connecting Students,<br />One Deal at a Time.
        </h1>

        <div class="video-wrapper">
          <video autoplay muted loop playsinline width="800" height="450">
            <source src="{{asset('imgs/AboutUs.mp4')}}" type="video/mp4" />
          </video>
        </div>
      </section>

      <section id="section-2" class="section mission-section">
        <div class="mission-content">
          <!-- Left column -->
          <div class="mission-left">
            <h2 class="mission-title">Our Mission</h2>
            <p class="mission-text">
              Yonder empowers the BPSU community with a secure, student-focused
              digital marketplace. We simplify campus commerce by connecting
              students and university businesses through a platform built on
              trust, convenience, and innovation—while promoting student
              entrepreneurship, financial literacy, and digital growth.
            </p>

            <div class="mission-video">
              <video autoplay muted loop playsinline>
                <source
                  src="{{asset('imgs/0_Online_Shopping_E_commerce_3840x2160.mp4')}}"
                  type="video/mp4"
                />
                Your browser does not support the video tag.
              </video>
            </div>
          </div>

          <!-- Right column -->
          <div class="mission-right">
            <h3>
              Empowering Students to Connect, Create, and Grow Through a Trusted
              Campus Marketplace
            </h3>
            <p>
              Yonder is more than just a marketplace—it’s a platform that
              empowers the BPSU community by creating opportunities, building
              connections, and supporting student success.
            </p>
            <p>
              Through Yonder, students can access a safe and trusted environment
              where they can buy, sell, and exchange goods and services within
              the campus community. Beyond transactions, it fosters
              collaboration and innovation by providing a space for students to
              showcase their talents, promote their initiatives, and engage with
              peers who share the same passion for growth. By combining
              convenience, trust, and community spirit, Yonder becomes a vital
              tool in helping students thrive academically, socially, and
              professionally.
            </p>
          </div>
        </div>

        <!-- Bottom features -->
        <div class="mission-features">
          <div class="feature">
            <span class="feature-number">01</span>
            <h4>Entrepreneurship</h4>
            <p>
              Gives students a space to start small businesses and showcase
              their products.
            </p>
          </div>
          <div class="feature">
            <span class="feature-number">02</span>
            <h4>Financial Literacy</h4>
            <p>
              Encourages smart spending and earning within a safe, trusted
              campus environment.
            </p>
          </div>
          <div class="feature">
            <span class="feature-number">03</span>
            <h4>Digital Growth</h4>
            <p>
              Promotes the use of modern platforms, preparing students for
              today’s digital economy.
            </p>
          </div>
        </div>
      </section>

      <section id="section-3" class="section vision-section">
        <div class="vision-content">
          <!-- Left column -->
          <div class="vision-left">
            <h2 class="vision-title">Our Vision</h2>
            <p class="vision-text">
              Yonder aims to be a trusted campus marketplace that supports the
              BPSU community by fostering safe and convenient digital
              transactions. Our vision is to create a platform where students
              and university-linked businesses can connect more easily, promote
              entrepreneurship, and build financial awareness. By encouraging
              collaboration and innovation within the campus, Yonder helps
              empower individuals to trade, grow, and adapt to the digital age.
            </p>

            <!-- Wide gray placeholder -->
            <div class="vision-box vision-box-wide">
              <video autoplay muted loop playsinline>
                <source
                  src="{{asset('imgs/7019997_Animation_Brainstorming_3840x2160.mp4')}}"
                  type="video/mp4"
                />
              </video>
            </div>
          </div>

          <!-- Right column -->
          <div class="vision-right">
            <video autoplay muted loop playsinline>
              <source
                src="{{asset('imgs/6918396_Motion_Graphics_Motion_Graphic_3840x2160.mp4')}}"
                type="video/mp4"
              />
            </video>
          </div>
        </div>

        <!-- Bottom features -->
        <div class="vision-features">
          <div class="feature">
            <div class="feature-icon">
              <img src="{{asset('imgs/TP.svg')}}" alt="Trusted Platform icon" />
            </div>
            <h4>Trusted Platform</h4>
            <p>
              We provide a secure and reliable space where students and campus
              businesses can trade with confidence.
            </p>
          </div>
          <div class="feature">
            <div class="feature-icon">
              <img src="{{asset('imgs/SE.svg')}}" alt="Student Empowerment icon" />
            </div>
            <h4>Student Empowerment</h4>
            <p>
              By promoting entrepreneurship and financial literacy, we help
              students build valuable skills for the future.
            </p>
          </div>
          <div class="feature">
            <div class="feature-icon">
              <img src="{{asset('imgs/CC.svg')}}" alt="Campus Connection icon" />
            </div>
            <h4>Campus Connection</h4>
            <p>
              Yonder bridges students and university-associated businesses,
              strengthening the academic community through collaboration.
            </p>
          </div>
          <div class="feature">
            <div class="feature-icon">
              <img src="{{asset('imgs/DR.svg')}}" alt="Digital Readiness icon" />
            </div>
            <h4>Digital Readiness</h4>
            <p>
              We prepare the BPSU community to adapt and thrive in a
              digital-first world by embracing modern, accessible technology.
            </p>
          </div>
        </div>
      </section>

      <!-- ===== SECTION 4: OUR TEAM ===== -->
      <section id="section-4" class="section team-section">
        <h2 class="team-title">Our Team</h2>

        <div class="team-content">
          <!-- LEFT: gallery (1 big, 3 small) -->
          <div class="team-gallery">
            <div class="team-box featured" data-member="jun">
              <img src="{{asset('imgs/junSolo.svg')}}" alt="Jun Vincent Guillermo" />
            </div>
            <div class="team-box" data-member="jhed">
              <img src="{{asset('imgs/jhedSolo.svg')}}" alt="Jhed Andrei Magdato" />
            </div>
            <div class="team-box" data-member="kristel">
              <img src="{{asset('imgs/telSolo.svg')}}" alt="Kristel Joy Bagtas" />
            </div>
            <div class="team-box" data-member="iris">
              <img src="{{asset('imgs/irisSolo.svg')}}" alt="Iris Jewel Dinglas" />
            </div>
          </div>

          <!-- RIGHT: profile card -->
          <article class="team-profile" aria-label="Team member profile">
            <div class="profile-header">
              <div class="role-pills"><!-- optional pills --></div>
            </div>

            <!-- avatar (optional; set via JS if you have images) -->
            <div class="profile-avatar" aria-hidden="true"></div>

            <div class="profile-body">
              <h3 class="profile-name">Jun Vincent Guillermo</h3>
              <p class="profile-role">Student<br />Balanga City, Bataan</p>

              <div class="skills">
                <span>Skills</span>
                <div class="skill-tags">
                  <span class="tag">Figma</span>
                  <span class="tag">Adobe Photoshop</span>
                  <span class="tag">Adobe Illustrator</span>
                  <span class="tag">Canva</span>
                  <span class="tag">UI/UX</span>
                </div>
              </div>

              <p class="profile-quote">“Pure grit, no shortcuts.”</p>
            </div>
          </article>
        </div>

        <div class="team-quote">
          “Together, we are Yonder. Driven by collaboration, creativity, and
          innovation to empower our campus community and shape a brighter
          digital future.”
        </div>
      </section>
    </main>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <script src="AboutUs.js"></script>
  </body>
</html>
