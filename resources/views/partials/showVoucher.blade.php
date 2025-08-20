@if($voucher->isEmpty())
    <div class="no-items-wrapper">
        <p>No Vouchers Found</p>
    </div>
@else
@foreach ($voucher as $vouchers)
    <div class="card">
        <img src="{{ asset('img/voucher-logo.svg') }}" alt="Product" class="cardImg" />
        <div class="info">
            <div class="price">
            <p>P {{$vouchers->amount}}</p>
            </div>
            <p class="productDesc">
                PBEN's voucher that can be used for PHP {{$vouchers->amount}} OFF for single use only
            </p>
        </div>
    </div>
@endforeach
@endif