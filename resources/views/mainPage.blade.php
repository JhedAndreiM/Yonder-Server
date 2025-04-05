<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/mainPage.css')
    @vite('resources/js/mainPage.js')
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
        <button class="filter-btn" data-filter="verified">Verified suppliers</button>
        <button class="filter-btn" data-filter="students">Students</button>
    </div>

    <div class="three modeOfTransaction">
        <h3>Mode of Transaction</h3>
        <button class="filter-btn" data-filter="pickup">Pick up</button>
        <button class="filter-btn" data-filter="deliver">Deliver</button>
        <button class="filter-btn" data-filter="meetup">Meet Up</button>
    </div>

    <div class="four condition">
        <h3>Condition</h3>
        <button class="filter-btn" data-filter="new">New</button>
        <button class="filter-btn" data-filter="like-new">Like new</button>
        <button class="filter-btn" data-filter="used">Used (Fair)</button>
    </div>

    <div class="five price">
        <h3>Price</h3>
        Min: <input type="number" placeholder="100" min="0">
        Max: <input type="number" placeholder="1000" min="0">
    </div>

    <div class="six colleges">
        <h3>Colleges</h3>
        <button class="filter-btn" data-filter="ccst">CCST</button>
        <button class="filter-btn" data-filter="cea">CEA</button>
        <button class="filter-btn" data-filter="cba">CBA</button>
        <button class="filter-btn" data-filter="ctech">CTECH</button>
        <button class="filter-btn" data-filter="cahs">CAHS</button>
        <button class="filter-btn" data-filter="cas">CAS</button>
    </div>

    <div class="seven for">
        <h3>For</h3>
        <button class="filter-btn" data-filter="sale">Sale</button>
        <button class="filter-btn" data-filter="trade">Trade</button>
    </div>
        </div>
        <div class="add-listing">
           <button><h6>want to sell or trade? Add a listing!</h6></button>
        </div>
    </div>
    <div class="right">

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


        <div class="right-bottom"></div>

    </div>
</div>
</body>
</html>
