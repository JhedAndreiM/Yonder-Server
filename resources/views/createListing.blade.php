<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Listing</title>
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
     @vite('resources/css/createListing.css')
     @vite('resources/js/createListing.js')
</head>
<body>
    <header>
        <img class="menu-button"src="{{ asset('img/Menu.svg') }}" alt="">
        <h1 class="webName">Yonder</h2>
        <div class="left-nav">
        <img class="wishlistBtn" src="{{ asset('img/cart.svg') }}" alt="">
        <img class="wishlistBtn" src="{{ asset('img/heart-icon.svg') }}" alt="">
        <img class="notificationBtn" src="{{ asset('img/bell-icon.svg') }}" alt="">
        <div class="vertical-line"></div>
        <img class="profile_link" src="{{ asset('img/profile-placeholder.svg') }}" alt="">
        </div>
    </header>
    <div class="container">
        <div class="left">
            <div id="hiddenInputs"></div>
            <div class="filter-box">
                <div class="one title">
                <h1>List an item</h1>
                </div>
            

            <div class="seven for">
                <h3>Items are available for?</h3>
                <button class="filter-btn" name="forSaleTrade[]"data-filter="sale" data-filter-type="forSaleTrade">Sale</button>
                <button class="filter-btn" name="forSaleTrade[]"data-filter="trade" data-filter-type="forSaleTrade">Trade</button>
            </div>

            <div class="four condition">
                <h3>What is the condition of the item?</h3>
                <button class="filter-btn" name="product_condition[]"data-filter="new" data-filter-type="product_condition">New</button>
                <button class="filter-btn" name="product_condition[]"data-filter="like-new" data-filter-type="product_condition">Like new</button>
                <button class="filter-btn" name="product_condition[]"data-filter="used" data-filter-type="product_condition">Used (Fair)</button>
            </div>

            <div class="six colleges">
                <h3>What college/s is this item for?</h3>
                <h6>select all that applied</h6>
                <button class="filter-btn" name="colleges[]"data-filter="ccst" data-filter-type="colleges">CCST</button>
                <button class="filter-btn" name="colleges[]"data-filter="cea" data-filter-type="colleges">CEA</button>
                <button class="filter-btn" name="colleges[]"data-filter="cba" data-filter-type="colleges">CBA</button>
                <button class="filter-btn" name="colleges[]"data-filter="ctech" data-filter-type="colleges">CTECH</button>
                <button class="filter-btn" name="colleges[]"data-filter="cahs" data-filter-type="colleges">CAHS</button>
                <button class="filter-btn" name="colleges[]"data-filter="cas" data-filter-type="colleges">CAS</button>
            </div>
        </div>
        </div>
        <div class="right">
            <form action="{{ route('products.store')  }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="filters" id="filtersInput">
                <div class="name-of-product">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" name="productName">
                </div>
        
                <div class="price-and-stock">
                    <label for="productPrice">Price</label>
                    <input type="text" id="productPrice" name="productPrice">
        
                    <label for="productStocks">Stocks</label>
                    <input type="text" id="productStocks" name="productStocks">
                </div>
        
                <div class="description-of-product">
                    <label for="productDescription">Description</label>
                    <input type="text" id="productDescription" name="productDescription">
                </div>
        
                <div class="image-of-product">
                    <label for="productImage">Upload Image</label>
                    <input type="file" id="productImage" name="productImage">
                </div>

                <h3>Reminder!</h3>
                <h5>Once your listing is submitted, it will under go for review before it is visible to others.
                     Thank you for understanding!
                </h5>
                <button>Cancel</button>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    
</body>
</html>