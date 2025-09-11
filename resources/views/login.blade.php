
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap');
    </style>
    <title>Log in</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/login.css')
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
            <a class="navbar-brand d-flex align-items-center" href="{{url('/')}}">
              <img src="{{ asset('img/logo.svg') }}" alt="Logo" height="40" />
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
                  <a class="nav-link fw-semibold" href="{{ route('about.us') }}">About</a>
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
<div class="container">
    <div class="left">
        <div class="form-header" data-aos="fade-right">
        <h4>GET STARTED</h4>
        <h1>Login to your account</h1>
        <h6>Log in to access your account, track your orders, and <br>
            enjoy a personalized experience.</h6>
        </div>
        

        <form action="{{ route('login.submit') }}" method="POST" data-aos="fade-right">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <div class="form-firstRow">
                <div class="email input-container">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                        pattern="^[a-zA-Z0-9._%+-]+@bpsu\.edu\.ph$" 
                        required autocomplete="off">
                </div>
                </div>

                <div class="lname input-container">
                    <div class="password-container">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" autocomplete="off">
                        <i class="fa-solid fa-eye" id="showPassword"></i>
                </div>
            </div>
            @if($errors->any())
            <div style="color: red;padding-top:1rem;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            <div class="btnGroup">
                <a class="cancel"href="{{ route('select.role') }}">Cancel</a>
                <button class="submit">Login</button>
            </div>
            
        </form>
        
    </div>
    <div class="right" data-aos="fade-left">
        <img src="{{ asset('img/login-image.svg') }}" alt="">
    </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" style="display: none;">
  <div class="modal-content">
    <div class="header-warning">This is for system testing</div>

    <div class="modal-body">

      {{-- Success Message --}}
      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      {{-- Error Messages --}}
      @if ($errors->any())
          <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                  <div>{{ $error }}</div>
              @endforeach
          </div>
      @endif
      @if (session('success'))
      <button type="button" class="close" id="closeBtn">Close</button>
      @else
      <form class="modalForm" id="modalForm" action="{{ route('modal.submit') }}" method="POST">
        @csrf

        <input type="text" name="name" id="firstName" placeholder="First Name" required>
        <input type="text" name="middle_name" id="middleName" placeholder="Middle Name (optional)">
        <input type="text" name="last_name" id="lastName" placeholder="Last Name" required>

        <input type="email" name="email" id="modalEmail" placeholder="user@bpsu.edu.ph" required>
        <input type="email" name="confirmemail" id="confirmmodalEmail" placeholder="Confirm Email" required>

        <span>Only BPSU email addresses are allowed</span>

        <div class="modal-footer">
          <button type="button" class="close" id="closeBtn">Close</button>
          <button type="submit" class="close" id="submitBtn">Submit</button>
        </div>
      </form>
      @endif
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
    const showPassword = document.querySelector("#showPassword");
    const passwordField = document.querySelector("#password");

    showPassword.addEventListener("click", function(){
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
        const type=passwordField.getAttribute("type")=== "password"?"text":"password";
        passwordField.setAttribute("type",type);
    });
</script>
<!-- modal script -->
<script>
    // Get modal and close button
    const modal = document.getElementById("myModal");
    const closeBtn = document.getElementById("closeBtn");

    window.onload = () => {
        // Always show on first load
        modal.style.display = "flex";

        // Also reopen if errors or success exist
        @if ($errors->any() || session('success'))
            modal.style.display = "flex";
        @endif
    };

    // Close when clicking the "Close" button
    closeBtn.onclick = () => {
        modal.style.display = "none";
    };

    // Close when clicking outside modal content
    window.onclick = (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
  </script>
</body>
</html>