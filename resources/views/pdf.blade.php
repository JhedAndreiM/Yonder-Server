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
    <!-- Header with logos and text -->
    <tr>
        <td style="width: 20%; text-align: left; vertical-align: top; padding-top: 10px;">
            <img src="{{ public_path('img/bpsulogo.png') }}" alt="BPSU Logo" width="90" style="vertical-align: middle;" />
        </td>
        <td style="width: 60%; text-align: center; vertical-align: top; padding-top: 10px;">
            <h3 class="bpsuName" style="margin: 0;">BATAAN PENINSULA STATE UNIVERSITY</h3>
            <h5 class="pbenName" style="margin: 0;">PRODUCTION AND BUSINESS ENTERPRISE - MAIN CAMPUS</h5>
            <h6 class="bpsuLocation" style="margin: 0;">Capitol Compound, Tenejero, Balanga City Bataan 2101, PHILIPPINES</h6>
        </td>
        <td style="width: 20%; text-align: right; vertical-align: middle; padding-top: 10px;">
            <img src="{{ public_path('img/pbenLogo.svg') }}" alt="PBEN Logo" width="100" style="vertical-align: middle;" />
        </td>
    </tr>
</table>

@if (!empty($pbenProducts) && isset($pbenProducts))
{{-- PBEN Section --}}
<div class="margin-top">
    <div style="text-align: center; margin: 0.5rem 0;">
        <h3 class="yonderName" style="margin: 0;">STATEMENT OF FINANCIAL PERFORMANCE</h3>
        <h6 style="margin: 0;">
            For the date of 
            {{
                \Carbon\Carbon::parse($from)->format('F d, Y')
            }} to {{
                \Carbon\Carbon::parse($to)->format('F d, Y')
            }}
        </h6>
    </div>
    <table class="products">
        <tr>
            <th colspan="4" style="background-color:#0075a3ff;text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                PBEN Sales Report
            </th>
        </tr>
        <tr style="background-color: #003c53ff;">
            <th style="width:20%;">Date</th>
            <th>Product Name</th>
            <th style="width:15%; text-align:center;">Quantity</th>
            <th style="width:20%; text-align:center;">Total Price</th>
        </tr>
@php
    $currentDate = null;
@endphp

        <tbody>
        @forelse($pbenProducts as $index => $item)
    @php
        $date = \Carbon\Carbon::parse($item->updated_at)->format('F j, Y');

        $nextDate = isset($pbenProducts[$index + 1])
            ? \Carbon\Carbon::parse($pbenProducts[$index + 1]->updated_at)->format('F j, Y')
            : null;

        $isLastOfDate = $date !== $nextDate; // <-- define it here
    @endphp

    <tr class="items {{ $isLastOfDate ? 'last-of-date' : '' }}">
        @if($date !== $currentDate)
            <td>{{ $date }}</td>
            @php $currentDate = $date; @endphp
        @else
            <td></td>
        @endif

        <td>{{ $item->product_name }}</td>
        <td style="text-align:center;">{{ $item->quantity }}</td>
        <td>P {{ ($item->unit_price * $item->quantity) - $item->voucher_applied }}</td>
    </tr>
        @empty
            <tr><td colspan="4">No PBEN Data Found</td></tr>
        @endforelse
        </tbody>
        @php
            $totalQuantity = $pbenProducts->sum('quantity');
        @endphp
        <tfoot style="background-color: #003c53ff !important;">
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;">Total</td>
                <td></td>
                <td>Total Quantity: </td>
                <td>{{ $totalQuantity }}</td>
            </tr>   
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;"></td>
                <td></td>
                <td>Total Amount: </td>
                <td>P {{ number_format($pbenTotal, 2) }}</td>
            </tr>   
        </tfoot>
    </table>
