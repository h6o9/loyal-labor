<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogCategoryTranslation;
use Modules\Blog\app\Models\BlogComment;
use Modules\Blog\app\Models\BlogTranslation;
use Modules\Coupon\app\Models\Coupon;
use Modules\Coupon\app\Models\CouponHistory;
use Modules\Coupon\app\Models\CouponTranslation;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Models\FaqTranslation;
use Modules\Frontend\app\Models\Section;
use Modules\GlobalSetting\app\Enums\WebsiteSettingEnum;
use Modules\GlobalSetting\app\Models\AdminNotification;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\KnowYourClient\app\Models\KycInformation;
use Modules\KnowYourClient\app\Models\KycType;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderBillingAddress;
use Modules\Order\app\Models\OrderDetails;
use Modules\Order\app\Models\OrderPaymentDetails;
use Modules\Order\app\Models\OrderShippingAddress;
use Modules\Order\app\Models\OrderStatusChangeHistory;
use Modules\Order\app\Models\TransactionHistory;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Attribute;
use Modules\Product\app\Models\AttributeTranslation;
use Modules\Product\app\Models\AttributeValue;
use Modules\Product\app\Models\AttributeValueTranslation;
use Modules\Product\app\Models\Brand;
use Modules\Product\app\Models\BrandTranslation;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\CategoryTranslation;
use Modules\Product\app\Models\Gallery;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductLabel;
use Modules\Product\app\Models\ProductLabelTranslation;
use Modules\Product\app\Models\ProductTos;
use Modules\Product\app\Models\ProductTranslation;
use Modules\Product\app\Models\Tag;
use Modules\Product\app\Models\TagTranslation;
use Modules\Product\app\Models\UnitType;
use Modules\Product\app\Models\Variant;
use Modules\Product\app\Models\VariantOption;
use Modules\Shipping\app\Models\ShippingRule;
use Modules\Shipping\app\Models\ShippingRuleItem;
use Modules\Stock\app\Models\Stock;
use Modules\Tax\app\Models\Tax;
use Modules\Tax\app\Models\TaxTranslation;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Testimonial\app\Models\TestimonialTranslation;
use Modules\Wallet\app\Models\WalletHistory;
use ZipArchive;

class GlobalSettingController extends Controller
{
    /**
     * @var mixed
     */
    protected $cachedSetting;

    public function __construct()
    {
        $this->cachedSetting = Cache::get('setting');
    }

    public function general_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        $custom_paginations = CustomPagination::all();
        $all_timezones      = WebsiteSettingEnum::allTimeZones();
        $all_time_format    = WebsiteSettingEnum::allTimeFormat();
        $all_date_format    = WebsiteSettingEnum::allDateFormat();

        $setting = $this->cachedSetting;

