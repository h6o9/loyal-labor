<?php

namespace Modules\BasicPayment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\BasicPayment\app\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    /**
     * @param Request $request
     */
    public function razorpay_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'razorpay_key'         => 'required',
            'razorpay_secret'      => 'required',
            'razorpay_currency_id' => 'required',
            'razorpay_charge'      => 'required|numeric',
            'razorpay_name'        => 'required',
            'razorpay_description' => 'required',
            'razorpay_theme_color' => 'required',
        ];
        $customMessages = [
            'razorpay_key.required'         => __('Razorpay key is required'),
            'razorpay_secret.required'      => __('Razorpay secret is required'),
            'razorpay_currency_id.required' => __('Currency is required'),
            'razorpay_charge.required'      => __('Gateway charge is required'),
            'razorpay_charge.numeric'       => __('Gateway charge should be numeric'),
            'razorpay_name.required'        => __('Name is required'),
            'razorpay_description.required' => __('Description is required'),
            'razorpay_theme_color.required' => __('Theme is required'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'razorpay_key')->update(['value' => $request->razorpay_key]);
        PaymentGateway::where('key', 'razorpay_secret')->update(['value' => $request->razorpay_secret]);
        PaymentGateway::where('key', 'razorpay_currency_id')->update(['value' => $request->razorpay_currency_id]);
        PaymentGateway::where('key', 'razorpay_charge')->update(['value' => $request->razorpay_charge]);
        PaymentGateway::where('key', 'razorpay_status')->update(['value' => $request->razorpay_status]);
        PaymentGateway::where('key', 'razorpay_name')->update(['value' => $request->razorpay_name]);
        PaymentGateway::where('key', 'razorpay_description')->update(['value' => $request->razorpay_description]);
        PaymentGateway::where('key', 'razorpay_theme_color')->update(['value' => $request->razorpay_theme_color]);

        if ($request->file('razorpay_image')) {
            $razorpay_setting        = PaymentGateway::where('key', 'razorpay_image')->first();
            $file_name               = file_upload($request->razorpay_image, 'uploads/custom-images/', $razorpay_setting->value);
            $razorpay_setting->value = $file_name;
            $razorpay_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     */
    public function flutterwave_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'flutterwave_public_key'  => 'required',
            'flutterwave_secret_key'  => 'required',
            'flutterwave_currency_id' => 'required',
            'flutterwave_charge'      => 'required|numeric',
            'flutterwave_app_name'    => 'required',
        ];
        $customMessages = [
            'flutterwave_public_key.required'  => __('Public key is required'),
            'flutterwave_secret_key.required'  => __('Secret key is required'),
            'flutterwave_currency_id.required' => __('Currency is required'),
            'flutterwave_charge.required'      => __('Gateway charge is required'),
            'flutterwave_charge.numeric'       => __('Gateway charge should be numeric'),
            'flutterwave_app_name.required'    => __('Name is required'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'flutterwave_currency_id')->update(['value' => $request->flutterwave_currency_id]);
        PaymentGateway::where('key', 'flutterwave_charge')->update(['value' => $request->flutterwave_charge]);
        PaymentGateway::where('key', 'flutterwave_public_key')->update(['value' => $request->flutterwave_public_key]);
        PaymentGateway::where('key', 'flutterwave_secret_key')->update(['value' => $request->flutterwave_secret_key]);
        PaymentGateway::where('key', 'flutterwave_app_name')->update(['value' => $request->flutterwave_app_name]);
        PaymentGateway::where('key', 'flutterwave_status')->update(['value' => $request->flutterwave_status]);

        if ($request->file('flutterwave_image')) {
            $flutterwave_setting        = PaymentGateway::where('key', 'flutterwave_image')->first();
            $file_name                  = file_upload($request->flutterwave_image, 'uploads/custom-images/', $flutterwave_setting->value);
            $flutterwave_setting->value = $file_name;
            $flutterwave_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     */
    public function paystack_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'paystack_public_key'  => 'required',
            'paystack_secret_key'  => 'required',
            'paystack_currency_id' => 'required',
            'paystack_charge'      => 'required|numeric',
        ];
        $customMessages = [
            'paystack_public_key.required'  => __('Public key is required'),
            'paystack_secret_key.required'  => __('Secret key is required'),
            'paystack_currency_id.required' => __('Currency is required'),
            'paystack_charge.required'      => __('Gateway charge is required'),
            'paystack_charge.numeric'       => __('Gateway charge should be numeric'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'paystack_currency_id')->update(['value' => $request->paystack_currency_id]);
        PaymentGateway::where('key', 'paystack_charge')->update(['value' => $request->paystack_charge]);
        PaymentGateway::where('key', 'paystack_public_key')->update(['value' => $request->paystack_public_key]);
        PaymentGateway::where('key', 'paystack_secret_key')->update(['value' => $request->paystack_secret_key]);
        PaymentGateway::where('key', 'paystack_status')->update(['value' => $request->paystack_status]);

        if ($request->file('paystack_image')) {
            $paystack_setting        = PaymentGateway::where('key', 'paystack_image')->first();
            $file_name               = file_upload($request->paystack_image, 'uploads/custom-images/', $paystack_setting->value);
            $paystack_setting->value = $file_name;
            $paystack_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     */
    public function mollie_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'mollie_key'         => 'required',
            'mollie_currency_id' => 'required',
            'mollie_charge'      => 'required|numeric',
        ];
        $customMessages = [
            'mollie_key.required'         => __('Mollie key is required'),
            'mollie_currency_id.required' => __('Currency is required'),
            'mollie_charge.required'      => __('Gateway charge is required'),
            'mollie_charge.numeric'       => __('Gateway charge should be numeric'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'mollie_currency_id')->update(['value' => $request->mollie_currency_id]);
        PaymentGateway::where('key', 'mollie_charge')->update(['value' => $request->mollie_charge]);
        PaymentGateway::where('key', 'mollie_key')->update(['value' => $request->mollie_key]);
        PaymentGateway::where('key', 'mollie_status')->update(['value' => $request->mollie_status]);

        if ($request->file('mollie_image')) {
            $mollie_setting        = PaymentGateway::where('key', 'mollie_image')->first();
            $file_name             = file_upload($request->mollie_image, 'uploads/custom-images/', $mollie_setting->value);
            $mollie_setting->value = $file_name;
            $mollie_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function instamojo_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'instamojo_client_id'     => 'required',
            'instamojo_client_secret' => 'required',
            'instamojo_currency_id'   => 'required',
            'instamojo_charge'        => 'required|numeric',
        ];
        $customMessages = [
            'instamojo_client_id.required'     => __('API key is required'),
            'instamojo_client_secret.required' => __('Auth token is required'),
            'instamojo_currency_id.required'   => __('Currency is required'),
            'instamojo_charge.required'        => __('Gateway charge is required'),
            'instamojo_charge.numeric'         => __('Gateway charge should be numeric'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'instamojo_currency_id')->update(['value' => $request->instamojo_currency_id]);
        PaymentGateway::where('key', 'instamojo_charge')->update(['value' => $request->instamojo_charge]);
        PaymentGateway::where('key', 'instamojo_client_id')->update(['value' => $request->instamojo_client_id]);
        PaymentGateway::where('key', 'instamojo_client_secret')->update(['value' => $request->instamojo_client_secret]);
        PaymentGateway::where('key', 'instamojo_status')->update(['value' => $request->instamojo_status]);
        PaymentGateway::where('key', 'instamojo_account_mode')->update(['value' => $request->instamojo_account_mode]);

        if ($request->file('instamojo_image')) {
            $instamojo_setting        = PaymentGateway::where('key', 'instamojo_image')->first();
            $file_name                = file_upload($request->instamojo_image, 'uploads/custom-images/', $instamojo_setting->value);
            $instamojo_setting->value = $file_name;
            $instamojo_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function sslcommerz_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $rules = [
            'sslcommerz_store_id'       => 'required',
            'sslcommerz_store_password' => 'required',
            'sslcommerz_charge'         => 'nullable|numeric',

        ];
        $customMessages = [
            'sslcommerz_store_id.required'       => __('Store ID is required'),
            'sslcommerz_store_password.required' => __('Store Password is required'),
        ];

        $request->validate($rules, $customMessages);

        PaymentGateway::where('key', 'sslcommerz_store_id')->update(['value' => $request->sslcommerz_store_id]);
        PaymentGateway::where('key', 'sslcommerz_store_password')->update(['value' => $request->sslcommerz_store_password]);
        PaymentGateway::where('key', 'sslcommerz_test_mode')->update(['value' => $request->sslcommerz_test_mode]);
        PaymentGateway::where('key', 'sslcommerz_localhost')->update(['value' => $request->sslcommerz_localhost]);
        PaymentGateway::where('key', 'sslcommerz_status')->update(['value' => $request->sslcommerz_status]);
        PaymentGateway::where('key', 'sslcommerz_charge')->update(['value' => $request->sslcommerz_charge]);

        if ($request->file('sslcommerz_image')) {
            $sslcommerz_setting        = PaymentGateway::where('key', 'sslcommerz_image')->first();
            $file_name                 = file_upload($request->sslcommerz_image, 'uploads/custom-images/', $sslcommerz_setting->value);
            $sslcommerz_setting->value = $file_name;
            $sslcommerz_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function crypto_update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.update');
        $request->validate(
            [
                'crypto_api_key'          => 'required',
                'crypto_charge'           => 'nullable|numeric',
                'crypto_receive_currency' => 'required',
            ],
            [
                'crypto_api_key.required'          => __('API key is required'),
                'crypto_receive_currency.required' => __('Crypto Receive Currency is required'),
            ]
        );

        PaymentGateway::where('key', 'crypto_api_key')->update(['value' => $request->crypto_api_key]);
        PaymentGateway::where('key', 'crypto_sandbox')->update(['value' => $request->crypto_sandbox]);
        PaymentGateway::where('key', 'crypto_status')->update(['value' => $request->crypto_status]);
        PaymentGateway::where('key', 'crypto_charge')->update(['value' => $request->crypto_charge]);
        PaymentGateway::where('key', 'crypto_receive_currency')->update(['value' => $request->crypto_receive_currency]);

        if ($request->file('crypto_image')) {
            $crypto_setting        = PaymentGateway::where('key', 'crypto_image')->first();
            $file_name             = file_upload($request->crypto_image, 'uploads/custom-images/', $crypto_setting->value);
            $crypto_setting->value = $file_name;
            $crypto_setting->save();
        }

        $this->put_payment_cache();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    private function put_payment_cache()
    {
        $payment_info    = PaymentGateway::get();
        $payment_setting = [];
        foreach ($payment_info as $payment_item) {
            $payment_setting[$payment_item->key] = $payment_item->value;
        }

        $payment_setting = (object) $payment_setting;
        Cache::put('payment_setting', $payment_setting);
    }
}
