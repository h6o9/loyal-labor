<?php

namespace Modules\KnowYourClient\app\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\KnowYourClient\app\Enums\KYCStatusEnum;
use Modules\KnowYourClient\app\Http\Requests\KycStoreRequest;
use Modules\KnowYourClient\app\Models\KycInformation;
use Modules\KnowYourClient\app\Models\KycType;

class SellerKycController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $myKycInformation = KycInformation::where('vendor_id', vendorId())->first();

        $isVerified = $myKycInformation && $myKycInformation->status == KYCStatusEnum::APPROVED;

        $kycType = KycType::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('knowyourclient::seller.index', [
            'myKycInformation' => $myKycInformation,
            'isVerified'       => $isVerified,
            'kycType'          => $kycType,
        ]);
    }

    /**
     * @param Request $request
     */
    public function store(KycStoreRequest $request)
    {
        $user = auth('web')->user();

        try {
            DB::beginTransaction();

            $kyc = KycInformation::updateOrCreate([
                'user_id'   => $user->id,
                'vendor_id' => vendorId(),
            ], [
                'kyc_type_id' => $request->kyc_type_id,
                'message'     => $request->message,
                'status'      => KYCStatusEnum::PENDING->value,
            ]);

            if ($request->hasFile('file')) {
                $file      = file_upload($request->file);
                $kyc->file = $file;
                $kyc->save();
            } else {
                throw new Exception(__('File is required.'));
            }

            $user->seller()->update([
                'is_verified' => 0,
            ]);

            DB::commit();

            notifyAdmin(
                'KYC Submission',
                "A new KYC has been submitted by {$user->seller->shop_name} ({$user->email})",
                'info',
                route('admin.kyc-list.show', $kyc->id)
            );

            return to_route('seller.kyc.index')->with([
                'message'    => __('KYC information has been submitted successfully.'),
                'alert-type' => 'success',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            logError("Error while starting transaction for KYC store", $e);

            return redirect()->back()->with([
                'message'    => __('Something went wrong, please try again later.'),
                'alert-type' => 'error',
            ]);
        }
    }
}
