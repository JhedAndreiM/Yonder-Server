<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Report</title>
    <link rel="stylesheet" href="{{ public_path('pdf.css') }}" type="text/css">
</head>
<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ public_path('img/logo.svg') }}" alt="Yonder" width="200" />
            </td>
        </tr>
    </table>
 
    <div class="margin-top">
        <h2>PBEN List of Items</h2>
        <table class="products">
            <tr>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Voucher Applied</th>
                <th>Unit Price</th>
            </tr>
            @if($data)
            @foreach($data as $item)
                 <tr class="items">
                    <td class="name">
                        {{ $item['product_name'] }}
                    </td>
                    <td>
                        {{ $item['quantity'] }}
                    </td>
                    <td>
                        {{ $item['voucher_applied'] }}
                    </td>
                    <td>
                        {{ $item['unit_price'] }}
                    </td>
                 </tr>
           @endforeach
            @else
            <p>No Data Found</p>
            @endif
        </table>
    </div>
 
    <div class="total">
        Total: P {{ $total }}
    </div>


     <div class="margin-top">
        <h2>Summary</h2>
        <table class="products">
            <tr>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Voucher Applied</th>
                <th>Unit Price</th>
                <th>Total Sold</th>
            </tr>
            @if($data)
            @foreach($pbenSummary as $item)
                  <tr class="items">
                    <td class="name">
                        {{ $item['product_name'] }}
                    </td>
                    <td>
                        {{ $item['quantity'] }}
                    </td>
                    <td>
                        {{ $item['voucher_applied'] }}
                    </td>
                    <td>
                        {{ $item['unit_price'] }}
                    </td>
                    <td>
                        {{ $item['total_sold'] }}
                    </td>
                 </tr>
           @endforeach
            @else
            <p>No Data Found</p>
            @endif
        </table>
    </div>
 
    <div class="total">
        Total: P {{ $total }}
    </div>
    <div class="footer margin-top">
        <div>&copy; Yonder</div>
    </div>
</body>
</html>