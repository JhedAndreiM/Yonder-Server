
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
                        required>
                </div>
                </div>

                <div class="lname input-container">
                    <div class="password-container">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">
                </div>
            </div>
            @if($errors->any())
            <div style="color: red; margin-bottom: 10px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
            <div class="btnGroup">
                <button class="cancel">Cancel</button>
                <button class="submit">Login</button>
            </div>
            
        </form>
        
    </div>
    <div class="right">
        <img src="{{ asset('img/login-image.svg') }}" alt="">
    </div>
</div>
</body>
</html>