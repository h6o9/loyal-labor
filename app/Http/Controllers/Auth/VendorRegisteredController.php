<?php

namespace App\Http\Controllers\Auth;

use App\Facades\MailSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VendorStoreRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\Vendor;
use App\Services\MailSenderService;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\KnowYourClient\app\Enums\KYCStatusEnum;
use Modules\KnowYourClient\app\Models\KycInformation;
use Modules\KnowYourClient\app\Models\KycType;
use Modules\PageBuilder\app\Models\CustomizeablePage;

class VendorRegisteredController extends Controller
{
    use GlobalMailTrait;
    /**
     * Display the form for joining as a seller.
     *
     * @return string
     */
    public function joinAsSeller()
    {
        if (auth()->check() && auth()->user()?->seller ?? false) {
            return back()->with([
                'alert-type' => 'warning',
                'message'    => __('Your are already a seller.'),
            ]);
        }

        $countries = Country::all();
        $pageData  = CustomizeablePage::with('translation')->where('slug', 'join-as-seller')->first();
        $kycType   = KycType::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('website.join-as-seller', compact('countries', 'pageData', 'kycType'));
    }

    /**
     * @param Request $request
     */
    public function storeSellerInfo(VendorStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            if (auth()->check()) {
                $user = auth()->user();
            } else {
                $user = $this->storeUserInfo($request);
            }

            $vendor = $this->storeVendor($request, $user);

            $this->storeStoreKyc($request, $user, $vendor);

            DB::commit();

            if (!auth()->check() && $user) {
                (new MailSenderService)->sendVerifyMailSingleUser($user);
            }
        } catch (Exception $e) {
            DB::rollBack();

            logError("Error while creating vendor", $e);

            return $this->handleMailException($e);
        }

        $notification = __('User and Shop verification links has been sent to your mail, please verify and enjoy our service');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return to_route('website.home')->with($notification);
    }

    /**
     * @param $request
     */
    private function storeUserInfo($request)
    {
        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'verification_token' => Str::random(100),
        ]);

        $user->phone      = $request->phone;
        $user->bio        = $request->bio;
        $user->country_id = $request->country_id;
        $user->state_id   = $request->state_id;
        $user->city_id    = $request->city_id;
        $user->zip_code   = $request->zip_code;
        $user->address    = $request->address;
        $user->save();

        return $user;
    }

    /**
     * @param $request
     * @param $user
     */
    private function storeVendor($request, $user)
    {
        $token = (string) Str::ulid();

        $vendor                     = new Vendor();
        $vendor->user_id            = $user->id;
        $vendor->shop_name          = $request->shop_name;
        $vendor->shop_slug          = $this->generateUniqueShopSlug($request->shop_name);
        $vendor->phone              = $request->phone;
        $vendor->email              = $request->email;
        $vendor->seo_title          = $request->shop_name;
        $vendor->seo_description    = $request->shop_name;
        $vendor->address            = $request->address ?: $user->full_address ?? 'null';
        $vendor->verification_token = $token;
        $vendor->status             = 0;
        $vendor->save();

        return $vendor;
    }

    /**
     * @param  $shopName
     * @return mixed
     */
    private function generateUniqueShopSlug($shopName)
    {
        $baseSlug = Str::slug($shopName);
        $slug     = $baseSlug;
        $i        = 1;

        while (Vendor::where('shop_slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * @param $request
     * @param $user
     */
    private function storeStoreKyc($request, $user, $vendor)
    {
        try {
            $kyc = KycInformation::updateOrCreate([
                'user_id'     => $user->id,
                'vendor_id'   => $vendor->id,
                'kyc_type_id' => $request->kyc_type,
            ], [
                'message' => 'KYC Submitted while joining as seller',
                'status'  => KYCStatusEnum::PENDING->value,
            ]);

            $kyc->file = file_upload($request->kyc_file);

            if ($kyc->save()) {
                $vendor->is_verified = 0;
                $vendor->save();
            }

            notifyAdmin(
                'KYC Submission',
                "A new KYC has been submitted by {$user->seller->shop_name} ({$user->email})",
                'info',
                route('admin.kyc-list.show', $kyc->id)
            );
        } catch (Exception $e) {
            logError("Error while starting transaction for KYC store", $e);

            throw $e;
        }
    }

    /**
     * @param $token
     */
    public function verifyShop($token)
    {
        $vendor = Vendor::where('verification_token', $token)->first();

        if (!$vendor) {
            return to_route('website.join-as-seller')->with([
                'alert-type' => 'error',
                'message'    => 'Invalid token or already verified',
            ]);
        }

        if ($vendor->status == 0) {
            $vendor->status             = 1;
            $vendor->verification_token = null;
            $vendor->save();

            try {
                [$subject, $message] = MailSender::fetchEmailTemplate('shop_verification_complete', ['shop_name' => $vendor->shop_name]);

                $link = [
                    __('SELLER DASHBOARD') => route('seller.dashboard', ['success' => 1]),
                    __('LOGIN')            => route('login'),
                ];

                if ($vendor->status == 1) {
                    MailSender::sendMail($vendor->email, $subject, $message, $link);
                }
            } catch (Exception $e) {
                logError('Verification email could not be sent', $e);

                $date    = formattedTime(now());
                $message = "Unable to send shop {$vendor->shop_name}'s verification success email to {$vendor->email} at {$date}. The error is {$e->getMessage()}";

                notifyAdmin('Verification email could not be sent', $message);
            }

            return to_route('login')->with([
                'alert-type' => 'success',
                'message'    => 'Your Shop has been verified successfully',
            ]);
        }

        return to_route('login')->with([
            'alert-type' => 'error',
            'message'    => 'Already verified!',
        ]);
    }
}
