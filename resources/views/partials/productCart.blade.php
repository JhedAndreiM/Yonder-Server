@if($cartItems->isEmpty())
  <div class="no-items-wrapper">
    <p>No items found</p>
  </div>
@else
  @foreach ($cartItems as $items)
    @php
      $images = DB::table('product_images')->where('product_id', $items->product_id)->get();
      $firstImage = $images->first();
      $available = $items->available_stock ?? $items->product_stock ?? 0;
    @endphp
<div class="card cart-item" data-id="{{ $items->cart_id }}" data-stock="{{ $available }}">
  {{-- CARD IMAGE (checkbox left of the product image) --}}
  <div class="card-image">
    <div class="card-select">
      <input aria-label="Select item" type="checkbox"
             name="selected_items[]"
             value="{{ $items->cart_id }}"
             form="cartCheckoutForm"
             class="cart-checkbox"
             {{ $available <= 0 ? 'disabled' : '' }}>
    </div>

    <div class="image-placeholder">
      @if($firstImage)
        <img src="{{ asset('images/' . $firstImage->image_path) }}" alt="Product Image">
      @else
        <img src="{{ asset('img/default-product.png') }}" alt="No image available">
      @endif
    </div>
  </div>

  {{-- CARD DETAILS --}}
  <div class="card-details">
    <h4>{{ $items->product_name }}</h4>

    <div class="div-quantity">
      <div class="quantity-controls">
        <button type="button" class="decrease">−</button>
        <input type="number" class="quantity" value="{{ $items->quantity }}" min="1" max="{{ $available }}">
        <button type="button" class="increase">+</button>
      </div>
    </div>

    <div class="div-price">
      <h4>Total Price: </h4>
      <p>P {{ number_format((($items->unit_price * $items->quantity) - $items->voucher_applied), 2) }}</p>
    </div>

    @if(!empty($items->selected_variant))
      <div class="div-variant">
        <h4>Variant: </h4>
        <p>{{ $items->selected_variant }}</p>
      </div>
    @endif

    @if($items->voucher_applied > 0)
      <div class="div-voucher">
        <h4>Voucher Amount: </h4>
        <p>P {{ number_format($items->voucher_applied, 2) }}</p>
      </div>
    @endif

    @if($available <= 0)
      <p style="color: var(--danger); font-weight: 700;">Out of stock</p>
    @endif
  </div>

  {{-- CARD FUNCTIONS --}}
<div class="card-functions">
  <form action="{{ route('cart.destroy', $items->cart_id) }}" 
        method="POST" 
        class="remove-item-form">
      @csrf
      @method('DELETE')
      <button type="submit" class="remove-button">Remove</button>
  </form>
</div>
</div>
  @endforeach
@endif
