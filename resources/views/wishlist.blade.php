@extends('Front_layouts.app')

@section('title', 'Wishlist')
@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
@vite('resources/css/wishlist.css')
@endsection

@section('content')
<div class="mainContainer">
      <div class="top">
        <h1>Wishlist</h1>
      </div>
      <div class="container">
        @include('partials.wishlistProducts', ['wishlistItems' => $wishlistItems])
      </div>
    </div>
    <script>
      let isHeartClicked = false;
      $(document).on('click', '.heart-icon', function(event) {
        isHeartClicked = true;
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
    function hrefClick(cardElement){
        const input = cardElement.querySelector('#cardLinkFromInput');
        setTimeout(function() {
            if (!isHeartClicked) {
            console.log('clicked');
            window.location.href = input.value;
        }
        }, 100);
        
            isHeartClicked = false;
    }
    </script>
@endsection