<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite('resources/css/mainPage.css')
    @vite('resources/js/mainPage.js')
    <meta name="base-url" content="{{ url('/') }}">
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
    <div class="nav-search">
        <nav>
            <input type="text" placeholder="CCST ID LACE">
            <button>Search</button>
        </nav>
    </div>
    <div class="left-nav">
    <img src="{{ asset('img/heart-icon.svg') }}" alt="">
    <img src="{{ asset('img/bell-icon.svg') }}" alt="">
    <div class="vertical-line"></div>
    <img src="{{ asset('img/profile-placeholder.svg') }}" alt="">
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

    <div class="three modeOfTransaction">
        <h3>Mode of Transaction</h3>
        <button class="filter-btn" data-filter="pickup" data-filter-type="condition">Pick up</button>
        <button class="filter-btn" data-filter="deliver" data-filter-type="condition">Deliver</button>
        <button class="filter-btn" data-filter="meetup" data-filter-type="condition">Meet Up</button>
    </div>

    <div class="four condition">
        <h3>Condition</h3>
        <button class="filter-btn" data-filter="new" data-filter-type="condition">New</button>
        <button class="filter-btn" data-filter="like-new" data-filter-type="condition">Like new</button>
        <button class="filter-btn" data-filter="used" data-filter-type="condition">Used (Fair)</button>
    </div>

    <div class="five price">
        <h3>Price</h3>
        Min: <input type="number" placeholder="100" min="0" data-filter-type="condition">
        Max: <input type="number" placeholder="1000" min="0" data-filter-type="condition">
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
           <button><h6>want to sell or trade? Add a listing!</h6></button>
        </div>
    </div>
    <div class="right" id="scroll-container">

        <div class="right-top">
            <div class="right-top-left">
                <h5>Welcome, Jun! Here are the recommended</h5>
                <h5>listing for you:</h5>
            </div>

            <div class="right-top-right">
                <h5>Sort by:</h5>
                <select name="sort-by" id="sort-by">
                    <option value="volvo">Price: Low to High</option>
                    <option value="saab">Price: High to Low</option>
                    <option value="mercedes">Newest First</option>
                    <option value="audi">Oldest First</option>
                  </select>
            </div>
        </div>

        <div class="right-middle">
            basta dito yunmg featured
        </div>
        <div class="right-bottom">
            <div class="card-container infinite-scroll" id="product-container">
                @include('partials.productList', ['products' => $products])
                
            </div>
            
    </div>
</div>

</body>
</html>
