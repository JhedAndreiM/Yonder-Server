<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
    <h1 class="webName">Yonder</h2>
    <div class="nav-search">
        <nav>
            <form>
                <input id="searchInput"type="text" placeholder="CCST ID LACE" >
                <button id="btnSub" type="submit">Search</button>
                <img id="magnifying"class="fa-magnifying-glass" src="{{ asset('img/magnifying-glass-solid.svg') }}" alt="">
            </form>
            
        </nav>
    </div>
    <div class="left-nav">
        <img class="wishlistBtn" src="{{ asset('img/cart.svg') }}" alt="">
        <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
        <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
        <div class="vertical-line"></div>
        <img class="profile_link" src="{{ asset('img/profile-placeholder.svg') }}" alt="" id="nav-profile">
        <div class="sub-menu-wrapper" id="subMenu">
            <div class="sub-menu">
                <a href="{{ route('profile.page') }}" class="sub-menu-link">
                    <i class="fa-solid fa-user"></i>
                    <p>Profile</p>
                </a>
                <a href="" class="sub-menu-link">
                    <i class="fa-solid fa-gear"></i>
                    <p>Settings</p>
                    
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
                <img class="mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%; margin-bottom: 10px;">
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
    var slideIndex = 1;
    showDivs(slideIndex);
    
    function plusDivs(n) {
      showDivs(slideIndex += n);
    }
    
    function showDivs(n) {
      var i;
      var x = document.getElementsByClassName("mySlides");
      if (n > x.length) {slideIndex = 1}
      if (n < 1) {slideIndex = x.length}
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
      }
      x[slideIndex-1].style.display = "block";  
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
        $(this).toggleClass('red gray');
        document.getElementById("card-link").addEventListener("click", function(event){
                event.preventDefault()
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
    </script>
</body>
</html>
