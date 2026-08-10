<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\Product\app\Models\ProductTos;
use Yajra\DataTables\Facades\DataTables;

class SellerProductTosController extends Controller
{
    use RedirectHelperTrait;

    public function productReturnPolicy(Request $request)
    {
        try {
            $query = ProductTos::where('vendor_id', vendorId())->latest();

            if ($request->ajax()) {
                return DataTables::of($query)
                    ->addColumn('answer_col', function ($returnPolicy) {
                        return $returnPolicy->answer ? e(str($returnPolicy->answer)->limit(300)) : '';
                    })
                    ->addColumn('status_badge', function ($returnPolicy) {
                        return '<span class="badge badge-' . ($returnPolicy->status == 1 ? 'success' : 'danger') . '">'
                            . ($returnPolicy->status == 1 ? __('Active') : __('Inactive')) . '</span>';
                    })
                    ->addColumn('action', function ($returnPolicy) {
                        return '<a class="btn btn-sm btn-danger" href="javascript:;" onclick="deleteData(' . $returnPolicy->id . ')"><i class="fas fa-trash"></i> ' . __('Delete') . '</a>';
                    })
                    ->rawColumns(['status_badge', 'action'])
                    ->make(true);
            }

            return view('vendor::products.return-policy.index');
        } catch (\Exception $ex) {
            logError("Product Return Policy Error", $ex);

            return back()->with([
                'message'    => __('Something Went Wrong'),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param $id
     */
    public function productReturnPolicyDelete($id)
    {
        try {
            $returnPolicies = ProductTos::where('vendor_id', vendorId())->findOrFail($id);

            if ($returnPolicies->products()->count() > 0) {
                return back()->with([
                    'message'    => __('Return policy deletion failed. Return policy has products'),
                    'alert-type' => 'error',
                ]);
            }

            $returnPolicies->delete();

            return back()->with([
                'message'    => __('Deleted successfully'),
                'alert-type' => 'success',
            ]);
        } catch (\Exception $ex) {
            logError("Product Return Policy Delete Error", $ex);

            return back()->with([
                'message'    => __('Something Went Wrong'),
                'alert-type' => 'error',
            ]);
        }
    }

}
