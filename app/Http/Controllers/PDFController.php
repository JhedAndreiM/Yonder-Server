<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
public function generate(Request $request)
{
    $request->validate([
        'fromDate'=> 'required|date|before_or_equal:today',
        'toDate' => 'required|date|after_or_equal:fromDate|before_or_equal:today',
    ]);

    $from = $request->input('fromDate');
    $to = $request->input('toDate');
    $reportType = $request->input('reportType', 'all');

    // Always fetch both, but only pass what is needed to the view
    $pbenProducts = DB::table('cart_items')
        ->join('product', 'cart_items.product_id', '=', 'product.product_id')
        ->where('cart_items.seller_id', Auth::id())
        ->where('cart_items.status', '=', 'completed')
        ->where('product.supplier_type', '=', 'pben')
        ->whereBetween('cart_items.updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
        ->select(
            'cart_items.id as cart_id',
            'cart_items.quantity',
            'cart_items.unit_price',
            'cart_items.product_id',
            'cart_items.updated_at',
            'product.name as product_name',
            'cart_items.voucher_applied'
        )
        ->orderBy('cart_items.updated_at', 'asc')
        ->get();

    $studentOrgProducts = DB::table('cart_items')
        ->join('product', 'cart_items.product_id', '=', 'product.product_id')
        ->where('cart_items.seller_id', Auth::id())
        ->where('cart_items.status', '=', 'completed')
        ->where('product.supplier_type', '=', 'student-org')
        ->whereBetween('cart_items.updated_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
        ->select(
            'cart_items.id as cart_id',
            'cart_items.quantity',
            'cart_items.unit_price',
            'cart_items.product_id',
            'cart_items.updated_at',
            'product.name as product_name',
            'cart_items.voucher_applied'
        )
        ->get();

    $pbenTotal = $pbenProducts->sum(fn($item) =>
        ($item->quantity * $item->unit_price) - floatval($item->voucher_applied)
    );
    $studentOrgTotal = $studentOrgProducts->sum(fn($item) =>
        ($item->quantity * $item->unit_price) - floatval($item->voucher_applied)
    );

    $pbenSummary = $pbenProducts->groupBy('product_name')->map(function($items, $product_name){
        return [
            'product_name' => $product_name,
            'quantity' => $items->sum('quantity'),
            'voucher_applied' => $items->sum(fn($item) => floatval($item->voucher_applied)),
            'unit_price' => $items->first()->unit_price,
            'total_sold' => $items->sum(fn($item) =>
                ($item->quantity * $item->unit_price) - floatval($item->voucher_applied)
            ),
        ];
    })->values()->toArray();
    $studentOrgSummary = $studentOrgProducts->groupBy('product_name')->map(function($items, $product_name){
        return [
            'product_name' => $product_name,
            'quantity' => $items->sum('quantity'),
            'voucher_applied' => $items->sum(fn($item) => floatval($item->voucher_applied)),
            'unit_price' => $items->first()->unit_price,
            'total_sold' => $items->sum(fn($item) =>
                ($item->quantity * $item->unit_price) - floatval($item->voucher_applied)
            ),
        ];
    })->values()->toArray();

    // Only pass the requested section(s)
    $viewData = [
        'from' => $from,
        'to' => $to,
        'reportType' => $reportType,
    ];
    if ($reportType === 'all' || $reportType === 'pben') {
        $viewData['pbenProducts'] = $pbenProducts;
        $viewData['pbenSummary'] = $pbenSummary;
        $viewData['pbenTotal'] = $pbenTotal;
    }
    if ($reportType === 'all' || $reportType === 'student_org') {
        $viewData['studentOrgProducts'] = $studentOrgProducts;
        $viewData['studentOrgSummary'] = $studentOrgSummary;
        $viewData['studentOrgTotal'] = $studentOrgTotal;
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf', $viewData);
    $fileName = 'sales_report_' . $from . '_to_' . $to . '.pdf';
    return $pdf->stream($fileName, ['Attachment' => false]);
}

}
