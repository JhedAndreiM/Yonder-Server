@if($cartItems->seller_id != Auth::id())
@if ($cartItems->buyer_response=='no')
<form action="{{route('cart.orderReceivedDelivered',$cartItems->cart_id)}}" method="post">
    @csrf
    <input type="hidden" value="buyer" name="role">
    <input id="filterValue" name="filterValue" type="hidden" value="{{$filters}}">
    <button class="orderReceived">Order Received</button>
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
    <button class="orderReceived">Order Delivered</button>
</form>
@else
<button class="orderReceived" style="background-color:#4CAF50; color:white;">checked</button>
@endif
@endif