<?php

namespace Modules\KnowYourClient\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\KnowYourClient\app\Http\Requests\KycTypeStoreRequest;
use Modules\KnowYourClient\app\Models\KycType;
use Yajra\DataTables\Facades\DataTables;

class KycTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $query = KycType::withCount('kycApplications')->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('status_badge', function ($type) {
                    return $type->status == 1
                        ? '<span class="badge badge-success">' . __('Active') . '</span>'
                        : '<span class="badge badge-danger">' . __('Inactive') . '</span>';
                })
                ->addColumn('action', function ($type) {
                    $onclick = 'editKycType(' . $type->id . ', ' . json_encode($type->name) . ', ' . json_encode($type->description ?? '') . ', ' . (int) $type->status . ')';
                    $html    = '<a class="btn btn-primary btn-sm" href="javascript:;" onclick="' . e($onclick) . '"><i class="fa fa-edit" aria-hidden="true"></i></a> ';

                    if ($type->kyc_applications_count == 0) {
                        $html .= '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" onclick="deleteData(' . $type->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    } else {
                        $html .= '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#canNotDeleteModal" href="javascript:;" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('knowyourclient::admin.type.index');
    }

    /**
     * @param Request $request
     */
    public function store(KycTypeStoreRequest $request)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyctype              = new KycType();
        $kyctype->name        = $request->name;
        $kyctype->description = $request->description;
        $kyctype->status      = $request->status;
        $kyctype->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(KycTypeStoreRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc              = KycType::find($id);
        $kyc->name        = $request->name;
        $kyc->description = $request->description;
        $kyc->status      = $request->status;
        $kyc->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        return to_route('admin.kyc-list.show', $id);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc = KycType::find($id);

        if ($kyc->kycApplications->count() > 0) {
            return redirect()->back()->with(['message' => __('Unable to delete type associated with applications'), 'alert-type' => 'error']);
        }

        $kyc->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }
}
