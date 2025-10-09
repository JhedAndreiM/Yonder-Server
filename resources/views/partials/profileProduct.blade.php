@if ($items->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
@foreach ($items as $cartItems)
    @php
        $images = DB::table('product_images')
        ->where('product_id', $cartItems->product_id)
        ->get();
        $firstImage= $images->first();
    @endphp
          <div class="card" data-id="{{$cartItems->cart_id}}">
            <div class="text">
            <!-- For User Name -->
            @if(Auth::user()->role==="student")
                <a href="/Yonder/Chat/{{$cartItems->seller_id}}" class="seller">{{$cartItems->seller_name}} {{$cartItems->seller_lastname}}</a>
            @elseif(Auth::user()->role==="organization")
                <a href="/Yonder/Chat/{{$cartItems->buyer_id}}" class="seller buyer">{{$cartItems->buyer_name}} {{$cartItems->buyer_lastname}}</a>
            @endif
            <!-- End For User Name -->

            <!-- For the Status -->
            @if($cartItems->status == 'receive')
                @if ($cartItems->seller_id == Auth::id())
                    <p class="status">To Pick up</p>
                @else
                    <p class="status">To Receive</p>
                @endif
                
            @elseif ($cartItems->status == 'pending')
                <p class="status">Pending</p>
            @elseif ($cartItems->status == 'cancelled')
                <p class="status">Cancelled</p>
            @elseif ($cartItems->status == 'completed')
                <p class="status">Completed</p>
            @endif
            <!-- End For the Status -->
            </div>
            <div class="content">
              <div class="leftPart">
                <div class="placeholder">
                    <!-- For showing the first Image -->
                    @if($firstImage && $firstImage->image_path)
                    <img class="placeholder" src="{{ asset('images/' . $firstImage->image_path) }}" alt="Product Image" />
                    @else
                    <img class="placeholder" src="{{ asset('img/default-product.png') }}" alt="Product Image" />
                    @endif
                    <!-- End For showing the first Image -->
                </div>
                <div class="info">
                  <p class="productName">{{$cartItems->product_name}}</p>
                  @if(!empty($cartItems->listing_update))
                  <p>Order Date: {{ \Carbon\Carbon::parse($cartItems->listing_update)->format('F d, Y') }}</p>
                  @endif
                  <p class="price">Price Per Item: P {{$cartItems->unit_price}}</p>
                  @if($cartItems->selected_variant)
                  <p class="variants">Variant: {{$cartItems->selected_variant}}</p>
                  @endif
                    @if($cartItems->voucher_applied != 0)
                        <p class="voucher">Voucher Applied: P {{$cartItems->voucher_applied}}</p>
                    @endif
                  <p>
                    @if($cartItems->quantity <= 1)
                    {{$cartItems->quantity}} item
                    @else
                    {{$cartItems->quantity}} items
                    @endif
                  </p>
                </div>
              </div>
              <div class="rightPart">
                <div class="totalPrice">
                  <p class="price">Total Price: P {{ ($cartItems->unit_price*$cartItems->quantity)-$cartItems->voucher_applied }}</p>
                </div>
                <div class="cardButtons">
                    <!-- MAIN IF STATEMENT FOR PENDING -->
                    @if($cartItems->status=='pending')
                        @include('partials.status._pending', ['cartItems' => $cartItems, 'filters' => $filters])
                    <!-- MAIN IF STATEMENT FOR RECEIVE -->
                    @elseif($cartItems->status=='receive')
                        @include('partials.status._receive', ['cartItems' => $cartItems, 'filters' => $filters])
                    <!-- MAIN IF STATEMENT FOR COMPLETED -->
                    @elseif($cartItems->status=='completed')
                        @include('partials.status._completed', ['cartItems' => $cartItems, 'filters' => $filters])
                    @endif
                    @if($cartItems->status !== 'cancelled' && $cartItems->status !== 'completed' && $cartItems->status !=='receive')
                    <form action="{{route('cart.cancel',$cartItems->cart_id)}}" method="post">
                        @csrf
                        <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                        <button type="submit" class="cancelButton">Cancel</button>
                    </form>
                    @endif
                </div>
              </div>
            </div>
          </div>
          
    @endforeach
@endif
