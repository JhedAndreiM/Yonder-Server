<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <meta name="base-url" content="{{ url('/') }}">
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
        .mySlides {
            display:none;
        }
    </style>
    @vite('resources/css/mainPage.css')
    @vite('resources/js/mainPage.js')
</head>

<body>
    <!-- Ang Controller ng Page na to ay PageController.php :) -->
<header>
    <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
    <a href="{{ route('custom.home') }}"><img class="webName" src="{{ asset('img/logo.svg') }}" alt=""></a>
    <div class="nav-search">
        <nav>
            <form>
                <input id="searchInput"type="text" placeholder="Search" >
                <button id="btnSub" type="submit"><img src="{{ asset('img/search-icon.svg') }}" alt=""></button>
                <img id="magnifying"class="fa-magnifying-glass" src="{{ asset('img/search-icon.svg') }}" alt="">
            </form>
            
        </nav>
    </div>
    <div class="left-nav">
        <img class="cartBtn" src="{{ asset('img/cart.svg') }}" alt="">
        <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
        <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
        <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
        <div class="notification-header">
        <h3>Notifications</h3>
        <h3 class="closeButton">X</h3>
        </div>
        <div class="notification-list">
        @if ($notifications->isEmpty())
            <p>No notifications</p>
        @else
            @foreach ($notifications as $notification)
            <div class="notification">
            <div class="title">
            <h1>
                @if($notification['title']==="Product Approved")
                <span style="color:Green;">{{ $notification['title'] }}</span>
                @elseif($notification['title']==="Product Rejected")
                <span style="color:red;">{{ $notification['title'] }}</span>
                @else
                {{ $notification['title'] }}
                @endif
            </h1>
            </div>
            <div class="Message">{{ $notification['message'] }}</div>
            <div class="time">{{ $notification['time_ago'] }}</div>
        </div>
        @endforeach
            
        @endif
        
        
        </div>
        </div>
        <div class="vertical-line"></div>
        <div class="profilePlace"><img class="profile_link" src="{{ asset('storage/users-avatar/'. Auth::user()->avatar) }}" alt="" id="nav-profile"></div>
        
        <div class="sub-menu-wrapper" id="subMenu">
            <div class="sub-menu">
                <a href="{{ route('student.profile') }}" class="sub-menu-link">
                    <i class="fa-solid fa-user"></i>
                    <p>Profile</p>
                </a>
                <a href="{{ route('account.page') }}" class="sub-menu-link">
                    <i class="fa-solid fa-gear"></i>
                    <p>Account</p>
                    
                </a>
                <a href="{{ route('logout') }}" class="sub-menu-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <p>Logout</p>
                </a>
            </div>
        </div>
    </div>

</header>
<div class="container">
    <div class="left">
        <div class="filter-box">
        <div class="one title">
        <h1>Filter</h1>
    </div>

    <div class="two supplierTypes">
        <h3>Supplier Types</h3>
        <button class="filter-btn" data-filter="verified" data-filter-type="condition">Verified suppliers</button>
        <button class="filter-btn" data-filter="students" data-filter-type="condition">Students</button>
    </div>

    

    <div class="four condition">
        <h3>Condition</h3>
        <button class="filter-btn" data-filter="new" data-filter-type="condition">New</button>
        <button class="filter-btn" data-filter="like-new" data-filter-type="condition">Like new</button>
        <button class="filter-btn" data-filter="used" data-filter-type="condition">Used (Fair)</button>
    </div>

    <div class="five price">
        <h3>Price</h3>
        Min: <input id="min"class="input-min" type="number" placeholder="100" min="0" data-filter-type="condition">
        Max: <input id="max"class="input-max" type="number" placeholder="1000" min="0" data-filter-type="condition">
    </div>

    <div class="six colleges">
        <h3>Colleges</h3>
        <button class="filter-btn" data-filter="ccst" data-filter-type="condition">CCST</button>
        <button class="filter-btn" data-filter="cea" data-filter-type="condition">CEA</button>
        <button class="filter-btn" data-filter="cba" data-filter-type="condition">CBA</button>
        <button class="filter-btn" data-filter="ctech" data-filter-type="condition">CTECH</button>
        <button class="filter-btn" data-filter="cahs" data-filter-type="condition">CAHS</button>
        <button class="filter-btn" data-filter="cas" data-filter-type="condition">CAS</button>
    </div>

    <div class="seven for">
        <h3>For</h3>
        <button class="filter-btn" data-filter="sale" data-filter-type="condition">Sale</button>
        <button class="filter-btn" data-filter="trade" data-filter-type="condition">Trade</button>
    </div>
        </div>
        <div class="add-listing">
           <a class="listing_link"href="{{ route('create.listing') }}"><h6>want to sell or trade? Add a listing!</h6></a>
        </div>
    </div>
    <div class="right" id="scroll-container">

        <div class="right-top">
            <div class="right-top-left">
                <h5>Welcome, {{ Auth::user()->name }}! Here are the recommended</h5>
                <h5>listing for you:</h5>
            </div>

            <div class="right-top-right">
                <button id="filter-btn" class="filter-icon">
                    <img class="fas fa-filter" src="{{ asset('img/Funnel.svg') }}" alt="">
                  </button>
                  <h5 class="sort-label">Sort by:</h5>
                <div id="sort-dropdown" class="sort-dropdown">
                    <select name="sort-by" id="sort-by">
                      <option value="">Sort Here</option>
                      <option value="lowToHigh">Price: Low to High</option>
                      <option value="highToLow">Price: High to Low</option>
                      <option value="newFirst">Newest First</option>
                      <option value="oldFirst">Oldest First</option>
                    </select>
                  </div>
                
            </div>
        </div>
        
        <div class="right-middle">
            
            <button class="w3-button w3-black left-btn slider-btn" onclick="plusDivs(-1)">&#10094;</button>
            <button class="w3-button w3-black right-btn slider-btn" onclick="plusDivs(1)">&#10095;</button>
            <div class="w3-content w3-display-container image-slider-wrapper">
                @foreach ($featuredImages as $image)
                <img class="mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%; ">
                @endforeach
                
            </div>
            
        </div>
        <div class="right-bottom">
            <div class="card-container infinite-scroll" id="product-container">
                @include('partials.productList', ['products' => $products])
                
            </div>
            
            
    </div>
