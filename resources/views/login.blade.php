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
        <h4>GET STARTER</h4>
        <h1>Create new account</h1>
        <h6>Already have an account? <a href="#">Log in</a></h6>
        </div>
        

        <form action="">
            <div class="form-firstRow">
                <div class="fname input-container">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name">
                </div>

                <div class="lname input-container">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name">
                </div>
            </div>

            <div class="form-secondRow">
                <div class="email input-container">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                        pattern="^[a-zA-Z0-9._%+-]+@bpsu\.edu\.ph$" 
                        required>
                </div>
                <div class="input-container">
                    <label for="OTP">OTP</label>
                    <div class="otp-row">
                        <input class="otp"type="text" id="OTP" name="OTP">
                        <a href="">send code</a>
                    </div>
                </div>
                
            </div>
            <div class="form-thirdRow">
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
            </div>
            <div class="form-fourthRow">
                <div class="input-container">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="Password" id="confirm-password" name="confirm-password">
                </div>
            </div>
            <div class="btnGroup">
                <button class="cancel">Cancel</button>
                <button class="submit">Create Account</button>
            </div>
            
        </form>
       
    </div>
    <div class="right">
        <img src="{{ asset('img/login-image.svg') }}" alt="">
    </div>
</div>
</body>
</html>