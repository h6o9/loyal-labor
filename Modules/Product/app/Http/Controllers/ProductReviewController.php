<?php

namespace Modules\Product\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\app\Models\ProductReview;
use Yajra\DataTables\Facades\DataTables;

class ProductReviewController extends Controller
{
    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.view');

        $query = ProductReview::with('user', 'product')->orderBy('id', 'desc')
            ->when($request->filled('product'), function ($q) use ($request) {
                $q->whereHas('product', function ($query) use ($request) {
                    $query->where('slug', $request->product);
                });
            });

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('user_name', function ($review) {
                    return e(optional($review->user)->name);
                })
                ->addColumn('product_name', function ($review) {
                    if (!$review->product) {
                        return '';
                    }

                    return '<a href="' . route('seller.product.show', $review->product->id) . '">' . e($review->product->name) . '</a>';
                })
                ->addColumn('status_badge', function ($review) {
                    $checked = $review->status ? 'checked' : '';

                    if (checkAdminHasPermission('product.reviews.update')) {
                        $extra = 'onchange="status(' . $review->id . ')"';
                    } else {
                        $extra = 'disabled';
                    }

                    return '<input id="status_toggle" data-toggle="toggle" data-onlabel="' . __('Published') . '" data-offlabel="' . __('Hidden') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" ' . $extra . ' ' . $checked . '>';
                })
                ->addColumn('action', function ($review) {
                    $html = '<a class="btn btn-primary btn-sm" href="' . route('admin.show-product-review', $review->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    if (checkAdminHasPermission('product.reviews.delete')) {
                        $html .= ' <a class="btn btn-danger" href="javascript:;" onclick="deleteData(' . $review->id . ')">' . __('Delete') . '</a>';
                    }

                    return $html;
                })
                ->rawColumns(['product_name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('product::products.reviews.index');
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.view');

        $review = ProductReview::findOrFail($id);

        return view('product::products.reviews.show', compact('review'));
    }

    /**
     * @param $id
     */
    public function status($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.update');

        $review         = ProductReview::findOrFail($id);
        $review->status = !$review->status;
        $review->save();

        return response()->json([
            'status'  => true,
            'message' => __('Review status updated successfully.'),
        ]);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.delete');

        ProductReview::findOrFail($id)->delete();

        return back()->with([
            'alert-type' => 'success',
            'message'    => __('Review deleted successfully.'),
        ]);
    }
}
