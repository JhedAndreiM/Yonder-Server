@extends('Front_layouts.org')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background-image: url("{{ asset('img/background.svg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Admin Page</title>
    @vite('resources/css/admin-org.css')
@endsection
@section('maincontent')
    <div class="container">
        <div class="left">
            <div class="left-container">
                <div class="left-one">
                    <h3>PBEN Organization</h3>
                </div>
                <div class="left-two">
                    <hr>
                </div>
                <div class="left-three"><i class="fa-solid fa-basket-shopping left-icon"></i>Products</div>
                <div class="left-four"><i class="fa-solid fa-list-check left-icon"></i>Orders</div>
                <div class="left-five"><i class="fa-solid fa-star-half-stroke left-icon"></i>Reviews</div>
                <div class="left-six"><i class="fa-solid fa-money-check-dollar left-icon"></i>Sales</div>
                <div class="left-seven"><i class="fa-solid fa-gear left-icon"></i>Settings</div>
            </div>
            <div class="add-listing">
                <a class="listing_link"href="{{ route('create.listing') }}">
                    <h3>Add Product</h3>
                </a>
            </div>
        </div>

        <div class="right">
            <div class="right-top">
                <div class="nav-container-top">
                    <div class="nav-top-one"></div>
                    <div class="nav-top-two">Name</div>
                    <div class="nav-top-three">Stock</div>
                    <div class="nav-top-four">Price</div>
                    <div class="nav-top-five">
                    </div>


                </div>

            </div>
            <div class="right-bottom">
                <div class="card-container">
                    @include('partials.adminProducts', ['products' => $products])

                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <form method="POST" action="{{ route('products.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="productId">

            <div class="modal-content">
                <div class="modal-left">
                    <div class="image-placeholder" id="previewContainer">

                        <img id="productImage" class="default-image" src="" alt="">

                    </div>
                    <input type="file" accept="image/png, image/jpeg"name="images[]" id="imageInput" multiple onchange="previewModalImages(event)">
                    <label for="imageInput">
                       Choose Image(s)
                    </label>
                </div>

                <div class="modal-right">
                    <div class="right-modal-top">
                        <input id="productID" type="hidden" name="product_id" id="">
                        <h4>Product:</h4>
                        <input id="productName" name="name" type="text" placeholder="Product Name">
                    </div>
                    <div class="right-modal-middle">
                        <div class="stockDiv">
                            <h4>Stocks:</h4>
                        <input id="productStock" name="stock" type="number">
                        </div>
                        <div class="priceDiv">
                            <h4>Price:</h4>
                            <input id="productPrice" name="price" type="text">
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
    <script>
        var modal = document.getElementById("myModal");

        function openModal(button) {
            var modal = document.getElementById("myModal");
            modal.style.display = "flex";
            document.getElementById('productName').value = button.dataset.name;
            document.getElementById('productStock').value = button.dataset.stock;
            document.getElementById('productPrice').value = button.dataset.price;
            document.getElementById('productID').value = button.dataset.id;

            const previewContainer = document.getElementById("previewContainer");
    previewContainer.innerHTML = ''; // Clear previous previews

    // Add old image as default preview
    console.log(button.dataset.fimage)
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

        function previewModalImages(event) {
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = "";

            for (let file of event.target.files) {
                const reader = new FileReader();
                const figure = document.createElement("figure");
                figure.style.margin = "10px";
                figure.style.position = "relative";

                const figCap = document.createElement("figcaption");
                figCap.innerText = "";
                figure.appendChild(figCap);

                reader.onload = function() {
                    const img = document.createElement("img");
                    img.src = reader.result;
                    img.style.width = "100px";
                    img.style.height = "100px";
                    img.style.objectFit = "cover";
                    figure.insertBefore(img, figCap);
                }

                reader.readAsDataURL(file);
                previewContainer.appendChild(figure);
            }
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            document.getElementById("previewContainer").innerHTML = "";
            document.getElementById("imageInput").value = "";
        }
    </script>
@endsection
