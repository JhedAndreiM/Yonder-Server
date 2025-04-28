@extends('Front_layouts.default')

@section('head')

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Wishlist</title>
    @vite('resources/css/wishlist.css')
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
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />

@endsection

@section('maincontent')

    <div class="mainContainer">
      <div class="top">
        <h1>Wishlist</h1>
      </div>
      <div class="container">
        @include('partials.wishlistProducts', ['wishlistItems' => $wishlistItems])
      </div>
    </div>
    <script>
      $(document).on('click', '.heart-icon', function(event) {
        console.log('wtf1');
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
              location.reload();
            }
        });
    });
    </script>
@endsection
