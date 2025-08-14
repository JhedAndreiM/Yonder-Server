<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function generate(Request $request){
        $request->validate([
            'fromDate'=> 'required|date|before_or_equal:today',
            'toDate' => 'required|date|after_or_equal:fromDate|before_or_equal:today',
        ]);
        $from = $request->input('fromDate');
        $to = $request->input('toDate');
        //dd($from,$to);

        $pbenProducts=DB::table('cart_items')
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
                'product.name as product_name',
                'cart_items.voucher_applied'
            )
            ->get();

        $studentOrgProducts=DB::table('cart_items')
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
                'product.name as product_name',
                'cart_items.voucher_applied'
            )
            ->get();

        $total = $pbenProducts->sum(function($item) {
            $subtotal = $item->quantity * $item->unit_price;
            $voucher = floatval($item->voucher_applied); 
            return $subtotal - $voucher;
        });

        $pbenGroup = $pbenProducts->groupBy('product_name');
        $pbenSummary=$pbenGroup->map(function($items, $product_name){
            $totalQuantity=$items->sum('quantity');
            $totalVoucher = $items->sum(function($item) {
                return floatval($item->voucher_applied);
            });
            $totalSold = $items->sum(function($item) {
                return ($item->quantity * $item->unit_price) - floatval($item->voucher_applied);
            });
            return [
                'product_name' => $product_name,
                'quantity' => $totalQuantity,
                'voucher_applied' => $totalVoucher,
                'unit_price' => $items->first()->unit_price,
                'total_sold' => $totalSold,
            ];
        });
        $pbenSummary = $pbenSummary->values()->toArray();
        $data= $pbenProducts->map(function($item){
            return[
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'voucher_applied' => $item->voucher_applied,
                'unit_price' => $item->unit_price,
            ];
        })->toArray();
        $pdf = Pdf::loadView('pdf',['data' => $data,'total'=>$total, 'pbenSummary'=>$pbenSummary]);
        return $pdf->stream();
    }
}