</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="4" style="background-color:#0075a3ff;text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                PBEN Summary of Sales
            </th>
        </tr>
        <tr style="background-color: #003c53ff;">
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Unit Price</th>
            <th>Total Sold</th>
        </tr>
        @forelse($pbenSummary as $item)
            <tr class="items">
                <td>{{ $item['product_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total_sold'] }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No PBEN Summary Data Found</td></tr>
        @endforelse
                @php
            $totalQuantity = $pbenProducts->sum('quantity');
        @endphp
        <tfoot style="background-color: #003c53ff !important;">
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;">Total</td>
                <td></td>
                <td style="text-align:center;">Total Quantity: </td>
                <td>{{ $totalQuantity }}</td>
            </tr>   
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;"></td>
                <td></td>
                <td style="text-align:center;">Total Amount: </td>
                <td>P {{ number_format($pbenTotal, 2) }}</td>
            </tr>   
        </tfoot>
    </table>
</div>
@endif

@if (!empty($studentOrgProducts) && isset($studentOrgProducts))
@if (!empty($pbenProducts) && isset($pbenProducts))
<div class="page-break"></div>
@endif




{{-- Student Org Section - FIXED --}}
<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="4" style="background-color:#0075a3ff;text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                Student Org Sales Report
            </th>
        </tr>
        <tr style="background-color: #003c53ff;">
            <th style="width:20%;">Date</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th style="width:20%; text-align:center;">Total Price</th>
        </tr>
        <tbody>
        @php
            $currentDate = null;
        @endphp
        @forelse($studentOrgProducts as $index => $item)
        @php
            $date = \Carbon\Carbon::parse($item->updated_at)->format('F j, Y');

            $nextDate = isset($studentOrgProducts[$index + 1])
                ? \Carbon\Carbon::parse($studentOrgProducts[$index + 1]->updated_at)->format('F j, Y')
                : null;

            $isLastOfDate = $date !== $nextDate;
        @endphp
            <tr class="items {{ $isLastOfDate ? 'last-of-date' : '' }}">
                @if($date !== $currentDate)
                    <td>{{ $date }}</td>
                    @php $currentDate = $date; @endphp
                @else
                    <td></td>
                @endif
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>P {{ ($item->unit_price * $item->quantity) - $item->voucher_applied }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No Student Org Data Found</td></tr>
        @endforelse
        </tbody>
        @php
            $studentTotalQuantity = $studentOrgProducts->sum('quantity');
        @endphp
        <tfoot style="background-color: #003c53ff !important;">
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;">Total</td>
                <td></td>
                <td style="text-align:center;">Total Quantity: </td>
                <td>{{ $studentTotalQuantity }}</td>
            </tr>   
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;"></td>
                <td></td>
                <td style="text-align:center;">Total Amount: </td>
                <td>P {{ number_format($studentOrgTotal, 2) }}</td>
            </tr>   
        </tfoot>
    </table>
</div>
<div class="total">Student Org Total: P {{ $studentOrgTotal }}</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="4" style="background-color:#0075a3ff;text-align: center; font-size: 1rem; padding: 0.5rem; border-bottom: 2px solid #0075a3ff;">
                Student Org Summary of Sales
            </th>
        <tr style="background-color: #003c53ff;">
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Sold</th>
        </tr>
        <tbody>
        @forelse($studentOrgSummary as $item)
            <tr class="items">
                <td>{{ $item['product_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total_sold'] }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No Student Org Summary Data Found</td></tr>
        @endforelse
        </tbody>
        @php
            $studentTotalQuantity = $studentOrgProducts->sum('quantity');
        @endphp
        <tfoot style="background-color: #003c53ff !important;">
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;">Total</td>
                <td></td>
                <td style="text-align:center;">Total Quantity: </td>
                <td>{{ $studentTotalQuantity }}</td>
            </tr>
            <tr style="color:white; font-weight:bold;">
                <td style="text-align:center;"></td>
                <td></td>
                <td style="text-align:center;">Total Amount: </td>
                <td>P {{ number_format($studentOrgTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

    <div class="footer margin-top">
        <div>&copy; Yonder</div>
    </div>
</body>
</html>