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
    <!-- Row 1: University headings -->
    <tr>
        <td style="text-align: center;">
            <h3 class="bpsuName" style="margin: 0;">BATAAN PENINSULA STATE UNIVERSITY</h3>
            <h5 class="pbenName" style="margin: 0;">PRODUCTION AND BUSINESS ENTERPRISE - MAIN CAMPUS</h5>
            <h6 class="bpsuLocation" style="margin: 0;">Capitol Compound, Tenejero, Balanga City Bataan 2101, PHILIPPINES</h6>
        </td>
    </tr>

    <!-- Row 2: Logos (closer together) -->
    <tr>
        <td style="text-align: center; padding-top: 10px;">
            <img src="{{ public_path('img/pbenLogo.svg') }}" alt="Left Logo" width="100" style="margin: 0 10px; vertical-align: middle;" />
            <img src="{{ public_path('img/pbenLogo.svg') }}" alt="Center Logo" width="100" style="margin: 0 10px; vertical-align: middle;" />
            <img src="{{ public_path('img/logo.svg') }}" alt="Right Logo" width="100" style="margin: 0 10px; vertical-align: middle;" />
        </td>
    </tr>
</table>

 
{{-- PBEN Section --}}
<div class="margin-top">
    <div style="text-align: center; margin: 0.5rem 0;">
        <h3 class="yonderName" style="margin: 0;">YONDER</h3>
        <h3 class="yonderName" style="margin: 0;">STATEMENT OF FINANCIAL PERFORMANCE</h3>
        <h6 style="margin: 0;">For the date of </h6>
    </div>
    <table class="products">
        <tr>
            <th colspan="4" style="text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                PBEN List of Items
            </th>
        </tr>
        <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Voucher Applied</th>
            <th>Unit Price</th>
        </tr>
        @forelse($pbenProducts as $item)
            <tr class="items">
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->voucher_applied }}</td>
                <td>{{ $item->unit_price }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No PBEN Data Found</td></tr>
        @endforelse
    </table>
</div>
<div class="total">PBEN Total: P {{ $pbenTotal }}</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="5" style="text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                PBEN Summary
            </th>
        </tr>
        <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Voucher Applied</th>
            <th>Unit Price</th>
            <th>Total Sold</th>
        </tr>
        @forelse($pbenSummary as $item)
            <tr class="items">
                <td>{{ $item['product_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['voucher_applied'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total_sold'] }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No PBEN Summary Data Found</td></tr>
        @endforelse
    </table>
</div>
<div class="page-break"></div>
{{-- Student Org Section --}}
<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="4" style="text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                Student Org List of Items
            </th>
        </tr>
        <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Voucher Applied</th>
            <th>Unit Price</th>
        </tr>
        @forelse($studentOrgProducts as $item)
            <tr class="items">
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->voucher_applied }}</td>
                <td>{{ $item->unit_price }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No Student Org Data Found</td></tr>
        @endforelse
    </table>
</div>
<div class="total">Student Org Total: P {{ $studentOrgTotal }}</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="5" style="text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                Student Org Summary
            </th>
        </tr>
        <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Voucher Applied</th>
            <th>Unit Price</th>
            <th>Total Sold</th>
        </tr>
        @forelse($studentOrgSummary as $item)
            <tr class="items">
                <td>{{ $item['product_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['voucher_applied'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total_sold'] }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No Student Org Summary Data Found</td></tr>
        @endforelse
    </table>
</div>
<div class="total">Student Org Total: P {{ $studentOrgTotal }}</div>

    <div class="footer margin-top">
        <div>&copy; Yonder</div>
    </div>
</body>
</html>