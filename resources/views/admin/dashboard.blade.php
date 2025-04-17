<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    @vite('resources/css/admin-younder.css')
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
</head>
<body>
    <!-- Admin Controller yung Controller nito -->
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
    
        <div class="section-one">
            <div class="form-holder">
                <form action="{{ route('admin.featured.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="image">Upload Featured Image</label><br>
                    <input type="file" name="image" required><br><br>
                    <button type="submit">Upload</button>
                </form>
                
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
           
        </div>
        <div class="section-two">
            <h2>Unapproved Products</h2>

            @if(session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Approve</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.approve', $product->product_id) }}">
                                @csrf
                                <button type="submit">Approve</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
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
        </script>
</body>
</html>