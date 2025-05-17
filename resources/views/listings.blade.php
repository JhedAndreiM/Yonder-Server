@extends('Front_layouts.default')

@section('head')

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/listings.css')
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
@endsection

@section('maincontent')
<h1 class="goBack"><a href="{{ route('custom.home') }}">&#x226A; <span>Go Back</span></a></h1>
<div class="mainContainer">
    <div class="top">
        <h1>Profile</h1>
    </div>
    <div class="container">
        <div class="leftPart">
            <div class="leftPartItems">
                <div class="profilePlace_profile"><img class="profile_link_profile" src="{{ asset('storage/users-avatar/'. Auth::user()->avatar) }}" alt="" id="nav-profile"></div>
                <h3 class="h3">{{ Auth::user()->name }}</h3>
            </div>
            <hr>
            <div class="leftPartItems2">
                <a href="{{ route('student.profile') }}" >My Purchases</a>
                    <a href="{{ route('listing.seller') }}" class="current">My Listings</a>
                    <a href="{{ route('show.vouchers') }}">My Vouchers</a>
                    <a href="{{ route('student.sales') }}">My Sales</a>
            </div>
        </div>
        <div class="rightPart">
            <h2 class="yourListing">Your Listing(s):</h2>
            <div class="cardContainer">
                
                @include('partials.myListing',['products' => $products])
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <form method="POST" action="{{ route('products.updateSeller') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="productId">

            <div class="modal-content">
                <div class="modal-left">
                    <div class="image-placeholder" id="previewContainer">

                    </div>
                    
                </div>

                <div class="modal-right">
                    <div class="right-modal-top">
                        <input id="productID" type="hidden" name="product_id" id="">
                        <h4>Product:</h4>
                        <input id="productName" name="name" type="text" placeholder="Product Name" readonly>
                    </div>
                    <div class="right-modal-middle">
                        <div class="stockDiv">
                            <h4>Stocks:</h4>
                        <input id="productStock" name="stock" type="number" min="1">
                        </div>
                        <div class="priceDiv">
                            <h4>Price:</h4>
                            <input id="productPrice" name="price" type="text" readonly>
                        </div>
                        
                    </div>
                    <div class="right-modal-bottom">
                        <button class="modal-button cancel"type="button" onclick="closeModal()">Cancel</button>
                        <button class="modal-button submit"type="submit">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="confirmModal" class="confirmModal">
        <div class="confirmModalContent">
                <form action="{{ route('delete.listing') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="productIDDelete" >
                <div class="closeDiv" onclick="closeConfirmModal()">&times;</div>
                <div class="confirmModalText">
                    <h2>Are you sure you want to delete this item?</h2>
                    <h4>You can't recover this item after deleting</h4>
                </div>
                <div class="confirmModalButtons">
                    <button type="button" class="cancelButton" onclick="closeConfirmModal()">Cancel</button>
                    <button type="submit" class="confirmButton">Confirm</button>
                </div>
            </form>
            
            
        </div>
    </div>
    <script>
        var modal = document.getElementById("myModal");

        function openModal(button) {
            var modal = document.getElementById("myModal");
            modal.style.display = "flex";
            document.getElementById('productName').value = button.dataset.name;
            document.getElementById('productStock').value = button.dataset.stock;
            document.getElementById('productPrice').value = button.dataset.price;
            document.getElementById('productID').value = button.dataset.id;
            if (button.dataset.fimage) {
        console.log('went here');
        const img = document.createElement("img");
        img.id = "productImage";
        img.src = `${button.dataset.fimage}`;
        img.className = "default-image";
        img.style.objectFit = "cover";
        previewContainer.appendChild(img);
    }
        };
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            document.getElementById("previewContainer").innerHTML = "";
            document.getElementById("imageInput").value = "";
        }


        function openModalRemove(button) {
            var modal = document.getElementById("confirmModal");
            modal.style.display = "flex";
            console.log(button.dataset.id);
            document.getElementById('productIDDelete').value = button.dataset.id;
        }
        function closeConfirmModal(){
            document.getElementById("confirmModal").style.display = "none";
        }
    </script>
@endsection
