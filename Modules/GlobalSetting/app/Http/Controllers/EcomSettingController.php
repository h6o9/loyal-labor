<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\GlobalSetting\app\Models\Setting;

class EcomSettingController extends Controller
{
    // keys
    /**
     * @var array
     */
    private $keys = [
        'has_vendor',
        'has_app',
        'can_guest_checkout',
        'marketing_status',
        'sku_prefix',
        'invoice_prefix',
        'sku_length',
        'invoice_length',
        'order_cancel_minutes_before',
        'product_commission_rate',
    ];

    /**
     * @return mixed
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $setting_info = Setting::whereIn('key', $this->keys)->get();

        $setting = [];

        foreach ($setting_info as $setting_item) {
            $setting[$setting_item->key] = $setting_item->value;
        }

        $setting = (object) $setting;

        return view('globalsetting::ecom_setting.index', [
            'ecomSetting' => $setting,
        ]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            // 'has_vendor'                  => 'required|in:0,1',
            // 'has_app'                     => 'required|in:0,1',
            'product_commission_rate'     => 'required|numeric|min:0|max:100',
            'can_guest_checkout'          => 'required|in:0,1',
            'marketing_status'            => 'required|in:0,1',
            'sku_prefix'                  => 'required|string|max:10',
            'invoice_prefix'              => 'required|string|max:10',
            'sku_length'                  => 'required|numeric|between:1,15',
            'invoice_length'              => 'required|numeric|between:1,15',
            'order_cancel_minutes_before' => 'required|numeric|min:1',
        ], [
            'has_vendor.required'                  => __('Has multi vendor is required'),
            'has_vendor.in'                        => __('Has vendor is invalid, it should be enable or disable'),
            'has_app.required'                     => __('Has app is required'),
            'has_app.in'                           => __('Has app is invalid, it should be have or does not have'),
            'can_guest_checkout.required'          => __('Can guest checkout is required'),
            'can_guest_checkout.in'                => __('Can guest checkout is invalid, it should be enable or disable'),
            'marketing_status.required'            => __('Marketing status is required'),
            'marketing_status.in'                  => __('Marketing status is invalid, it should be enable or disable'),
            'sku_prefix.required'                  => __('Sku prefix is required'),
            'sku_prefix.string'                    => __('Sku prefix is invalid'),
            'sku_prefix.max'                       => __('Sku prefix is invalid, it should be less than 10 characters'),
            'invoice_prefix.required'              => __('Invoice prefix is required'),
            'invoice_prefix.string'                => __('Invoice prefix is invalid'),
            'invoice_prefix.max'                   => __('Invoice prefix is invalid, it should be less than 10 characters'),
            'sku_length.required'                  => __('Sku length is required'),
            'sku_length.numeric'                   => __('Sku length is invalid'),
            'sku_length.between'                   => __('Sku length is invalid, it should be between 1 and 15'),
            'invoice_length.required'              => __('Invoice length is required'),
            'invoice_length.numeric'               => __('Invoice length is invalid'),
            'invoice_length.between'               => __('Invoice length is invalid, it should be between 1 and 15'),
            'order_cancel_minutes_before.required' => __('Order cancel minutes before is required'),
            'order_cancel_minutes_before.numeric'  => __('Order cancel minutes before is invalid'),
            'order_cancel_minutes_before.min'      => __('Order cancel minutes before is invalid, it should be more than 1 minute'),
            'product_commission_rate.required'     => __('Product cancel minutes before is required'),
            'product_commission_rate.numeric'      => __('Product cancel minutes before is invalid'),
            'product_commission_rate.min'          => __('Product commission rate is invalid, it should be more than 0'),
            'product_commission_rate.max'          => __('Product cancel minutes before is invalid, it should not be more than 100'),
        ]);

        // update settings
        foreach ($this->keys as $key) {
            if ($request->filled($key)) {
                Setting::where('key', $key)->update(['value' => $request->get($key)]);
            }
        }

        cache()->forget('setting');

        return back()->with([
            'message'    => __('E-commerce settings updated successfully'),
            'alert-type' => 'success',
        ]);
    }
}
