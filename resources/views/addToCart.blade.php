@extends('Front_layouts.app')

@section('title', 'Cart')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
@vite('resources/css/addToCart.css')
@endsection

@section('content')
<div class="mainContainer">
  <div class="top">
    <h1>My Cart</h1>
  </div>

  <div class="container">
    {{-- partial renders cards. checkboxes inside each card use form="cartCheckoutForm" --}}
    @include('partials.productCart', ['cartItems' => $cartItems])
  </div>

  <div class="total-bottom">
    <div class="bottom-container" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
      <div>
        <p class="items-count" style="font-weight: bold;">Items: {{ $totalItems }}</p>
        <p class="cart-total" style="font-weight: bold;">Total: P {{ number_format($totalAmount, 2) }}</p>
      </div>

      <div style="display:flex; gap:1rem; align-items:center;">
        <label style="user-select:none; display:flex;align-items:center; gap:10px;">
          <input type="checkbox" id="selectAll"> Select all
        </label>

        {{-- single checkout form; checkboxes from partial use form="cartCheckoutForm" --}}
        <form id="cartCheckoutForm" action="{{ route('cart.checkoutSelected') }}" method="POST" style="display:inline;">
          @csrf
          <button class="checkOutBtn" type="submit">Checkout Selected</button>
        </form>
      </div>
    </div>
  </div>
</div>
        <!-- Unique modal container -->
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="{{ asset('imgModal/cancelLogo.svg')}}" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage">Remove Item?</h3>
    <p id="uniqueConfirmMessage">Are you sure you want to remove this item?</p>
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Remove</button>
    </div>

  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("uniqueConfirmModal");
    const confirmYes = document.getElementById("uniqueConfirmYes");
    const confirmNo = document.getElementById("uniqueConfirmNo");

    let currentForm = null; // store which form triggered the modal

    // Intercept all cart delete forms
    document.querySelectorAll(".remove-item-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // stop normal submission
            currentForm = form;
            modal.style.display = "flex"; // show modal
        });
    });

    // Cancel button
    confirmNo.addEventListener("click", function () {
        modal.style.display = "none";
        currentForm = null;
    });

    // Yes/Remove button
    confirmYes.addEventListener("click", function () {
        if (currentForm) {
            currentForm.submit(); // proceed with form submission
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
  // quantity controls + debounce (same logic as before)
  document.querySelectorAll('.cart-item').forEach(item => {
    const decreaseBtn = item.querySelector('.decrease');
    const increaseBtn = item.querySelector('.increase');
    const input = item.querySelector('.quantity');
    const id = item.dataset.id;
    const stock = parseInt(item.dataset.stock || 0);
    let debounceTimer;

    function debounceUpdateQuantity(id, value){
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => updateQuantity(id, value), 300);
    }

    decreaseBtn?.addEventListener('click', () => {
      if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        debounceUpdateQuantity(id, input.value);
      }
    });

    increaseBtn?.addEventListener('click', () => {
      const maxStock = parseInt(input.getAttribute('max')) || stock;
      if (parseInt(input.value) < maxStock) {
        input.value = parseInt(input.value) + 1;
        debounceUpdateQuantity(id, input.value);
      }
    });

    input?.addEventListener('change', () => {
      let val = parseInt(input.value) || 1;
      if (val < 1) val = 1;
      if (val > stock) val = stock;
      input.value = val;
      updateQuantity(id, val);
    });
  });

  // SELECT ALL checkbox logic
  const selectAll = document.getElementById('selectAll');
  selectAll?.addEventListener('change', (e) => {
    const checked = e.target.checked;
    document.querySelectorAll('input[name="selected_items[]"]').forEach(cb => {
      if (!cb.disabled) cb.checked = checked;
    });
  });

  // UPDATE quantity via fetch (adjust to your API response shape)
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
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      if (!data.success) {
        alert(data.message || 'Failed to update quantity');
        return;
      }

      const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
      if (cartItem) {
        // per-item price update (try to use server fields)
        const priceElement = cartItem.querySelector('.div-price p');
        if (data.newTotal !== undefined) {
          priceElement.textContent = `P ${data.newTotal}`;
        } else if (data.itemTotal !== undefined) {
          priceElement.textContent = `P ${Number(data.itemTotal).toFixed(2)}`;
        } else if (data.unit_price !== undefined && data.quantity !== undefined) {
          const v = (Number(data.unit_price) * Number(data.quantity) - (Number(data.voucher_applied || 0)));
          priceElement.textContent = `P ${v.toFixed(2)}`;
        }

        // update quantity input to server canonical value
        const quantityInput = cartItem.querySelector('.quantity');
        if (data.quantity !== undefined) quantityInput.value = data.quantity;

        // disable checkbox if server reports no stock
        const checkbox = cartItem.querySelector('input[name="selected_items[]"]');
        if (checkbox) {
          if (data.availableStock !== undefined) {
            checkbox.disabled = Number(data.availableStock) <= 0;
          } else {
            if (cartItem.dataset.stock && Number(cartItem.dataset.stock) <= 0) checkbox.disabled = true;
          }
        }
      }

      // update bottom totals (if server returns them)
      const itemsCountEl = document.querySelector('.items-count');
      const cartTotalEl = document.querySelector('.cart-total');
      if (data.totalItems !== undefined) itemsCountEl.textContent = `Items: ${data.totalItems}`;
      else if (data.totalQuantity !== undefined) itemsCountEl.textContent = `Items: ${data.totalQuantity}`;

      if (data.cartTotal !== undefined) cartTotalEl.textContent = `Total: P ${data.cartTotal}`;
      else if (data.cart_total !== undefined) cartTotalEl.textContent = `Total: P ${data.cart_total}`;
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while updating the quantity. The page will refresh to show the current state.');
      location.reload();
    });
  }
});
</script>
@endsection
