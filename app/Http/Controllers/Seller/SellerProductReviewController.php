<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\app\Models\ProductReview;

class SellerProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web', 'check.vendor']);
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        $reviews = ProductReview::with('user', 'product')->orderBy('id', 'desc')
            ->when($request->filled('product'), function ($q) use ($request) {
                $q->whereHas('product', function ($query) use ($request) {
                    $query->where('slug', $request->product);
                });
            })
            ->whereHas('product', function ($q) {
                $q->where('vendor_id', vendorId());
            })->paginate(20);

        return view('vendor::product_review', compact('reviews'));
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        $review = ProductReview::whereHas('product', function ($q) {
            $q->where('vendor_id', vendorId());
        })->findOrFail($id);

        return view('seller.show_product_review', compact('review'));
    }
}
