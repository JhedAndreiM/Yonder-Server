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
                
                <a href="" class="sellerName">{{$cartItems->seller_name}}</a>
                
                
                @if($cartItems->status == 'receive')
                <p>To Receive</p>
                @elseif ($cartItems->status == 'pending')
                <p>Pending</p>
                @elseif ($cartItems->status == 'cancelled')
                <p>Cancelled</p>
                @elseif ($cartItems->status == 'completed')
                <p>Completed</p>
                @endif
            </div>
            <div class="itemsBottom">
                <input type="text" value="{{$cartItems->cart_id}}">
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
                                    <button>Order Received</button>
                                @elseif ($cartItems->seller_id == Auth::id())
                                    <button>Order Delivered</button>
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
                                <form action="{{route('cart.cancelSales',$cartItems->cart_id)}}" method="post">
                                    @csrf
                                    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
                                    <button>Confirm Order</button>
                                </form>
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
                            @endif
                        </div>
                        
                </div>
            </div>
        </div>
    @endforeach
@endif
