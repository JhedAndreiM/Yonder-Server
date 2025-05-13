@if($voucher->isEmpty())
    <div class="no-items-wrapper">
        <p>No Vouchers Found</p>
    </div>
@else
@foreach ($voucher as $vouchers)
                <div class="card">
                    <div class="voucher">
                        <div class="placeholder"><img src="{{ asset('img/voucher-logo.svg') }}" alt=""></div>
                        <h3 class="voucherValue">PHP 5 OFF</h3>
                    </div>
                    <h4 class="voucherDetail">PBEN's voucher that can be used for PHP 5 OFF for single use only.</h4>
                </div>
@endforeach
@endif