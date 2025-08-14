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
          <div class="card">
            <div class="text">
            <!-- For User Name -->
            @if(Auth::user()->role==="student")
                <a href="/Yonder/Chat/{{$cartItems->seller_id}}" class="seller">{{$cartItems->seller_name}}</a>
            @elseif(Auth::user()->role==="organization")
                <a href="/Yonder/Chat/{{$cartItems->buyer_id}}" class="seller buyer">{{$cartItems->buyer_name}}</a>
            @endif
            <!-- End For User Name -->

            <!-- For the Status -->
            @if($cartItems->status == 'receive')
                @if ($cartItems->seller_id == Auth::id())
                    <p class="status">To Deliver</p>
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
                  <p>{{$cartItems->product_name}}</p>
                  <p class="price">Price Per Item: P {{$cartItems->unit_price}}</p>
                  <p>
                    @if($cartItems->quantity <= 1)
                    {{$cartItems->quantity}} item
                    @else
                    {{$cartItems->quantity}} items
                    @endif
                  </p>
                    @if($cartItems->voucher_applied==0)
                    @else
                        <p class="voucher">Voucher Applied: P {{$cartItems->voucher_applied}}</p>
                    @endif
                </div>
              </div>
              <div class="rightPart">
                <div class="totalPrice">
                  <p class="price">Total Price: P {{ ($cartItems->unit_price*$cartItems->quantity)-$cartItems->voucher_applied }}</p>
                </div>
                <div class="cardButtons">
                    <!-- MAIN IF STATEMENT FOR PENDING -->
                    @if($cartItems->status=='pending')
                        <!-- For Seller to Confirm an Order -->
                        @if($cartItems->seller_id == Auth::id())
                            <form action="{{route('cart.confirmSales', $cartItems->cart_id)}}" method="POST">
                                @csrf
                                <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                <button>Confirm Order</button>
                            </form>
                            <form action="{{route('cart.cancelSales', $cartItems->cart_id)}}" method="post">
                                @csrf
                                <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                <button class="cancelButton">Cancel</button>
                            </form>
                        @elseif ($cartItems->seller_id != Auth::id()) 
                            <form action="{{route('cart.cancel',$cartItems->cart_id)}}" method="post">
                                @csrf
                                <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                <button class="cancelButton">Cancel</button>
                            </form>
                        @endif
                    <!-- MAIN IF STATEMENT FOR RECEIVE -->
                    @elseif($cartItems->status=='receive')
                        @if($cartItems->seller_id != Auth::id())
                            @if ($cartItems->buyer_response=='no')
                                <form action="{{route('cart.orderReceivedDelivered',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input type="hidden" value="buyer" name="role">
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button class="cancelButton">Order Received</button>
                                </form>
                            @else
                                <button class="cancelButton" style="background-color:#4CAF50; color:white;">checked</button>
                            @endif
                                    
                        @elseif ($cartItems->seller_id == Auth::id())
                            @if ($cartItems->seller_response=='no')
                                <form action="{{route('cart.orderReceivedDelivered',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input type="hidden" value="seller" name="role">
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button class="cancelButton">Order Delivered</button>
                                </form>
                            @else
                                <button class="cancelButton" style="background-color:#4CAF50; color:white;">checked</button>
                            @endif
                        @endif
                    <!-- MAIN IF STATEMENT FOR COMPLETED -->
                    @elseif($cartItems->status=='completed')
                        @if($cartItems->seller_id == Auth::id())
                            <button
                                class="view-receipt cancelButton"
                                data-bs-toggle="modal"
                                data-bs-target="#myModal"
                                data-names="{{ $cartItems->product_name }}"
                                data-prices="{{ $cartItems->unit_price }}"
                                data-qtys="{{ $cartItems->quantity }}"
                                data-vouchers="{{ $cartItems->voucher_applied }}"
                                data-id="{{ $cartItems->cart_id }}"
                                data-date="{{ $cartItems->formatted_updated_at ?? $cartItems->updated_at}}"
                                onclick="openProductModalSeller(this)"
                                >Receipt</button>
                        @else
                                
                            <button class="btn btn-primary rate-btn cancelButton" data-itemid="{{ $cartItems->product_id}}">Review</button>
                            <button
                                class="view-receipt cancelButton"
                                data-bs-toggle="modal"
                                data-bs-target="#myModal"
                                data-name="{{ $cartItems->product_name }}"
                                data-price="{{ $cartItems->unit_price }}"
                                data-qty="{{ $cartItems->quantity }}"
                                data-voucher="{{ $cartItems->voucher_applied }}"
                                data-id="{{ $cartItems->cart_id }}"
                                data-date="{{ $cartItems->formatted_updated_at }}"
                                onclick="openProductModal(this)"
                                >Receipt</button>
                        @endif
                    @endif
                </div>
              </div>
            </div>
          </div>
    @endforeach
@endif