        return view('globalsetting::settings.index', compact('custom_paginations', 'all_timezones', 'all_time_format', 'all_date_format', 'setting'));
    }

    /**
     * @param Request $request
     */
    public function update_general_setting(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'app_name'                      => 'sometimes|required',
            'timezone'                      => 'sometimes',
            'contact_message_receiver_mail' => 'sometimes|email',
            'is_queueable'                  => 'sometimes|in:active,inactive',
            'comments_auto_approved'        => 'sometimes|in:active,inactive',
            'search_engine_indexing'        => 'sometimes|in:active,inactive',
            'admin_login_prefix'            => 'sometimes|required|string|min:4|max:10',
            'admin_auth_bg_preview'         => 'nullable|image|max:512',
        ], [
            'app_name.required'                   => __('App name is required'),
            'timezone.required'                   => __('Timezone is required'),
            'is_queueable.required'               => __('Queue is required'),
            'contact_message_receiver_mail.email' => __('The contact message receiver mail must be a valid email address.'),
            'is_queueable.in'                     => __('Queue is invalid'),
            'comments_auto_approved.in'           => __('Review auto approved is invalid'),
            'search_engine_indexing.in'           => __('Search engine crawling is invalid'),
            'admin_login_prefix.required'         => __('Admin login prefix is required'),
            'admin_login_prefix.string'           => __('Admin login prefix must be a string'),
            'admin_login_prefix.min'              => __('Admin login prefix must be at least 4 characters'),
            'admin_login_prefix.max'              => __('Admin login prefix must be at most 10 characters'),
            'admin_auth_bg_preview.image'         => __('The admin auth bg preview must be an image'),
            'admin_auth_bg_preview.max'           => __('The admin auth bg preview size must be less than  512KB'),
            'admin_auth_bg_preview.image'         => __('The admin auth bg preview must be an image'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            if ($request->hasFile($key)) {
                $file_name = file_upload($request->$key, 'uploads/custom-images/', $this->cachedSetting?->$key);
                Setting::where('key', $key)->update(['value' => $file_name]);
            } else {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        Cache::forget('setting');
        Cache::forget('corn_working');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_logo_favicon(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->file('logo')) {
            $file_name = file_upload($request->logo, 'uploads/custom-images/', $this->cachedSetting?->logo);
            Setting::where('key', 'logo')->update(['value' => $file_name]);
        }

        if ($request->file('logo_dark')) {
            $file_name = file_upload($request->logo_dark, 'uploads/custom-images/', $this->cachedSetting?->logo_dark);
            Setting::where('key', 'logo_dark')->update(['value' => $file_name]);
        }

        if ($request->file('favicon')) {
            $file_name = file_upload($request->favicon, 'uploads/custom-images/', $this->cachedSetting?->favicon);
            Setting::where('key', 'favicon')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_cookie_consent(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'cookie_status'    => 'required',
            'border'           => 'required',
            'corners'          => 'required',
            'background_color' => 'required',
            'text_color'       => 'required',
            'border_color'     => 'required',
            'btn_bg_color'     => 'required',
            'btn_text_color'   => 'required',
            'link_text'        => 'required',
            'btn_text'         => 'required',
            'message'          => 'required',
        ], [
            'cookie_status.required'    => __('Status is required'),
            'border.required'           => __('Border is required'),
            'corners.required'          => __('Corner is required'),
            'background_color.required' => __('Background color is required'),
            'text_color.required'       => __('Text color is required'),
            'border_color.required'     => __('Border Color is required'),
            'btn_bg_color.required'     => __('Button color is required'),
            'btn_text_color.required'   => __('Button text color is required'),
            'link_text.required'        => __('Link text is required'),
            'btn_text.required'         => __('Button text is required'),
            'message.required'          => __('Message is required'),
        ]);

        Setting::where('key', 'cookie_status')->update(['value' => $request->cookie_status]);
        Setting::where('key', 'border')->update(['value' => $request->border]);
        Setting::where('key', 'corners')->update(['value' => $request->corners]);
        Setting::where('key', 'background_color')->update(['value' => $request->background_color]);
        Setting::where('key', 'text_color')->update(['value' => $request->text_color]);
        Setting::where('key', 'border_color')->update(['value' => $request->border_color]);
        Setting::where('key', 'btn_bg_color')->update(['value' => $request->btn_bg_color]);
        Setting::where('key', 'btn_text_color')->update(['value' => $request->btn_text_color]);
        Setting::where('key', 'link_text')->update(['value' => $request->link_text]);
        Setting::where('key', 'btn_text')->update(['value' => $request->btn_text]);
        Setting::where('key', 'message')->update(['value' => $request->message]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_custom_pagination(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        foreach ($request->quantities as $index => $quantity) {
            if ($request->quantities[$index] == '') {
                $notification = [
                    'message'    => __('Every field are required'),
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($notification);
            }

            $custom_pagination           = CustomPagination::find($request->ids[$index]);
            $custom_pagination->item_qty = $request->quantities[$index];
            $custom_pagination->save();
        }

        Cache::forget('CustomPagination');

        customPagination();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_default_avatar(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->file('default_avatar')) {
            $file_name = file_upload($request->default_avatar, 'uploads/custom-images/', $this->cachedSetting?->default_avatar);
            Setting::where('key', 'default_avatar')->update(['value' => $file_name]);
        }

        if ($request->file('default_user_image')) {
            $file_name = file_upload($request->default_user_image, 'uploads/custom-images/', $this->cachedSetting?->default_user_image);
            Setting::where('key', 'default_user_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_breadcrumb(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->file('breadcrumb_image')) {
            $file_name = file_upload($request->breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->breadcrumb_image);
            Setting::where('key', 'breadcrumb_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_copyright_text(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'copyright_text' => 'required',
        ], [
            'copyright_text' => __('Copyright Text is required'),
        ]);
        Setting::where('key', 'copyright_text')->update(['value' => $request->copyright_text]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function crediential_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::credientials.index');
    }

    /**
     * @param Request $request
     */
    public function update_google_captcha(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'recaptcha_site_key'   => 'required',
            'recaptcha_secret_key' => 'required',
            'recaptcha_status'     => 'required',
        ], [
            'recaptcha_site_key.required'   => __('Site key is required'),
            'recaptcha_secret_key.required' => __('Secret key is required'),
            'recaptcha_status.required'     => __('Status is required'),
        ]);

        Setting::where('key', 'recaptcha_site_key')->update(['value' => $request->recaptcha_site_key]);
        Setting::where('key', 'recaptcha_secret_key')->update(['value' => $request->recaptcha_secret_key]);
        Setting::where('key', 'recaptcha_status')->update(['value' => $request->recaptcha_status]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_google_tag(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'googel_tag_status' => 'required',
            'googel_tag_id'     => 'required',
        ], [
            'googel_tag_status.required' => __('Status is required'),
            'googel_tag_id.required'     => __('Google Tag ID is required'),
        ]);

        Setting::where('key', 'googel_tag_status')->update(['value' => $request->googel_tag_status]);
        Setting::where('key', 'googel_tag_id')->update(['value' => $request->googel_tag_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_tawk_chat(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'tawk_status'    => 'required',
            'tawk_chat_link' => 'required',
        ], [
            'tawk_status.required'    => __('Status is required'),
            'tawk_chat_link.required' => __('Chat link is required'),
        ]);
        if (strpos($request->tawk_chat_link, 'embed.tawk.to') !== false) {
            $embedUrl = $request->tawk_chat_link;
        } elseif (strpos($request->tawk_chat_link, 'tawk.to/chat') !== false) {
            $embedUrl = str_replace('tawk.to/chat', 'embed.tawk.to', $request->tawk_chat_link);
        } else {
            $embedUrl = 'https://embed.tawk.to/' . $request->tawk_chat_link;
        }

        Setting::where('key', 'tawk_status')->update(['value' => $request->tawk_status]);
        Setting::where('key', 'tawk_chat_link')->update(['value' => $embedUrl]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_google_analytic(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'google_analytic_status' => 'required',
            'google_analytic_id'     => 'required',
        ], [
            'google_analytic_status.required' => __('Status is required'),
            'google_analytic_id.required'     => __('Analytic id is required'),
        ]);

        Setting::where('key', 'google_analytic_status')->update(['value' => $request->google_analytic_status]);
        Setting::where('key', 'google_analytic_id')->update(['value' => $request->google_analytic_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_facebook_pixel(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pixel_status' => 'required',
            'pixel_app_id' => 'required',
        ], [
            'pixel_status.required' => __('Status is required'),
            'pixel_app_id.required' => __('App ID is required'),
        ]);

        Setting::where('key', 'pixel_status')->update(['value' => $request->pixel_status]);
        Setting::where('key', 'pixel_app_id')->update(['value' => $request->pixel_app_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_social_login(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'google_login_status' => 'required',
            'google_client_id'    => 'required',
            'google_secret_id'    => 'required',
        ];
        $customMessages = [
            'google_login_status.required' => __('Google is required'),
            'google_client_id.required'    => __('Google client is required'),
            'google_secret_id.required'    => __('Google secret is required'),
        ];
        $request->validate($rules, $customMessages);

        Setting::where('key', 'facebook_login_status')->update(['value' => $request->facebook_login_status]);
        Setting::where('key', 'facebook_app_id')->update(['value' => $request->facebook_app_id]);
        Setting::where('key', 'facebook_app_secret')->update(['value' => $request->facebook_app_secret]);
        Setting::where('key', 'facebook_redirect_url')->update(['value' => $request->facebook_redirect_url]);
        Setting::where('key', 'google_login_status')->update(['value' => $request->google_login_status]);
        Setting::where('key', 'google_client_id')->update(['value' => $request->google_client_id]);
        Setting::where('key', 'google_secret_id')->update(['value' => $request->google_secret_id]);
        Setting::where('key', 'gmail_redirect_url')->update(['value' => $request->gmail_redirect_url]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    /**
     * @param Request $request
     */
    public function update_pusher(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pusher_status'      => 'required',
            'pusher_app_id'      => 'required',
            'pusher_app_key'     => 'required',
            'pusher_app_secret'  => 'required',
            'pusher_app_cluster' => 'required',
        ], [
            'pusher_status.required'      => __('Status is required'),
            'pusher_app_id.required'      => __('Pusher App ID is required'),
            'pusher_app_key.required'     => __('Pusher App Key is required'),
            'pusher_app_secret.required'  => __('Pusher App Secret is required'),
            'pusher_app_cluster.required' => __('Pusher App Cluster is required'),
        ]);

        Setting::where('key', 'pusher_status')->update(['value' => $request->pusher_status]);
        Setting::where('key', 'pusher_app_id')->update(['value' => $request->pusher_app_id]);
        Setting::where('key', 'pusher_app_key')->update(['value' => $request->pusher_app_key]);
        Setting::where('key', 'pusher_app_secret')->update(['value' => $request->pusher_app_secret]);
        Setting::where('key', 'pusher_app_cluster')->update(['value' => $request->pusher_app_cluster]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function seo_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $pages = SeoSetting::all();

        return view('globalsetting::seo_setting', compact('pages'));
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update_seo_setting(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'seo_title'       => 'required',
            'seo_description' => 'required',
        ];
        $customMessages = [
            'seo_title.required'       => __('SEO title is required'),
            'seo_description.required' => __('SEO description is required'),
        ];
        $request->validate($rules, $customMessages);

        $page                  = SeoSetting::find($id);
        $page->seo_title       = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function cache_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        return view('globalsetting::cache_clear');
    }

    public function cache_clear_confirm()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        Artisan::call('optimize:clear');

        $notification = __('Cache cleared successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function database_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::database_clear');
    }

    /**
     * @param Request $request
     */
    public function database_clear_success(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate(['password' => 'required'], ['password.required' => __('Password is required')]);

        if (Hash::check($request->password, auth('admin')->user()->password)) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Step 1: Detach & delete product relations
                $allProducts = Product::all();

                foreach ($allProducts as $product) {
                    $product->labels()?->detach();
                    $product->tags()?->detach();
                    $product->categories()?->detach();
                    $product->taxes()?->detach();

                    $product->gallery()?->delete();
                    $product->translations()?->delete();
                    $product->variantImage()?->delete();
                    $product->relatedProducts()?->delete();
                    $product->wishlist()?->delete();
                    $product->variants()?->delete();
                    $product->reviews()?->delete();
                    $product->manageStocks()?->delete();

                    $product->update([
                        'brand_id'         => 0,
                        'unit_type_id'     => 0,
                        'return_policy_id' => 0,
                        'vendor_id'        => 0,
                    ]);
                }

                // Step 2: Truncate all models
                KycType::truncate();
                WithdrawMethod::truncate();
                ProductLabelTranslation::truncate();
                ProductLabel::truncate();
                Brand::truncate();
                BrandTranslation::truncate();
                Category::truncate();
                CategoryTranslation::truncate();
                UnitType::truncate();
                ProductTos::truncate();
                Tag::truncate();
                TagTranslation::truncate();
                Attribute::truncate();
                AttributeTranslation::truncate();
                AttributeValue::truncate();
                AttributeValueTranslation::truncate();
                Product::truncate();
                ProductTranslation::truncate();
                Gallery::truncate();
                Testimonial::truncate();
                TestimonialTranslation::truncate();
                User::truncate();
                Vendor::truncate();
                Country::truncate();
                State::truncate();
                City::truncate();
                Blog::truncate();
                BlogTranslation::truncate();
                BlogCategory::truncate();
                BlogCategoryTranslation::truncate();
                BlogComment::truncate();
                Faq::truncate();
                FaqTranslation::truncate();
                Coupon::truncate();
                CouponHistory::truncate();
                Tax::truncate();
                TaxTranslation::truncate();
                ShippingRule::truncate();
                ShippingRuleItem::truncate();
                Wishlist::truncate();
                Order::truncate();
                OrderDetails::truncate();
                OrderShippingAddress::truncate();
                OrderBillingAddress::truncate();
                TransactionHistory::truncate();
                OrderStatusChangeHistory::truncate();
                OrderPaymentDetails::truncate();
                Stock::truncate();
                KycInformation::truncate();
                Variant::truncate();
                VariantOption::truncate();
                WalletHistory::truncate();
                WithdrawRequest::truncate();
                Tax::truncate();
                CouponTranslation::truncate();
                Cart::truncate();
                BlogTranslation::truncate();
                Address::truncate();
                AdminNotification::truncate();

                // update Section model status value to 0
                Section::where('status', 1)->update(['status' => 0]);

                // delete pages
                if ($pages = CustomizeablePage::whereNotIn('slug', ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'])->get()) {
                    $pages->each(function ($page) {
                        $page->translations()->each(function ($translation) {
                            $translation->customizeablePage()->dissociate();
                            $translation->delete();
                        });
                        $page->delete();
                    });
                }

                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');


                return redirect()->back()->with([
                    'message'    => __('Database Cleared Successfully'),
                    'alert-type' => 'success',
                ]);
            } catch (\Exception $e) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                logError("Unable to clear database", $e);

                return redirect()->back()->with([
                    'message'    => __('Database Clear Failed'),
                    'alert-type' => 'error',
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message'    => __('Passwords do not match.'),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param $type
     */
    public function customCode($type)
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $customCode = CustomCode::first();
        if (!$customCode) {
            $customCode                    = new CustomCode;
            $customCode->css               = '//write your css code here without the style tag';
            $customCode->header_javascript = '//write your javascript here without the script tag';
            $customCode->body_javascript   = '//write your javascript here without the script tag';
            $customCode->footer_javascript = '//write your javascript here without the script tag';
            $customCode->save();
        }

        return view('globalsetting::custom_code_' . $type, compact('customCode'));
    }

    /**
     * @param Request $request
     */
    public function customCodeUpdate(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $validatedData = $request->validate([
            'css'               => 'sometimes',
            'header_javascript' => 'sometimes',
            'body_javascript'   => 'sometimes',
            'footer_javascript' => 'sometimes',
        ]);

        $customCode = CustomCode::firstOrNew();
        $customCode->fill($validatedData);
        $customCode->save();

        Cache::forget('customCode');

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_maintenance_mode_status()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $status = $this->cachedSetting?->maintenance_mode == 1 ? 0 : 1;

        Setting::where('key', 'maintenance_mode')->update(['value' => $status]);

        Cache::forget('setting');

        return response()->json([
            'success' => true,
            'message' => __('Updated Successfully'),
        ]);
    }

    /**
     * @param Request $request
     */
    public function update_maintenance_mode(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'maintenance_image'       => 'nullable|image|file|max:1024',
            'maintenance_title'       => 'required',
            'maintenance_description' => 'required',
        ], [
            'maintenance_image'       => __('Maintenance Mode Image must be an image file.'),
            'maintenance_title'       => __('Maintenance Mode Title is required'),
            'maintenance_description' => __('Maintenance Mode Description is required'),
        ]);

        if ($request->file('maintenance_image')) {
            $file_name = file_upload($request->maintenance_image, 'uploads/custom-images/', $this->cachedSetting?->maintenance_image);
            Setting::where('key', 'maintenance_image')->update(['value' => $file_name]);
        }

        Setting::where('key', 'maintenance_title')->update(['value' => $request->maintenance_title]);
        Setting::where('key', 'maintenance_description')->update(['value' => $request->maintenance_description]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function systemUpdate()
    {
        $zipLoaded = extension_loaded('zip');

        $updateFileDetails = false;
        $files             = false;
        $uploadFileSize    = false;

        $zipFilePath = public_path('upload/update.zip');
        if ($updateFileDetails = File::exists($zipFilePath)) {
            $uploadFileSize = File::size($zipFilePath);

            $files = $this->getFilesFromZip($zipFilePath);
        }

        return view('globalsetting::auto-update', compact('updateFileDetails', 'uploadFileSize', 'files', 'zipLoaded'));
    }

    /**
     * @param Request $request
     */
    public function systemUpdateStore(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);
        $zipFile = $request->file('zip_file');

        // Get the file size in bytes
        $fileSize = $zipFile->getSize();
        // Fetch server max upload size and post max size
        $max_upload_size = $this->convertPHPSizeToBytes(ini_get('upload_max_filesize'));
        $max_post_size   = $this->convertPHPSizeToBytes(ini_get('post_max_size'));
        // Get the smaller of the two values
        $max_size = min($max_upload_size, $max_post_size);

        if ($fileSize > $max_size) {
            $notification = __("The uploaded file exceeds the server's maximum upload size of ") . $this->formatBytes($max_size);
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $zipFilePath = public_path('upload/update.zip');

        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
        }

        // Store the uploaded file
        $zipFilePath = $zipFile->move(public_path('upload'), 'update.zip');

        if (!$this->isFirstDirUpload($zipFilePath)) {
            File::delete($zipFilePath);
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        return back();
    }

    /**
     * @return mixed
     */
    private function convertPHPSizeToBytes($size)
    {
        $suffix = strtoupper(substr($size, -1));
        $value  = (int) substr($size, 0, -1);
        switch ($suffix) {
            case 'G':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'M':
                $value *= 1024 * 1024;
                break;
            case 'K':
                $value *= 1024;
                break;
        }

        return $value;
    }

    /**
     * @param $size
     * @param $precision
     */
    private function formatBytes($size, $precision = 2)
    {
        $units         = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index         = floor(log($size, 1024));
        $formattedSize = $size / pow(1024, $index);

        return round($formattedSize, $precision) . ' ' . $units[$index];
    }

    public function systemUpdateRedirect()
    {
        $zipFilePath = public_path('upload/update.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) !== true) {
            File::delete($zipFilePath);
            $notification = __('Corrupted Zip File');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();

            return redirect()->back()->with($notification);
        }

        if (!$this->isFirstDirUpload($zipFilePath)) {
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();

            return redirect()->back()->with($notification);
        }

        $zip->close();

        $this->deleteFolderAndFiles(base_path('update'));

        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo(base_path());
            $zip->close();
        }

        return redirect(url('/update'));
    }

    public function systemUpdateDelete()
    {
        $zipFilePath = public_path('upload/update.zip');
        File::delete($zipFilePath);

        $this->deleteFolderAndFiles(base_path('update'));

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    /**
     * @return mixed
     */
    private function getFilesFromZip($zipFilePath)
    {
        $files = [];
        $zip   = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $files[]  = $fileInfo['name'];
            }
        }
        $zip->close();

        return $files;
    }

    /**
     * @return null
     */
    private function deleteFolderAndFiles($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $this->deleteFolderAndFiles($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }

    /**
     * @return mixed
     */
    private function isFirstDirUpload($zipFilePath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            $firstDir = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo      = $zip->statIndex($i);
                $filePathParts = explode('/', $fileInfo['name']);

                if (count($filePathParts) > 1) {
                    $firstDir = $filePathParts[0];
                    break;
                }
            }

            $zip->close();

            return $firstDir === 'update';
        }

        $zip->close();

        return false;
    }
}
