@extends('Front_layouts.default')
@section('head')
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
@endsection

@section('maincontent')

    <div class="container">
        <div class="left">
            <div id="hiddenInputs"></div>
            <div class="filter-box">
                <div class="one title">
                    <h1>List an item</h1>
                </div>


                <div class="seven for">
                    <h3>Items are available for?</h3>
                    <button class="filter-btn" name="forSaleTrade[]"data-filter="sale"
                        data-filter-type="forSaleTrade">Sale</button>
                    <button class="filter-btn" name="forSaleTrade[]"data-filter="trade"
                        data-filter-type="forSaleTrade">Trade</button>
                </div>

                <div class="four condition">
                    <h3>What is the condition of the item?</h3>
                    <button class="filter-btn" name="product_condition[]"data-filter="new"
                        data-filter-type="product_condition">New</button>
                    <button class="filter-btn" name="product_condition[]"data-filter="like-new"
                        data-filter-type="product_condition">Like new</button>
                    <button class="filter-btn" name="product_condition[]"data-filter="used"
                        data-filter-type="product_condition">Used (Fair)</button>
                </div>

                <div class="six colleges">
                    <h3>What college/s is this item for?</h3>
                    <h6>select all that applied</h6>
                    <button class="filter-btn" name="colleges[]"data-filter="ccst"
                        data-filter-type="colleges">CCST</button>
                    <button class="filter-btn" name="colleges[]"data-filter="cea"
                        data-filter-type="colleges">CEA</button>
                    <button class="filter-btn" name="colleges[]"data-filter="cba"
                        data-filter-type="colleges">CBA</button>
                    <button class="filter-btn" name="colleges[]"data-filter="ctech"
                        data-filter-type="colleges">CTECH</button>
                    <button class="filter-btn" name="colleges[]"data-filter="cahs"
                        data-filter-type="colleges">CAHS</button>
                    <button class="filter-btn" name="colleges[]"data-filter="cas"
                        data-filter-type="colleges">CAS</button>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="top"></div>
            <div class="bottom">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="filters" id="filtersInput">
                    <div class="name-of-product">
                        <label for="productName">Product Name</label>
                        <input type="text" id="productName" name="productName">
                    </div>
    
                    <div class="price-and-stock">
                        <div class="price"><label for="productPrice">Price</label>
                            <input type="number" id="productPrice" name="productPrice">
                        </div>
    
                        <div class="stock">
                            <label for="productStocks">Stocks</label>
                            <input type="number" id="productStocks" name="productStocks">
                        </div>
                    </div>
    
                    <div class="description-of-product">
                        <label for="productDescription">Description</label>
                        <textarea id="productDescription" name="productDescription" rows="10" cols="30" style="resize: none;"></textarea>
                    </div>
    
                    <div class="image-of-product">
                        <div class="image-container">
                            <input type="file" id="productImage" accept="image/png, image/jpeg" onchange="preview()" multiple name="productImage[]">
                            <label for="productImage">
                                <i class="fas fa-upload"></i> &nbsp; Choose Image(s)
                            </label>
                            <p id="num-of-files" style="display: none;">No Files Chosen</p>
                            <div id="images"></div>
                        </div>
                        {{-- <label for="productImage">Upload Image</label>
                        <input type="file" id="productImage" name="productImage[]" multiple> --}}
                    </div>
    
                    <h3>Reminder!</h3>
                    <h5>Once your listing is submitted, it will under go for review before it is visible to others.
                        Thank you for understanding!
                    </h5>
                    <div class="button-group">
                        <button class="cancel">Cancel</button>
                        <button class="submit"type="submit">Submit</button>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Please fill out all the fields before submitting!
                        </div>
                    @endif
                </form>
    
            </div>
    
            </div>
           
        </div>
    <script>
        let fileInput = document.getElementById("productImage");
        let imageContainer = document.getElementById("images");
        let numOfFiles = document.getElementById("num-of-files");

        function preview() {
            console.log('wtf');
            imageContainer.innerHTML = "";
            numOfFiles.style.display = "";
            numOfFiles.textContent = `${fileInput.files.length} Files Selected`;

            for (i of fileInput.files) {
                let reader = new FileReader();
                let figure = document.createElement("figure");
                let figCap = document.createElement("figcaption");
                figCap.innerText = i.name;
                figure.appendChild(figCap);
                reader.onload = () => {
                    let img = document.createElement("img");
                    img.setAttribute("src", reader.result); 
                    figure.insertBefore(img, figCap);
                }
                imageContainer.appendChild(figure);
                reader.readAsDataURL(i);
            }
        }
    </script>
@endsection
