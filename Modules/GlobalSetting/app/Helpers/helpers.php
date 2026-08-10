<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\BasicPayment\app\Models\BasicPayment;
use Modules\BasicPayment\app\Models\PaymentGateway;
use Modules\GlobalSetting\app\Enums\WebsiteSettingEnum;
use Modules\GlobalSetting\app\Models\AdminNotification;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\Order\app\Models\Order;
use Modules\Product\app\Models\Product;
use Nwidart\Modules\Facades\Module;

if (!function_exists('doTransection')) {
    /**
     * Perform a model operation within a database transaction.
     *
     * @param  callable $operation A closure containing the model operation.
     * @return array    An array with status and message or data.
     */
    function doTransection(callable $operation): array
    {
        try {
            $result = DB::transaction(function () use ($operation) {
                return $operation();
            });

            return $operation;
        } catch (Exception $e) {
            logError("Transection Error", $e);
        }
    }
}

if (!function_exists('carbonNowWithTimeZone')) {
    function carbonNowWithTimeZone()
    {
        return WebsiteSettingEnum::now();
    }
}

if (!function_exists('timezone')) {
    function timezone()
    {
        return Cache::has('setting') ? Cache::get('setting')->timezone ?? config('app.timezone') : config('app.timezone');
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat()
    {
        return Cache::has('setting') ? Cache::get('setting')->time_format ?? 'h:i A' : 'h:i A';
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat()
    {
        return Cache::has('setting') ? Cache::get('setting')->date_format ?? 'Y-m-d' : 'Y-m-d';
    }
}

if (!function_exists('formattedDate')) {
    /**
     * @return mixed
     */
    function formattedDate($date)
    {
        return $date instanceof Carbon ? $date->setTimezone(timezone())->format(dateFormat()) : Carbon::parse($date)->setTimezone(timezone())->format(dateFormat());
    }
}

if (!function_exists('formattedTime')) {
    /**
     * @return mixed
     */
    function formattedTime($time)
    {
        return $time instanceof Carbon ? $time->setTimezone(timezone())->format(timeFormat()) : Carbon::parse($time)->setTimezone(timezone())->format(timeFormat());
    }
}

if (!function_exists('formattedDateTime')) {
    /**
     * @param $datetime
     */
    function formattedDateTime($datetime)
    {
        return formattedDate($datetime) . ' - ' . formattedTime($datetime);
    }
}

if (!function_exists('logError')) {
    /**
     * Log an error message with details about the exception.
     */
    function logError(string $context, \Exception $exception): void
    {
        logger()->error("{$context}: " . $exception->getMessage(), [
            'file'  => $exception->getFile(),
            'line'  => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}

if (!function_exists('htmlDecode')) {
    /**
     * @param $text
     */
    function htmlDecode($text)
    {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
}

if (!function_exists('notifyAdmin')) {
    /**
     * @param string     $type
     * @param nullstring $link
     */
    function notifyAdmin(string $title, string $message, ?string $type = null, ?string $link = null): void
    {
        try {
            $data = [
                'title'   => $title,
                'message' => $message,
            ];
            if (!is_null($type) && in_array($type, ['info', 'success', 'danger', 'warning', 'order'])) {
                $data['type'] = $type;
            }
            if (!is_null($link)) {
                $data['link'] = $link;
            }

            AdminNotification::create($data);

            Cache::remember('admin-notifications', 6000, function () {
                return AdminNotification::where('is_read', 0)->latest()->get();
            });
        } catch (Exception $e) {
            logError("Admin Notification Create Error", $e);
        }
    }
}

if (!function_exists('checkCredentials')) {
    /**
     * @return mixed
     */
    function checkCredentials()
    {
        $allMissingCredentials = [];
        if (Cache::has('setting') && $settings = Cache::get('setting')) {
            if ($settings->mail_host == 'mail_host' || $settings->mail_username == 'mail_username' || $settings->mail_password == 'mail_password' || $settings->mail_host == '' || $settings->mail_port == '' || $settings->mail_username == '' || $settings->mail_password == '') {
                $allMissingCredentials['Mail credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Mail credentials not found'),
                    'description' => __('This may create a problem while sending email. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.email-configuration',
                ];
            }

            if ($settings->recaptcha_status !== 'inactive' && ($settings->recaptcha_site_key == 'recaptcha_site_key' || $settings->recaptcha_secret_key == 'recaptcha_secret_key' || $settings->recaptcha_site_key == '' || $settings->recaptcha_secret_key == '')) {
                $allMissingCredentials['Google Recaptcha'] = (object) [
                    'status'      => true,
                    'message'     => __('Google Recaptcha credentials not found'),
                    'description' => __('This may create a problem while submitting any form submission from website. Please fill up the credential from google account.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->google_analytic_status !== 'inactive' && ($settings->google_analytic_id == 'google_analytic_id' || $settings->google_analytic_id == '')) {
                $allMissingCredentials['Google Analytic'] = (object) [
                    'status'      => true,
                    'message'     => __('Google Analytic credentials not found'),
                    'description' => __('This may create a problem to analyze your website. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->pixel_status !== 'inactive' && ($settings->pixel_app_id == 'pixel_app_id' || $settings->pixel_app_id == '')) {
                $allMissingCredentials['Facebook Pixel'] = (object) [
                    'status'      => true,
                    'message'     => __('Facebook Pixel credentials not found'),
                    'description' => __('This may create a problem to analyze your website. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->google_login_status !== 'inactive' && ($settings->google_client_id == 'google_client_id' || $settings->google_secret_id == 'google_secret_id' || $settings->google_client_id == '' || $settings->google_secret_id == '')) {
                $allMissingCredentials['Google Login'] = (object) [
                    'status'      => true,
                    'message'     => __('Google login credentials not found'),
                    'description' => __('This may create a problem while logging in using google. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.update-social-login',
                ];
            }
        }

        if (!Cache::has('basic_payment') && Module::isEnabled('BasicPayment')) {
            Cache::rememberForever('basic_payment', function () {
                $payment_info  = BasicPayment::get();
                $basic_payment = [];
                foreach ($payment_info as $payment_item) {
                    $basic_payment[$payment_item->key] = $payment_item->value;
                }

                return (object) $basic_payment;
            });
        }

        if (Cache::has('basic_payment') && $basicPayment = Cache::get('basic_payment')) {
            if ($basicPayment->stripe_status !== 'inactive' && ($basicPayment->stripe_key == 'stripe_key' || $basicPayment->stripe_secret == 'stripe_secret' || $basicPayment->stripe_key == '' || $basicPayment->stripe_secret == '')) {
                $allMissingCredentials['Stripe credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Stripe credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->paypal_status !== 'inactive' && ($basicPayment->paypal_client_id == 'paypal_client_id' || $basicPayment->paypal_secret_key == 'paypal_secret_key' || $basicPayment->paypal_client_id == '' || $basicPayment->paypal_secret_key == '')) {
                $allMissingCredentials['Paypal credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Paypal credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }
        }

        if (!Cache::has('payment_setting') && Module::isEnabled('BasicPayment')) {
            Cache::rememberForever('payment_setting', function () {
                $payment_info    = PaymentGateway::get();
                $payment_setting = [];
                foreach ($payment_info as $payment_item) {
                    $payment_setting[$payment_item->key] = $payment_item->value;
                }

                return (object) $payment_setting;
            });
        }

        if (Cache::has('payment_setting') && $paymentAddons = Cache::get('payment_setting')) {
            if ($paymentAddons->razorpay_status !== 'inactive' && ($paymentAddons->razorpay_key == 'razorpay_key' || $paymentAddons->razorpay_secret == 'razorpay_secret' || $paymentAddons->razorpay_key == '' || $paymentAddons->razorpay_secret == '')) {
                $allMissingCredentials['Razorpay credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Razorpay credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($paymentAddons->flutterwave_status !== 'inactive' && ($paymentAddons->flutterwave_public_key == 'flutterwave_public_key' || $paymentAddons->flutterwave_secret_key == 'flutterwave_secret_key' || $paymentAddons->flutterwave_public_key == '' || $paymentAddons->flutterwave_secret_key == '')) {
                $allMissingCredentials['Flutterwave credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Flutterwave credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($paymentAddons->paystack_status !== 'inactive' && ($paymentAddons->paystack_public_key == 'paystack_public_key' || $paymentAddons->paystack_secret_key == 'paystack_secret_key' || $paymentAddons->paystack_public_key == '' || $paymentAddons->paystack_secret_key == '')) {
                $allMissingCredentials['Paystack credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Paystack credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($paymentAddons->mollie_status !== 'inactive' && ($paymentAddons->mollie_key == 'mollie_key' || $paymentAddons->mollie_key == '')) {
                $allMissingCredentials['Mollie credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Mollie credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($paymentAddons->instamojo_status !== 'inactive' && ($paymentAddons->instamojo_client_id == 'instamojo_client_id' || $paymentAddons->instamojo_client_secret == 'instamojo_client_secret' || $paymentAddons->instamojo_client_id == '' || $paymentAddons->instamojo_client_secret == '')) {
                $allMissingCredentials['Instamojo credentials'] = (object) [
                    'status'      => true,
                    'message'     => __('Instamojo credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }
        }

        return $allMissingCredentials;
    }
}

if (!function_exists('getCurrentSerial')) {
    /**
     * @return int
     */
    function getCurrentSerial($paginator)
    {
        if ($paginator instanceof LengthAwarePaginator) {
            return (int) 1 + intval($paginator->currentPage() - 1) * intval($paginator->perPage());
        }

        return 1;
    }
}

if (!function_exists('generateSku')) {
    /**
     * @param Model $model
     */
    function generateSku(): ?string
    {
        $skuPrefix     = getSettings('sku_prefix');
        $skuLength     = getSettings('sku_length');
        $modelLatestId = Product::query()->latest('id')->first();

        return $skuPrefix . str_pad(($modelLatestId ? $modelLatestId->id + 1 : 1), $skuLength, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generateInvoiceNumber')) {
    /**
     * @param Model $model
     */
    function generateInvoiceNumber(): ?string
    {
        $skuPrefix     = getSettings('invoice_prefix');
        $skuLength     = getSettings('invoice_length');
        $modelLatestId = Order::query()->latest('id')->first();

        return $skuPrefix . str_pad(($modelLatestId ? $modelLatestId->id + 1 : 1), $skuLength, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generateUniqueBarcode')) {
    /**
     * @return mixed
     */
    function generateUniqueBarcode($length = 12): ?string
    {
        try {
            do {
                $barcode = strtoupper(Str::random($length));
            } while (Product::where('barcode', $barcode)->exists());

            return $barcode;
        } catch (Exception $e) {
            logError("Generate Unique Barcode Error", $e);

            return null;
        }
    }
}

if (!function_exists('seoSetting')) {
    /**
     * @param  null|string $route
     * @param  null|string $slug
     * @return mixed
     */
    function seoSetting(string | null $route = null, string | null $slug = null)
    {
        try {
            if (!Cache::has('SeoSetting')) {
                $seoSettings = Cache::rememberForever('SeoSetting', function () {
                    return SeoSetting::all();
                });
            } else {
                $seoSettings = Cache::get('SeoSetting');
            }
        } catch (\Exception $e) {
            logError("Seo Setting Error", $e);
            $seoSettings = SeoSetting::all();
        }

        if (is_null($route) && is_null($slug)) {
            return $seoSettings;
        } elseif ($route) {
            return $seoSettings->where('route', $route)->first();
        } elseif ($slug) {
            return $seoSettings->where('slug', $slug)->first();
        } else {
            return $seoSettings;
        }
    }
}
