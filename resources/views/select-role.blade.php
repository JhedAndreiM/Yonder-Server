
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/select-role.css')
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
                <li class="navHome"><a href="/">Home</a></li>
                <li class="navAbout"><a href="{{ route('about.us') }}">About</a></li>
                <li class="navFAQ"><a href="{{ route('FAQs') }}">FAQ</a></li>
                
            </ul>
        </nav>
        </div>
</header>
<div class="container">
<div class="top">
    <h4>Get Started</h4>
    <h1>Choose Your Account Type</h1>
    <h5>Select how you'd like to log in to continue to<br> your personalized experience.</h5>
</div>
<div class="bottom">
    
<a class="adminBtn" href="{{ route('login.form', ['role' => 'admin']) }}">Admin</a>
<a class="orgBtn" href="{{ route('login.form', ['role' => 'organization']) }}">Organization</a>
<a class="studentBtn" href="{{ route('login.form', ['role' => 'student']) }}">Student</a>
</div>
</div>

</body>
</html>