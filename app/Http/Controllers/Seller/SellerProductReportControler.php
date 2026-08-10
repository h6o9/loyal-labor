<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductReport;
use Auth;

class SellerProductReportControler extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        $seller = $user->seller;
        $reports = ProductReport::orderBy('id', 'desc')->where('seller_id', $seller->id)->get();

        return view('seller.product_report', compact('reports'));
    }

    public function show($id)
    {
        $report = ProductReport::find($id);
        $product = $report->product;
        $totalReport = ProductReport::where('product_id', $product->id)->count();

        return view('seller.show_product_report', compact('report', 'totalReport'));
    }
}
