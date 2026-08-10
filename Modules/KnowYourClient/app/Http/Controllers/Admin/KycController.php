<?php

namespace Modules\KnowYourClient\app\Http\Controllers\Admin;

use App\Facades\MailSender;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Modules\KnowYourClient\app\Enums\KYCStatusEnum;
use Modules\KnowYourClient\app\Models\KycInformation;
use Yajra\DataTables\Facades\DataTables;

class KycController extends Controller
{
    /**
     * @param $id
     */
    public function DestroyKyc($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc = KycInformation::findOrFail($id);

        if ($kyc->vendor_id) {
            $seller = Vendor::findOrFail($kyc->vendor_id);
            if ($seller) {
                $seller->is_verified = 0;
                $seller->save();

                try {
                    if ($seller->is_verified == 0) {
                        $subject = __('KYC Verification');
                        $message = 'Dear ' . $seller->name . '<br>' . __('Your Account KYC Verification Is Rejected, Submit again for approval');

                        $link = [
                            __('Check KYC') => route('seller.kyc.index'),
                        ];

                        MailSender::sendMail($seller->email, $subject, $message, $link);
                    }
                } catch (Exception $e) {
                    logError('Unable to send KYC rejection email to seller email ' . $seller->email, $e);
                }
            }
        }
        $kyc->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     * @param $id
     */
    public function UpdateKycStatus(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $statusEnum = KYCStatusEnum::tryFrom($request->status);

        if (!$statusEnum) {
            return redirect()->back()->with(['message' => __('Invalid Status'), 'alert-type' => 'error']);
        }

        $kyc           = KycInformation::findOrFail($id);
        $kyc->status   = $statusEnum->value;
        $kyc->admin_id = auth()->guard('admin')->user()->id;
        $kyc->save();

        $seller              = Vendor::find($kyc->vendor_id);
        $seller->is_verified = $statusEnum == KYCStatusEnum::APPROVED ? 1 : 0;
        $seller->save();

        $notification = __('Updated Successfully');

        if ($statusEnum !== KYCStatusEnum::APPROVED) {
            $notification2 = match ($kyc->status->value) {
                0 => __('Your Account KYC Verification Is Pending'),
                1 => __('Your Account Is Verified By KYC'),
                2 => __('Your Account KYC Verification Is Rejected'),
                default => __('Your Account KYC Verification Is Pending'),
            };

            $subject = __('KYC Verification');
            $message = 'Dear ' . $seller->name . '<br>' . $notification2;

            MailSender::sendMail($seller->email, $subject, $message);
        }

        if ($statusEnum == KYCStatusEnum::APPROVED) {
            $user = User::find($kyc->user_id);
            $this->sendKycMail($user, $kyc);
        }

        return redirect()->back()->with(['message' => $notification, 'alert-type' => 'success']);

    }

    public function kycList(Request $request)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $query = KycInformation::with(['type', 'shop'])->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('document', function ($kyc) {
                    return '<a href="' . asset($kyc->file) . '"><img class="img-thumbnail" src="' . asset($kyc->file) . '" alt="" width="120" style="margin: 3px !important"></a>';
                })
                ->addColumn('name_col', function ($kyc) {
                    return e($kyc->shop->shop_name ?? 'Unknown');
                })
                ->addColumn('type_name', function ($kyc) {
                    return e($kyc->type->name);
                })
                ->addColumn('status_badge', function ($kyc) {
                    return match ($kyc->status->value) {
                        0 => '<span class="badge bg-warning">' . __('Pending') . '</span>',
                        1 => '<span class="badge bg-success">' . __('Approved') . '</span>',
                        2 => '<span class="badge bg-danger">' . __('Reject') . '</span>',
                        default => '',
                    };
                })
                ->addColumn('action', function ($kyc) {
                    return '<a class="btn btn-primary btn-sm" href="' . route('admin.kyc.show', $kyc->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a> '
                        . '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" onclick="deleteData(' . $kyc->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                })
                ->rawColumns(['document', 'status_badge', 'action'])
                ->make(true);
        }

        return view('knowyourclient::admin.kyc.index');
    }

    /**
     * @param $id
     */
    public function kycListShow($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc = KycInformation::findOrFail($id);

        $kycStatusEnum = KYCStatusEnum::cases();

        return view('knowyourclient::admin.kyc.show', compact('kyc', 'kycStatusEnum'));
    }

    /**
     * @param $user
     */
    private function sendKycMail($user)
    {
        try {
            [$subject, $message] = MailSender::fetchEmailTemplate('kyc_approved', [
                'shop_name' => $user->seller->shop_name ?? $user->name,
            ]);

            $link = [
                __('Check KYC') => route('seller.kyc.index'),
            ];

            if (strtolower($user?->email) !== strtolower($user?->seller?->email)) {
                MailSender::sendMail($user->seller->email, $subject, $message, $link);
            }

            MailSender::sendMail($user->email, $subject, $message, $link);
        } catch (Exception $e) {
            logError('Unable to send KYC approval email to user email ' . $user->email, $e);
        }
    }
}
