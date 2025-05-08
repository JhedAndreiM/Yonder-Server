@extends('Front_layouts.default')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Cart</title>
    @vite('resources/css/addToCart.css')
@endsection


@section('maincontent')
      <div class="mainContainer">
        <div class="top">
          <h1>My Cart</h1>
        </div>
        <div class="container">
          @include('partials.productCart', ['cartItems' => $cartItems])
        </div>
        <div class="total-bottom">
          <div class="bottom-container"style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-weight: bold;">Items: {{ $totalItems }}</p>
                <p style="font-weight: bold;">Total: P {{ number_format($totalAmount, 2) }}</p>
            </div>
            <form action="{{ route('cart.checkoutAll') }}" method="POST">
                @csrf
                <button class="checkOutBtn"type="submit" >
                    Checkout All
                </button>
            </form>
        </div>
        </div>
      </div>
@endsection