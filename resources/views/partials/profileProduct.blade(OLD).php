@if ($items->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($items as $cartItems)
    @php
        $images = explode(',', $cartItems->image_path);
        $firstImage = $images[0];
    @endphp
        <div class="items">
            <div class="itemsTop">
                @if(Auth::user()->role==="student")
                <a href="/Yonder/Chat/{{ $cartItems->seller_id }}" class="sellerName">{{$cartItems->seller_name}}</a>
                @elseif(Auth::user()->role==="organization")
                <a href="/Yonder/Chat/{{ $cartItems->buyer_id }}" class="sellerName">{{$cartItems->seller_name}}</a>
                @endif
                
                @if($cartItems->status == 'receive')
                    @if ($cartItems->seller_id == Auth::id())
                        <p>To Deliver</p>
                    @else
                        <p>To Receive</p>
                    @endif
                @elseif ($cartItems->status == 'pending')
                <p>Pending</p>
                @elseif ($cartItems->status == 'cancelled')
                <p>Cancelled</p>
                @elseif ($cartItems->status == 'completed')
                <p>Completed</p>
                @endif
            </div>
            <div class="itemsBottom">
                <input type="hidden" value="{{$cartItems->cart_id}}">
                @if($cartItems->image_path)

                <div class="image-placeholder">
                    <img src="{{ asset('images/' . $firstImage) }}" alt="{{ $cartItems->image_path }}">
                </div>
                @else
                <div class="image-placeholder">
                    <img src="{{ asset('img/default-product.png') }}" alt="No image available">
                </div>
                @endif
                <div class="itemsBottomLeft">
                    
                    <h2 class="productName">{{$cartItems->product_name}}</h2>
                    <h3 class="amount">Quantity: {{$cartItems->quantity}}</h3>
                    <h3 class="price">Price Per Item: P {{$cartItems->unit_price}}</h3>
                    @if($cartItems->voucher_applied==0)
                    
                    @else
                        <h3 class="voucher">Voucher Applied: P {{$cartItems->voucher_applied}}</h3>
                    
                    @endif
                </div>
                <div class="itemsBottomRight">
                    <h2 class="totalPrice">Total Amount: P {{ ($cartItems->unit_price*$cartItems->quantity)-$cartItems->voucher_applied }}</h1>
                        <div class="buttonGroups">
                            <!-- MAIN IF STATEMENT FOR RECEIVED-->
                            @if($cartItems->status == 'receive')
                                @if($cartItems->seller_id != Auth::id())
                                    @if ($cartItems->buyer_response=='no')
                                    <form action="{{route('cart.orderReceivedDelivered',$cartItems->cart_id)}}" method="post">
                                        @csrf
                                        <input type="hidden" value="buyer" name="role">
                                        <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                        <button>Order Received</button>
                                    </form>
                                    @else
                                        <button style="background-color:#4CAF50; color:white;">checked</button>
                                    @endif
                                    
                                @elseif ($cartItems->seller_id == Auth::id())
                                @if ($cartItems->seller_response=='no')
                                <form action="{{route('cart.orderReceivedDelivered',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input type="hidden" value="seller" name="role">
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Order Delivered</button>
                                </form>
                                @else
                                    <button style="background-color:#4CAF50; color:white;">checked</button>
                                @endif
                                @endif
                                <!-- IF FOR CANCEL BUTTON ( BUYER OR SALES ) -->
                                @if ($cartItems->seller_id == Auth::id())
                                <form action="{{route('cart.cancelSales',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Cancel</button>
                                </form>
                                @elseif ($cartItems->seller_id != Auth::id()) 
                                <form action="{{route('cart.cancel',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Cancel</button>
                                </form>
                                @endif
                            <!-- MAIN IF STATEMENT FOR PENDING-->
                            @elseif($cartItems->status == 'pending')
                                <!-- IF FOR CANCEL BUTTON ( BUYER OR SALES ) -->
                                @if ($cartItems->seller_id == Auth::id())
                                    @if($cartItems->buyer_id != Auth::id())
                                    <form action="{{route('cart.confirmSales',$cartItems->cart_id)}}" method="post">
                                        @csrf
                                        <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                        <button>Confirm Order</button>
                                    </form>
                                    @endif
                                <form action="{{route('cart.cancelSales',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Cancel</button>
                                </form>
                                @elseif ($cartItems->seller_id != Auth::id()) 
                                <form action="{{route('cart.cancel',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Cancel</button>
                                </form>
                                @endif
                            <!-- MAIN IF STATEMENT -->
                            @elseif($cartItems->status == 'completed')
                                @if($cartItems->seller_id == Auth::id())
                                <button
                                    class="view-receipt"
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
                                
                                    <button class="btn btn-primary rate-btn" data-itemid="{{ $cartItems->product_id}}">Rate</button>
                                    <button
                                    class="view-receipt"
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
