
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap');
    </style>
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/login.css')
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
        <a href=""><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
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
</header>
<div class="container">
    <div class="left">
        <div class="form-header">
        <h4>GET STARTED</h4>
        <h1>Login to your account</h1>
        <h6>Log in to access your account, track your orders, and <br>
            enjoy a personalized experience.</h6>
        </div>
        

        <form action="{{ route('login.submit') }}" method="POST">
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
    <div class="right">
        <img src="{{ asset('img/login-image.svg') }}" alt="">
    </div>
</div>
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
</body>
</html>