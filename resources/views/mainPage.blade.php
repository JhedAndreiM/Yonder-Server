@php
  $activeTopFilter = request('topFilter') ?? session('topFilter') ?? 'featured';
@endphp
@extends('Front_layouts.app')

@section('title', 'Homepage')
@section('head')
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
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
    @vite('resources/js/app.js')
    <script>
        window.userId = {{ Auth::id() }};
    </script>
    @include('partials.common-scripts')
@endsection
@section('content')
    <div class="floating">
      <a class="listing_link"href="{{ route('create.listing') }}"><img src="{{ asset('img/add(2).svg') }}" alt="" /></a>
      <a href="#" id="chatWidgetToggle"><img src="{{ asset('img/message.png') }}" alt="" /></a>
    </div>

    <!-- Include Recent Chats Panel -->
    @include('partials.chat-recent')

    <div class="container">
      <div class="filter">
        <h2>Filter</h2>
        <h3>Price Range</h3>
        <div class="filterBtn">
          <div class="peso-input">
            <span>₱</span>
            <input id="min" class="input-min priceInput" type="number" placeholder="Min" min="0" data-filter-type="condition">
          </div>

          <div class="peso-input">
            <span>₱</span>
            <input id="max" class="input-max priceInput" type="number" placeholder="Max" min="0" data-filter-type="condition">
          </div>
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
        @if($colleges && $colleges->count() > 0)
        <h3>Colleges</h3>
        <div class="filterBtn">
          @foreach($colleges as $college)
            <button class="filter-btn" data-filter="{{ strtolower($college->code) }}"  data-filter-type="condition">{{ $college->code }}</button>
          @endforeach
        </div>
        @endif
        @endif

        @if($activeTopFilter==='student-org')
        @if($student_orgs && $student_orgs->count() > 0)
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
              <div class="navBarMiddle">
                <div class="searchBtnImg"><img id="magnifying"class="searchBtn" src="{{ asset('img/search-icon.svg') }}" alt="" /></div>
                <div class="searchInput"><input id="searchInput" class="search" type="text" placeholder="search" /></div>
              </div>     
              @if($featuredImages && $featuredImages->count())
                @foreach ($featuredImages as $image)
                    @if($image->product && $image->product->approved === 'yes')
                        <a href="{{ route('product.show', ['id' => $image->product->product_id]) }}" class="featured-image-link">
                            <img class="banner mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%;">
                            <div class="featured-product-overlay">
                                <div class="featured-product-info">
                                    <span class="featured-cta">View Product →</span>
                                </div>
                            </div>
                        </a>
                    @else
                        <img class="banner mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%;">
                    @endif
                @endforeach
                
                @if($featuredImages->count() > 1)
                    <!-- Prev/Next Buttons -->
                    <a class="prev" onclick="plusDivs(-1)">&#10094;</a>
                    <a class="next" onclick="plusDivs(1)">&#10095;</a>
                @endif
              @endif
            </div>
          
            <div class="cardContainer">
                <div class="card-container infinite-scroll" id="product-container">
                    @include('partials.productList', ['products' => $products])
                </div>
            </div>
        </div>
      </div>
    </div>
          @if (session('error'))
        <div id="errorBar" class="error-bar">
                {{session('error')}} <img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
            </div>
            <script>
                const errorbar = document.getElementById('errorBar');
                errorbar.classList.add('show');

                // Hide after 3 seconds
                setTimeout(() => {
                    errorbar.classList.remove("show");
                    setTimeout(() => bar.remove(), 400);
                }, 5000);
        </script>
        @elseif (session('success'))
        <div id="successBar" class="success-bar">
            {{session('success')}} <img src="{{ asset('imgModal/barCheckLogo.svg') }}" alt="success" class="success-icon">
        </div>
        <script>
            const bar = document.getElementById('successBar');
            bar.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                bar.classList.remove("show");
                setTimeout(() => bar.remove(), 400);
            }, 5000);
        </script>
        @endif
    <script>
      
    
    let isHeartClicked = false;
    function hrefClick(cardElement){
        console.log('wtf');
        const input = cardElement.querySelector('#cardLinkFromInput');
        setTimeout(function() {
            if (!isHeartClicked) {
            console.log('clickedone');
            window.location.href = input.value;
            
        }
        }, 100);
        
            isHeartClicked = false;
            console.log(isHeartClicked);
    }
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const cartButton = document.querySelectorAll('.cartBtn');
    const faqsBtn = document.querySelectorAll('.faqsBtn');
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
        faqsBtn.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('FAQs')}}";
                
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
      if (x.length === 0) {
        return;
      }
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
    
    document.querySelectorAll('.wishlist-icon').forEach(img => {
      img.addEventListener('click', function (event){
        event.stopPropagation();
        isHeartClicked = true;

      const currentSrc = img.getAttribute('src');
      const grayHeart = "{{ asset('img/wishlist.png') }}";
      const redHeart = "{{ asset('img/wishlist-red.png') }}";

      img.setAttribute(
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
    document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });


    </script>
@endsection