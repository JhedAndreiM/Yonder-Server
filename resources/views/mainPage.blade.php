@php
  $activeTopFilter = request('topFilter') ?? session('topFilter') ?? 'featured';
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite('resources/css/homepage.css')
    @vite('resources/js/homepage.js')
  </head>
  <body>
    <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

      <div class="navBarMiddle">
        <div class="searchBtnImg"><img id="magnifying"class="searchBtn" src="{{ asset('img/search-icon.svg') }}" alt="" /></div>
        <div class="searchInput"><input id="searchInput" class="search" type="text" placeholder="search" /></div>
      </div>

      <div class="navBarRight">
        <img class="hover" src="{{ asset('img/help.png') }}" alt="" />
        <div class="dropdown-container">
    <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="" />
    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
      <div class="notification-header">
        <h3>Notifications</h3>
      </div>
      <div class="notification-list">
        @if ($notifications->isEmpty())
          <p style="padding-left:10px;">No notifications</p>
        @else
          @foreach ($notifications as $notification)
            <div class="notification">
              <div class="title">
                <h1>
                  @if($notification['title'] === "Product Approved")
                    <span style="color:Green;">{{ $notification['title'] }}</span>
                  @elseif($notification['title'] === "Product Rejected")
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
  </div>
        <img class="hover wishlistBtn" src="{{ asset('img/wishlist.png') }}" alt=""/>
        <img class="hover cartBtn" src="{{ asset('img/cart.png') }}" alt="" />
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li><a href="{{ route('student.profile') }}">My Profile</a></li>
        <li><a href=" ">Wishlist</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->
    <div class="floating">
      <a class="listing_link"href="{{ route('create.listing') }}"><img src="{{ asset('img/add(2).svg') }}" alt="" /></a>
      <a href="{{ route('Yonder/Chat') }}"><img src="{{ asset('img/message.png') }}" alt="" /></a>
    </div>

    <div class="container">
      <div class="filter">
        <h2>Filter</h2>
        <h3>Price</h3>
        <div class="filterBtn">
          <input id="min"class="input-min priceInput" type="number" placeholder="Min" min="0" data-filter-type="condition">
          <input id="max"class="input-max priceInput" type="number" placeholder="Max" min="0" data-filter-type="condition">
        </div>
        @if($activeTopFilter==='marketplace')
        <h3>For</h3>
        <button class="filter-btn" data-filter="sale" data-filter-type="condition">Sale</button>
        <button class="filter-btn" data-filter="trade" data-filter-type="condition">Trade</button>
        <h3>Product Quality</h3>
        <button class="filter-btn" data-filter="new" data-filter-type="condition">New</button>
        <button class="filter-btn" data-filter="like-new" data-filter-type="condition">Like-new</button>
        <button class="filter-btn" data-filter="used" data-filter-type="condition">Used</button>
        @endif
        @if($activeTopFilter==='featured' || $activeTopFilter==='marketplace')
        @if($colleges)
        <h3>Colleges</h3>
        <div class="filterBtn">
          @foreach($colleges as $college)
            <button class="filter-btn" data-filter="{{ strtolower($college->code) }}"  data-filter-type="condition">{{ $college->code }}</button>
          @endforeach
        </div>
        @endif
        @endif

        @if($activeTopFilter==='student-org')
        @if($student_orgs)
        <h3>Student Organization</h3>
        <div class="filterBtn">
          @foreach($student_orgs as $student_org)
            <button class="filter-btn" data-filter="{{ strtolower($student_org->id) }}"  data-filter-type="condition">{{ $student_org->code }}</button>
          @endforeach
        </div>
        @endif
        @endif
      </div>
      <div class="content">
        <div class="textContent">
          

      <div class="left">
        <form class="buttonForm" action="" method="GET" id="filterForm">
            <a 
                href="{{ route('student.dashboard', ['topFilter' => 'featured']) }}" 
                class="mainFilterButtons {{ $activeTopFilter === 'featured' ? 'current' : 'notCurrent' }}">
                Featured Items
            </a>
          @if (\App\Models\disableButtons::getValue('show_student_org'))
            <a 
                href="{{ route('student.dashboard', ['topFilter' => 'student-org']) }}" 
                class="mainFilterButtons {{ $activeTopFilter === 'student-org' ? 'current' : 'notCurrent' }}">
                Student Organization
            </a>
          @endif

          @if (\App\Models\disableButtons::getValue('show_marketplace'))
            <a 
                href="{{ route('student.dashboard', ['topFilter' => 'marketplace']) }}" 
                class="mainFilterButtons {{ $activeTopFilter === 'marketplace' ? 'current' : 'notCurrent' }}">
                Marketplace
            </a>
          @endif
        </form>
      </div>
          <div class="right">
            <div id="sort-dropdown" class="sort-dropdown">
                    <select name="sort-by" id="sort-by">
                      <option value="" disabled selected hidden>Sort Here</option>
                      <option value="lowToHigh">Price: Low to High</option>
                      <option value="highToLow">Price: High to Low</option>
                      <option value="newFirst">Newest First</option>
                      <option value="oldFirst">Oldest First</option>
                    </select>
                  </div>
          </div>
        </div>
        <div class="items">
            <div class="slideshow-container">
                @foreach ($featuredImages as $image)
                    <img class="banner mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%;">
                @endforeach

                <!-- Prev/Next Buttons -->
                <a class="prev" onclick="plusDivs(-1)">&#10094;</a>
                <a class="next" onclick="plusDivs(1)">&#10095;</a>
            </div>

            <div class="cardContainer">
                <div class="card-container infinite-scroll" id="product-container">
                    @include('partials.productList', ['products' => $products])
                </div>
            </div>
        </div>
      </div>
    </div>
    <script>
      
    
    let isHeartClicked = false;
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
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const cartButton = document.querySelectorAll('.cartBtn');
        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });
         // cart button
        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
                
            })
        });
      
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
    //     document.querySelectorAll('.mainFilterButtons').forEach(button => {
    //     button.addEventListener('click', () => {
    //         setTimeout(() => {
    //             window.location.reload();
    //         }, 0.1); 
    //     });
    // });

    document.querySelectorAll('.wishlist-icon').forEach(function (icon) {
    icon.addEventListener('click', function (event) {
      event.stopPropagation(); 
      isHeartClicked = true;

      const currentSrc = icon.getAttribute('src');
      const grayHeart = "{{ asset('img/wishlist.png') }}";
      const redHeart = "{{ asset('img/wishlist-red.png') }}";

      icon.setAttribute(
        'src',
        currentSrc.includes('wishlist-red.png') ? grayHeart : redHeart
      );

      event.preventDefault();     
        event.stopPropagation();
        console.log("clicked");
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
                console.log("worked");
            }
        });
    });
  });

    </script>
  </body>
</html>
