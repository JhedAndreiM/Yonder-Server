@extends('Front_layouts.default')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart</title>
    @vite('resources/css/addToCart.css')
@endsection


@section('maincontent')
      <div class="mainContainer">
        <h1 class="goBack"><a href="{{ route('custom.home') }}"><img src="{{ asset('img/back-button.svg') }}" alt=""></a></h1>
        <div class="top">
          <h1>My Cart</h1>
        </div>
        <div class="container">
          @include('partials.productCart', ['cartItems' => $cartItems])
        </div>
        <div class="total-bottom">
          <div class="bottom-container"style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p class="totalPerItems" style="font-weight: bold;">Items: {{ $totalItems }}</p>
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
    <script>
      document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.cart-item').forEach(item => {
        const decreaseBtn = item.querySelector('.decrease');
        const increaseBtn = item.querySelector('.increase');
        const input = item.querySelector('.quantity');
        const stock = parseInt(item.dataset.stock);
        const id = item.dataset.id;

        decreaseBtn.addEventListener('click', () => {
            if (parseInt(input.value) > 1) {
                input.value--;
                updateQuantity(id, input.value);
            }
        });

        increaseBtn.addEventListener('click', () => {
            if (parseInt(input.value) < stock) {
                input.value++;
                updateQuantity(id, input.value);
            }
        });

        input.addEventListener('change', () => {
            let val = parseInt(input.value);
            if (val < 1) val = 1;
            if (val > stock) val = stock;
            input.value = val;
            updateQuantity(id, val);
        });
      });

    function updateQuantity(itemId, newQuantity) {
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the item's price display
            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            const priceElement = cartItem.querySelector('.div-price p');
            priceElement.textContent = `P ${data.newTotal}`;


            const totalElementPerItem = document.querySelector('.bottom-container p:first-child');
            totalElementPerItem.textContent = `Total: P ${data.totalQuantity}`;
            // Update the total at the bottom
            const totalElement = document.querySelector('.bottom-container p:last-child');
            totalElement.textContent = `Total: P ${data.cartTotal}`;

            // Update the quantity input to match server-side value
            const quantityInput = cartItem.querySelector('.quantity');
            quantityInput.value = data.quantity;
        } else {
            console.error('Update failed:', data.message);
            alert(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the quantity. The page will refresh to show the current state.');
        location.reload(); // Fallback to page reload if JavaScript update fails
    });
}
});
    </script>
@endsection