</div>
<div class="messageButton">
    <a href="{{ route('Yonder/Chat') }}"><img src="{{ asset('img/message-icon-full.svg') }}" alt=""></a>
</div>
<script>
    let isHeartClicked = false;
    var slideIndex = 1;
    var interval;
    showDivs(slideIndex);
    autoStart();

    function plusDivs(n) {
      showDivs(slideIndex += n);
      resetInterval();
      //console.log(slideIndex);
    }
    
    function showDivs(n) {
      var i;
      var x = document.getElementsByClassName("mySlides");
      if (n > x.length) {
        slideIndex = 1
    }
      if (n < 1) {
        slideIndex = x.length
    }
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
      }
      x[slideIndex-1].style.display = "block";  
    }
    // 5sec every move yung banner
    function autoStart(){
        interval = setInterval(function() {
        plusDivs(1);
        }, 5000);
    }
    
    function resetInterval(){
        clearInterval(interval);
        autoStart();
    }
    
    $(document).on('click', '.heart-icon', function(event) {
        event.preventDefault();     
        event.stopPropagation();
        var productId = $(this).data('product-id');
        var heart = $(this);
        
        $.ajax({
            url: "{{ route('wishlist.toggle') }}", 
            method: 'POST',
            data: {
                product_id: productId,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                
            }
        });
    });


    $(document).on('click', '.heart-icon', function() {
        isHeartClicked = true;
        console.log('isHeartClicked=true');
        $(this).toggleClass('red gray');
        document.getElementById("card-link").addEventListener("click", function(event){
                event.preventDefault()
                event.stopPropagation();
                
                
        });     
    });

    let subMenu= document.getElementById("subMenu");
    // fuction toggleMenu(){
    //     console.log('clicked');
    //     subMenu.classList.toggle("open-menu");
    // }
    $(document).on('click', '#nav-profile', function() {
        console.log('clicked1');
        subMenu.classList.toggle("active");
    });
    // pang ano to para clickable yung image na heart sa nav bar :)
    document.addEventListener('DOMContentLoaded', function () {
        const wishlistButtons = document.querySelectorAll('.wishlistBtn');
        const cartButton = document.querySelectorAll('.cartBtn');
        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });

        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
                
            })
        })
    });
    // yung mga next kasi na items di nagana yung a link kaya nilagyan ko ganto
    // may timeout para mauna mag execute yung heart function :3
    function hrefClick(cardElement){
        const input = cardElement.querySelector('#cardLinkFromInput');
        setTimeout(function() {
            if (!isHeartClicked) {
            console.log('clicked');
            window.location.href = input.value;
            
        }
        }, 100);
        
            isHeartClicked = false;
            console.log(isHeartClicked);
    }

    // notification 
    document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.querySelector('.notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Toggle dropdown
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = notificationDropdown.style.display === 'block';
        notificationDropdown.style.display = isVisible ? 'none' : 'block';
        
        // Add class to body to prevent scrolling when dropdown is open on mobile
        document.body.style.overflow = isVisible ? 'auto' : 'hidden';
        
        if (!isVisible) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
            closeDropdown();
        }
    });

    // Close dropdown when clicking the close button (mobile)
    const closeButton = document.querySelector('.closeButton');
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
                closeDropdown();
        });
    }

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    function closeDropdown() {
        notificationDropdown.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Add touch events for mobile
    let touchStartY = 0;
    let touchEndY = 0;

    notificationDropdown.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    }, false);

    notificationDropdown.addEventListener('touchmove', function(e) {
        touchEndY = e.touches[0].clientY;
        const diff = touchStartY - touchEndY;
        
        // If swiping down and at the top of the content
        if (diff < -50 && this.scrollTop === 0) {
            e.preventDefault();
            closeDropdown();
        }
    }, false);

});
    </script>
</body>
</html>
