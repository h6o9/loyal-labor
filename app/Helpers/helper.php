<?php

use App\Exceptions\AccessPermissionDeniedException;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\BasicPayment\app\Enums\BasicPaymentSupportedCurrencyListEnum;
use Modules\BasicPayment\app\Services\PaymentMethodService;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\CustomMenu\app\Enums\DefaultMenusEnum;
use Modules\Frontend\app\Enums\ManageThemeEnum;
use Modules\Frontend\app\Models\Home;
use Modules\Frontend\app\Models\Section;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Language\app\Models\Language;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderStatusChangeHistory;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductReview;
use Modules\Wallet\app\Http\Controllers\WalletController;
use Modules\Wallet\app\Models\WalletHistory;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

/**
 * @param  stringnull $oldFile
 * @return mixed
 */
function file_upload(UploadedFile $file, string $path = 'uploads/custom-images/', string|null $oldFile = null, bool $optimize = false)
{
    // Block dangerous extensions
    $blockedExtensions = [
        'php',
        'php3',
        'php4',
        'php5',
        'php7',
        'php8',
        'phtml',
        'phar',
        'sh',
        'bash',
        'zsh',
        'ksh',
        'csh',
        'ps1',
        'py',
        'pl',
        'rb',
        'cgi',
        'exe',
        'msi',
        'dll',
        'so',
        'bin',
        'cmd',
        'bat',
        'com',
        'apk',
        'jar',
        'htaccess',
        'htpasswd',
        'conf',
        'ini',
        'js',
        'ts',
        'html',
        'htm',
        'jsp',
        'asp',
        'aspx',
        'cshtml',
        'rar',
        '7z',
        'tar',
        'gz',
        'bz2'
    ];

    // Block dangerous MIME types
    $blockedMimes = [
        'application/x-php',
        'text/x-php',
        'text/html',
        'application/javascript',
        'text/javascript',
        'application/x-msdownload',
        'application/x-executable',
        'application/x-sh',
        'application/x-py',
        'application/java-archive'
    ];

    $ext = strtolower($file->getClientOriginalExtension());
    $mime = $file->getMimeType();

    if (in_array($ext, $blockedExtensions) || in_array($mime, $blockedMimes)) {
        Log::warning("Blocked upload. Ext: {$ext}, MIME: {$mime}");
        session()->flash('message', __('This file type is not allowed.'));
        session()->flash('alert-type', 'error');
        return null;
    }

    // Prepare filename and move file
    $extention = $file->getClientOriginalExtension();
    $file_name = 'wsus-img' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
    $file_name = $path . $file_name;
    $file->move(public_path($path), $file_name);

    try {
        if ($oldFile && !str($oldFile)->contains('uploads/website-images') && File::exists(public_path($oldFile))) {
            unlink(public_path($oldFile));
        }

        if ($optimize) {
            ImageOptimizer::optimize(public_path($file_name));
        }
    } catch (Exception $e) {
        Log::info($e->getMessage());
    }

    return $file_name;
}

if (!function_exists('needLogin')) {
    function needLogin(): bool
    {
        $setting = cache('setting');
        if ($setting && $setting->has_app) {
            return true;
        }

        if ($setting && !$setting->can_guest_checkout) {
            return true;
        }

        return false;
    }
}

// file upload method
if (!function_exists('allLanguages')) {
    /**
     * @return mixed
     */
    function allLanguages()
    {
        $allLanguages = Cache::rememberForever('allLanguages', function () {
            return Language::select('code', 'name', 'direction', 'status', 'is_default')->get();
        });

        if (!$allLanguages) {
            $allLanguages = Language::select('code', 'name', 'direction', 'status', 'is_default')->get();
        }

        return $allLanguages;
    }
}

if (!function_exists('getSessionLanguage')) {
    /**
     * @return mixed
     */
    function getSessionLanguage(): string
    {
        if (!session()->has('lang')) {
            session()->put('lang', config('app.locale'));
            session()->forget('text_direction');
            session()->put('text_direction', 'ltr');
        }

        $lang = Session::get('lang');

        return $lang;
    }
}
if (!function_exists('checkPaginate')) {
    /**
     * @return mixed
     */
    function checkPaginate($list)
    {
        return $list instanceof \Illuminate\Pagination\LengthAwarePaginator;
    }
}
if (!function_exists('setLanguage')) {
    /**
     * @param $code
     */
    function setLanguage($code)
    {
        $lang = Language::whereCode($code)->first();

        if (session()->has('lang')) {
            sessionForgetLangChang();
        }
        if ($lang) {
            session()->put('lang', $lang->code);
            session()->put('text_direction', $lang->direction);

            return true;
        }
        session()->put('lang', config('app.locale'));

        return false;
    }
}
if (!function_exists('sessionForgetLangChang')) {
    function sessionForgetLangChang()
    {
        session()->forget('lang');
        session()->forget('text_direction');
    }
}

if (!function_exists('allCurrencies')) {
    /**
     * @return mixed
     */
    function allCurrencies()
    {
        $allCurrencies = Cache::rememberForever('allCurrencies', function () {
            return MultiCurrency::all();
        });

        if (!$allCurrencies) {
            $allCurrencies = MultiCurrency::all();
        }

        return $allCurrencies;
    }
}

if (!function_exists('getSessionCurrency')) {
    function getSessionCurrency(): string
    {
        if (!session()->has('currency_code') || !session()->has('currency_rate') || !session()->has('currency_position')) {
            $currency = allCurrencies()->where('is_default', 'yes')->first();
            session()->put('currency_code', $currency->currency_code);
            session()->forget('currency_position');
            session()->put('currency_position', $currency->currency_position);
            session()->forget('currency_icon');
            session()->put('currency_icon', $currency->currency_icon);
            session()->forget('currency_rate');
            session()->put('currency_rate', $currency->currency_rate);
        }

        return Session::get('currency_code');
    }
}

function admin_lang()
{
    return Session::get('admin_lang');
}

// calculate currency
/**
 * @return mixed
 */
if (!function_exists('currency')) {
    /**
     * @param  null    $icon
     * @return mixed
     */
    function currency($price, $currency = null, $icon = true)
    {
        if ($currency) {
            $multiCurrency = MultiCurrency::where('currency_code', $currency)->first();

            if ($multiCurrency) {
                $currency_icon     = $multiCurrency->currency_icon;
                $currency_rate     = $multiCurrency->currency_rate;
                $currency_position = $multiCurrency->currency_position;
            }
        } else {
            $currency          = getSessionCurrency();
            $currency_icon     = session()->get('currency_icon');
            $currency_rate     = session()->get('currency_rate', 1);
            $currency_position = session()->get('currency_position');
        }

        return $icon ? formatCurrency(formatPrice($price, $currency_rate), $currency_icon, $currency_position) : formatPrice($price, $currency_rate);
    }

    /**
     * @param  $price
     * @param  $currency
     * @param  null        $icon
     * @return mixed
     */
    function defaultCurrency($price, $currency = null, $icon = true)
    {
        if (!$currency) {
            $multiCurrency = MultiCurrency::where('is_default', 'yes')->first();

            if ($multiCurrency) {
                $currency_icon     = $multiCurrency->currency_icon;
                $currency_rate     = $multiCurrency->currency_rate;
                $currency_position = $multiCurrency->currency_position;
            }
        } else {
            $multiCurrency = MultiCurrency::where('currency_code', $currency)->first();

            if ($multiCurrency) {
                $currency_icon     = $multiCurrency->currency_icon;
                $currency_rate     = $multiCurrency->currency_rate;
                $currency_position = $multiCurrency->currency_position;
            }
        }

        return $icon ? formatCurrency(formatPrice($price, $currency_rate), $currency_icon, $currency_position) : formatPrice($price, $currency_rate);
    }

    /**
     * @param $price
     * @param $rate
     */
    function formatPrice($price, $rate): string
    {
        $price = floatval($price) * floatval($rate);

        return number_format($price, 2, '.', ',');
    }

    /**
     * @return mixed
     */
    function formatCurrency($price, $icon, $position): string
    {
        switch ($position) {
            case 'before_price':
                $formatted_price = $icon . $price;
                break;
            case 'before_price_with_space':
                $formatted_price = $icon . ' ' . $price;
                break;
            case 'after_price':
                $formatted_price = $price . $icon;
                break;
            case 'after_price_with_space':
                $formatted_price = $price . ' ' . $icon;
                break;
            default:
                $formatted_price = $icon . $price;
                break;
        }

        return $formatted_price;
    }

    /**
     * @param string $formattedPrice
     */
    function revertFormattedPrice(string $formattedPrice): int
    {
        $number = str_replace(',', '', $formattedPrice);
        $number = round(floatval($number));

        return intval($number);
    }

    /**
     * @return mixed
     */
    function revertToUSD($price, $format = true)
    {
        if (str(getSessionCurrency())->lower() !== 'usd') {
            $multiCurrency = MultiCurrency::where('currency_code', getSessionCurrency())->first();

            if ($multiCurrency) {
                $currency_rate = $multiCurrency->currency_rate;
                $price         = floatval($price) / floatval($currency_rate);

                if ($format) {
                    return number_format($price, 2, '.', ',');
                } else {
                    return revertFormattedPrice($price);
                }
            }
        }

        return $price;
    }
}

/**
 * @return mixed
 */
function currency_icon()
{
    $currencySetting = Cache::rememberForever('currency', function () {
        $siteCurrencyId = Session::get('site_currency');

        $currency = MultiCurrency::when($siteCurrencyId, function ($query) use ($siteCurrencyId) {
            return $query->where('id', $siteCurrencyId);
        })->when(!$siteCurrencyId, function ($query) {
            return $query->where('is_default', 'yes');
        })->first();

        return $currency;
    });

    return $currencySetting->currency_icon;
}

// remove currency icon using regular expression
/**
 * @return mixed
 */
function remove_icon($price)
{
    $price = preg_replace('/[^0-9,.]/', '', $price);

    return $price;
}

if (!function_exists('delete_file')) {
    /**
     * @param $path
     */
    function delete_file($path)
    {
        if (File::exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
if (!function_exists('remove_comma')) {
    // remove , from number
    /**
     * @param $number
     */
    function remove_comma($number)
    {
        return str_replace(',', '', $number);
    }
}

// calculate currency

// custom decode and encode input value
/**
 * @return mixed
 */
function html_decode($text)
{
    $after_decode = htmlspecialchars_decode($text, ENT_QUOTES);

    return $after_decode;
}

if (!function_exists('currectUrlWithQuery')) {
    /**
     * @return mixed
     */
    function currectUrlWithQuery($code)
    {
        $currentUrlWithQuery = request()->fullUrl();

        // Parse the query string
        $parsedQuery = parse_url($currentUrlWithQuery, PHP_URL_QUERY);

        // Check if the 'code' parameter already exists
        $codeExists = false;
        if ($parsedQuery) {
            parse_str($parsedQuery, $queryArray);
            $codeExists = isset($queryArray['code']);
        }

        if ($codeExists) {
            $updatedUrlWithQuery = preg_replace('/(\?|&)code=[^&]*/', '$1code=' . $code, $currentUrlWithQuery);
        } else {
            $updatedUrlWithQuery = $currentUrlWithQuery . ($parsedQuery ? '&' : '?') . http_build_query(['code' => $code]);
        }

        return $updatedUrlWithQuery;
    }
}

if (!function_exists('checkAdminHasPermission')) {
    /**
     * @param $permission
     */
    function checkAdminHasPermission($permission): bool
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->can($permission) ? true : false;
    }
}

if (!function_exists('checkAdminHasPermissionAndThrowException')) {
    /**
     * @param $permission
     */
    function checkAdminHasPermissionAndThrowException($permission)
    {
        if (!checkAdminHasPermission($permission)) {
            throw new AccessPermissionDeniedException;
        }
    }
}

if (!function_exists('checkStaffHasPermission')) {
    /**
     * @param $permission
     */
    function checkStaffHasPermission($permission): bool
    {
        $staff = Auth::guard('staff')->user();

        if (! $staff) {
            return false;
        }

        if (property_exists($staff, 'is_super_admin') && $staff->is_super_admin) {
            return true;
        }

        return method_exists($staff, 'can') && $staff->can($permission);
    }
}

if (!function_exists('checkStaffHasPermissionAndThrowException')) {
    /**
     * @param $permission
     */
    function checkStaffHasPermissionAndThrowException($permission)
    {
        if (!checkStaffHasPermission($permission)) {
            throw new AccessPermissionDeniedException;
        }
    }
}

if (!function_exists('getSettingStatus')) {
    /**
     * @return mixed
     */
    function getSettingStatus($key, $type = 'string')
    {
        if (Cache::has('setting')) {
            $setting = Cache::get('setting');
            if (!is_null($key)) {
                if ($type == 'string') {
                    return config('services.default_status.active_text') == $setting->$key ? (bool) true : (bool) false;
                } else {
                    return config('services.default_status.active_int') == $setting->$key ? (bool) true : (bool) false;
                }
            }
        } else {
            try {
                if ($type == 'string') {
                    return Setting::where('key', $key)->first()?->value == config('services.default_status.active_text') ? (bool) true : (bool) false;
                } else {
                    return Setting::where('key', $key)->first()?->value == config('services.default_status.active_int') ? (bool) true : (bool) false;
                }
            } catch (Exception $e) {
                info($e->getMessage());

                return false;
            }
        }

        return false;
    }
}

if (!function_exists('getSettings')) {
    /**
     * @return mixed
     */
    function getSettings($key = null)
    {
        $setting = Cache::rememberForever('setting', function () {
            return (object) Setting::select('key', 'value')->get()
                ->pluck('value', 'key')
                ->toArray();
        });

        return $key !== null && property_exists($setting, $key)
            ? $setting->$key
            : $setting;
    }
}

if (!function_exists('loyalBrandName')) {
    function loyalBrandName(): string
    {
        $name = getSettings('app_name') ?? 'Loylabor';
        $stale = ['topcommerce', 'homeservice', 'home service', 'loyal labor', 'top commerce'];

        return in_array(strtolower(trim((string) $name)), $stale, true) ? 'Loylabor' : (string) $name;
    }
}

if (!function_exists('loyalEmailLogoUrl')) {
    function loyalEmailLogoUrl(): string
    {
        return asset('public/backend/img/admin-auth-bg.png');
    }
}

if (!function_exists('loyalSanitizeBrandText')) {
    function loyalSanitizeBrandText(string $text): string
    {
        $brand = loyalBrandName();
        $legacy = ['TopCommerce', 'Topcommerce', 'HomeService', 'Home Service', 'Loyal Labor'];

        return str_ireplace($legacy, $brand, $text);
    }
}

if (!function_exists('isDatatablesRequest')) {
    function isDatatablesRequest($request): bool
    {
        return $request->ajax() || $request->has('draw');
    }
}

if (!function_exists('asset_ver')) {
    /**
     * Build an asset URL with a cache-busting query string based on the
     * file's actual last-modified time, so browsers always fetch the
     * latest CSS/JS after a deploy instead of serving a stale cached copy
     * (the old `?v={{ $setting->version }}` approach never changes unless
     * someone manually bumps the app version string).
     *
     * @param string $path relative to the public/ directory, e.g. 'backend/css/style.css'
     * @return string
     */
    function asset_ver(string $path): string
    {
        $fullPath = public_path(ltrim($path, '/'));
        $version = File::exists($fullPath) ? filemtime($fullPath) : time();

        return asset($path) . '?v=' . $version;
    }
}

if (!function_exists('checkLangCode')) {
    /**
     * @return mixed
     */
    function checkLangCode($code)
    {
        $languages = allLanguages();

        return $code == $languages->first()->code ? '' : 'd-none';
    }
}

if (!function_exists('isRoute')) {
    /**
     * @param array $route
     */
    function isRoute(string | array $route, string | null $returnValue = null)
    {
        if (is_array($route)) {
            foreach ($route as $value) {
                if (Route::is($value)) {
                    return is_null($returnValue) ? true : $returnValue;
                }
            }

            return false;
        }

        if (Route::is($route)) {
            return is_null($returnValue) ? true : $returnValue;
        }

        return false;
    }
}
if (!function_exists('customCode')) {
    function customCode()
    {
        return Cache::rememberForever('customCode', function () {
            return CustomCode::select('css', 'header_javascript', 'body_javascript', 'footer_javascript')->first();
        });
    }
}
if (!function_exists('customPages')) {
    function customPages()
    {
        return CustomizeablePage::with('translation')->where('status', 1)->get();
    }
}

if (!function_exists('paidCurrencyReformat')) {
    /**
     * @return mixed
     */
    function paidCurrencyReformat($paid_amount, $payable_currency, $gateway_name)
    {
        $paymentService = app(PaymentMethodService::class);
        if ($paymentService->getValue($gateway_name) == str('Razorpay')->lower() || $paymentService->getValue($gateway_name) == str('Stripe')->lower()) {
            if ($paymentService->getValue($gateway_name) == str('Stripe')->lower()) {
                $allCurrencyCodes = BasicPaymentSupportedCurrencyListEnum::getStripeSupportedCurrencies();

                if (in_array(Str::upper($payable_currency), $allCurrencyCodes['non_zero_currency_codes'])) {
                    $paid_amount = $paid_amount;
                } elseif (in_array(Str::upper($payable_currency), $allCurrencyCodes['three_digit_currency_codes'])) {
                    $paid_amount = (int) rtrim(strval($paid_amount), '0');
                } else {
                    $paid_amount = floatval($paid_amount / 100);
                }
            } else {
                $paid_amount = floatval($paid_amount / 100);
            }
        }

        return $paid_amount;
    }
}

if (!function_exists('convertWithCurrencyRate')) {
    /**
     * @return mixed
     */
    function convertWithCurrencyRate($amount, $currency = 'usd')
    {
        $currency = str($currency)->upper();

        $targetCurrency = MultiCurrency::where('currency_code', $currency)->first();

        return $targetCurrency ? round(floatval($amount) * floatval($targetCurrency->currency_rate), 2) : round($amount, 2);
    }
}

/**
 * @return mixed
 */
function getPaymentMethodsDetails()
{
    $paymentMethods = [];

    $paymentServiceInstance = app(PaymentMethodService::class);

    foreach ($paymentServiceInstance->getSupportedPayments() as $paymentMethod) {
        $paymentMethods[$paymentMethod] = (object) [
            'name'                => $paymentServiceInstance->getPaymentName($paymentMethod),
            'logo'                => $paymentServiceInstance->getLogo($paymentMethod),
            'currencies'          => $paymentServiceInstance->getSupportedCurrencies($paymentMethod),
            'status'              => $paymentServiceInstance->isActive($paymentMethod),
            'isCurrencySupported' => $paymentServiceInstance->isCurrencySupported($paymentMethod),
        ];
    }

    return $paymentMethods;
}

if (!function_exists('allCategories')) {
    /**
     * @param null $parent
     */
    function allCategories(bool $getLatest = false, int | null $status = null, bool $featured = false, int | null $parent = null, bool $parentOnly = false, bool $withChild = false, int | null $limit = null)
    {
        return Category::query()
            ->when($withChild, function ($query) {
                $query->with([
                    'translation',
                    'children',
                    'children.translation',
                    'children.parent',
                    'children.parent.translation',
                ]);
            }, function ($query) {
                $query->with([
                    'translation',
                    'parent',
                ]);
            })
            ->withCount('products')
            ->when($parent, function ($query) use ($parent) {
                $query->where('parent_id', $parent);
            })
            ->when($parentOnly, function ($query) {
                $query->whereNull('parent_id');
            })
            ->when($featured, function ($query) {
                $query->where('is_featured', 1);
            })
            ->when(is_int($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($getLatest, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->when(is_int($limit), function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->get();
    }
}

if (!function_exists('themeList')) {
    function themeList()
    {
        return ManageThemeEnum::allThemeValueArray();
    }
}

if (!function_exists('getDefaultHome')) {
    /**
     * Get the default home theme.
     */
    function getDefaultHome(): int
    {
        $defaultTheme   = 1;
        $setting        = getSettings();
        $requestedTheme = request()->filled('theme') ? request('theme') : null;
        $cachedTheme    = session('selected_theme', $defaultTheme);

        if ($requestedTheme && in_array($requestedTheme, themeList())) {
            return $requestedTheme;
        }

        if ($setting) {
            $settingTheme = $setting->theme ?? $defaultTheme;

            if ($cachedTheme && in_array($cachedTheme, themeList())) {
                return $cachedTheme;
            }

            return $settingTheme;
        }

        return $defaultTheme;
    }
}

if (!function_exists('processText')) {
    /**
     * @return mixed
     */
    function processText($text)
    {
        // Replace text within curly brackets with a <span> tag
        $patternCurlyBrackets     = '/\{(.*?)\}/';
        $replacementCurlyBrackets = '<span>$1</span>';
        $text                     = preg_replace($patternCurlyBrackets, $replacementCurlyBrackets, $text);

        // Replace backslashes with <br> tags
        $patternBackslash     = '/\\\\/';
        $replacementBackslash = '<br>';
        $text                 = preg_replace($patternBackslash, $replacementBackslash, $text);

        // Return the modified text
        return $text;
    }
}

/**
 * @param string $theme
 * @param string $lang
 */
function removeSectionCache(string $theme, string $lang): void
{
    $cacheKey = $theme . '_home_section_data_' . $lang;
    Cache::forget($cacheKey);
}

if (!function_exists('setHomepageSections')) {
    /**
     * @return mixed
     */
    function setHomepageSections($isReturn = false)
    {
        $lang = getSessionLanguage();

        $theme = ManageThemeEnum::tryFrom(config('services.theme'));

        $sectionCacheKey = $theme->value . '_home_section_data_' . $lang;

        if (!cache()->has($sectionCacheKey)) {
            $getSection = getSection();

            cache()->rememberForever($sectionCacheKey, function () use ($getSection) {
                return $getSection;
            });
        }

        return $isReturn ? cache()->get($sectionCacheKey) : null;
    }
}

if (!function_exists('section_data')) {
    /**
     * Safely get section data property from nested object.
     *
     * @param  object|array|null $sections
     * @param  mixed             $default
     * @return mixed
     */
    function sectionData($sections = null, $sectionName = null, $propertyName = null, $default = null)
    {
        try {
            if (!$sections) {
                $sections = setHomepageSections(isReturn: true);
            }
        } catch (Exception $e) {
            logError('Unable to set home section cache', $e);
            $sections = null;
        }

        if (is_object($sections) && isset($sections->{$sectionName})) {
            $section = $sections->{$sectionName};

            if (is_object($section) && isset($section->{$propertyName})) {
                return processText(strip_tags($section->{$propertyName}));
            }
        }

        return $default;
    }
}

if (!function_exists('getSection')) {
    /**
     * @param  $sectionName
     * @return mixed
     */
    function getSection($sectionName = null, $hasHomepage = true)
    {
        $homepage = Home::whereSlug(config('services.theme'))->first();
        $sections = [];

        if ($homepage && $hasHomepage) {
            $sections = (object) $homepage?->sections()
                ->when($sectionName, function ($query) use ($sectionName) {
                    return $query->where('name', $sectionName);
                })
                ->get()->mapWithKeys(function ($section) {
                    $globalContent = is_object($section->global_content) || is_array($section->global_content)
                        ? (array) $section->global_content
                        : [];

                    $flattenedContent = collect($globalContent)->mapWithKeys(function ($item, $key) {
                        if (is_object($item) && property_exists($item, 'value')) {
                            return [$key => $item->value];
                        } elseif (is_array($item) && isset($item['value'])) {
                            return [$key => $item['value']];
                        }

                        return [$key => null];
                    });

                    $translationContent = optional($section->translation)->content;

                    $flattenedTranslation = is_object($translationContent) || is_array($translationContent)
                        ? (array) $translationContent
                        : [];

                    return [
                        $section->name ?? 'unnamed_section' => (object) array_merge(
                            [
                                'status' => $section->status ?? 0,
                            ],
                            $flattenedContent->toArray(),
                            $flattenedTranslation
                        ),
                    ];
                })->toArray();
        } elseif (!$hasHomepage) {
            $sections = (object) Section::when($sectionName, function ($query) use ($sectionName) {
                return $query->where('name', $sectionName);
            })
                ->whereNull('home_id')
                ->get()->mapWithKeys(function ($section) {
                    $globalContent = is_object($section->global_content) || is_array($section->global_content)
                        ? (array) $section->global_content
                        : [];

                    $flattenedContent = collect($globalContent)->mapWithKeys(function ($item, $key) {
                        if (is_object($item) && property_exists($item, 'value')) {
                            return [$key => $item->value];
                        } elseif (is_array($item) && isset($item['value'])) {
                            return [$key => $item['value']];
                        }

                        return [$key => null];
                    });

                    $translationContent = optional($section->translation)->content;

                    $flattenedTranslation = is_object($translationContent) || is_array($translationContent)
                        ? (array) $translationContent
                        : [];

                    return [
                        $section->name ?? 'unnamed_section' => (object) array_merge(
                            [
                                'status' => $section->status ?? 0,
                            ],
                            $flattenedContent->toArray(),
                            $flattenedTranslation
                        ),
                    ];
                })->toArray();
        }

        return $sections;
    }
}

if (!function_exists('bcround')) {
    /**
     * @param $number
     * @param $precision
     */
    function bcround($number, $precision = 2)
    {
        $factor = bcpow('10', (string) $precision);
        return bcdiv(bcadd(bcmul($number, $factor, 0), '0.5', 0), $factor, $precision);
    }
}

if (!function_exists('userId')) {
    /**
     * @param $number
     * @param $precision
     */
    function userId()
    {
        $user = Auth::user();

        if ($user) {
            return $user->id;
        }

        return null;
    }
}

if (!function_exists('vendorId')) {
    /**
     * @param $number
     * @param $precision
     */
    function vendorId()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::user();

            if ($user->seller) {
                return $user->seller->id;
            }
        }

        return null;
    }
}

if (!function_exists('vendorNotifications')) {
    /**
     * @param $number
     * @param $precision
     */
    function vendorNotifications($limit = 10)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::user();

            if ($user->seller) {
                $notifications = OrderStatusChangeHistory::whereHas('order', function ($query) use ($user) {
                    $query->where('vendor_id', $user->seller->id);
                })
                    ->with([
                        'order:id,order_id,vendor_id',
                    ])
                    ->latest()->get();

                $totalNotifications = $notifications->count();

                if ($limit !== null) {
                    $notifications = $notifications->take($limit);
                }

                return [
                    $notifications,
                    $totalNotifications,
                ];
            }
        }

        return [
            [],
            0,
        ];
    }
}

if (!function_exists('saveWalletHistory')) {
    /**
     * @param $userId
     * @param $amount
     * @param $trxId
     * @param $gateway
     * @param $order
     * @param $course
     * @param $status
     */
    function saveWalletHistory($orderDetailsItem, $order, $status = 'pending', $transactionType = 'credit')
    {
        $vendorId     = $orderDetailsItem->vendor_id ?? $order->vendor_id;
        $vendorUserId = $orderDetailsItem->load('vendor')->vendor->user_id ?? Vendor::where('id', $vendorId)->first()?->user_id;

        if ($wallet = WalletHistory::where([
            'user_id'          => $vendorUserId,
            'vendor_id'        => $vendorId,
            'order_id'         => $order->id,
            'order_details_id' => $orderDetailsItem->id,
            'transaction_type' => $transactionType,
            'payment_gateway'  => $order->paymentDetails->payment_method,
        ])->first()) {
            notifyAdmin('Duplicate Wallet Request', 'You tried to add amount to the wallet of ' . optional($wallet->user)->email . ' which was already added at ' . formattedDateTime($wallet->created_at), link: route('admin.show-wallet-history', $wallet->id));

            return false;
        }

        try {
            $wallet                   = new WalletHistory();
            $wallet->user_id          = $vendorUserId;
            $wallet->vendor_id        = $vendorId;
            $wallet->order_details_id = $orderDetailsItem->id;
            $wallet->order_id         = $order->id;
            $wallet->product_id       = $orderDetailsItem->product_id;
            $wallet->amount           = $orderDetailsItem->commission;
            $wallet->transaction_id   = blank($order->paymentDetails->transaction_id) ? 'not-found' : $order->paymentDetails->transaction_id;
            $wallet->payment_gateway  = $order->paymentDetails->payment_method;
            $wallet->payment_status   = PaymentStatus::tryFrom($status) ? PaymentStatus::tryFrom($status)->value : PaymentStatus::PENDING->value;
            $wallet->transaction_type = $transactionType;
            $wallet->save();

            notifyAdmin('New Balance Add Request', 'A new wallet amount add request added. Order ID: ' . $order->order_id, link: route('admin.show-wallet-history', $wallet->id));

            if (getSettingStatus('wallet_amount_auto_approve', 'int') && $order->paymentDetails->payment_status == PaymentStatus::COMPLETED) {
                $wallet->payment_status = PaymentStatus::COMPLETED->value;
                $wallet->save();

                WalletController::updateWalletBalance($wallet->id, true);
            }

            return true;
        } catch (Exception $e) {
            notifyAdmin('Wallet Balance Adding Failed', 'A wallet amount adding failed. Order ID: ' . $order->order_id . ' ERROR: ' . $e->getMessage());

            logError("Wallet Balance Adding Failed. Order ID: $order->order_id ERROR: ", $e);
        }

        return false;
    }
}

/**
 * @param string $bbcode
 */
function encodeShopTimeToHtml(string $bbcode): string
{
    // Normalize all newlines to "\n"
    $bbcode = str_replace(["\r\n", "\r"], "\n", $bbcode);

    // Split on newlines
    $lines = explode("\n", trim($bbcode));

    $html = '';

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed !== '') {
            $html .= '<li>' . $trimmed . '</li>' . "\n";
        }
    }

    return trim($html);
}

/**
 * @return int
 */
function pendingProducts()
{
    return Product::where('is_approved', 0)->count() ?? 0;
}

function pendingProductReviews()
{
    return ProductReview::where('status', 0)->count() ?? 0;
}

/**
 * @param $type
 */
function orderStatusCount($type)
{
    $status = OrderStatus::tryFrom($type);

    if ($status) {
        return Order::where('order_status', $status->value)->count() ?? 0;
    }

    return 0;
}

/**
 * @param  $type
 * @return int
 */
function orderPaymentStatusCount($type)
{
    $status = PaymentStatus::tryFrom($type);

    if ($status) {
        return Order::whereRelation('paymentDetails', 'payment_status', $status->value)->count() ?? 0;
    }

    return 0;
}

/**
 * @return int
 */
function pendingWithdrawRequests()
{
    return WithdrawRequest::where('status', 'pending')->count() ?? 0;
}

function pendingSellerCount()
{
    return Vendor::where('status', 0)->count() ?? 0;
}

/**
 * @param string $data
 */
function hasTitleKey(string $key, $theme, $sectionName): bool
{
    $sections = ManageThemeEnum::tryFrom($theme)?->getSections() ?? [];

    foreach ($sections as $section) {
        if (($section['name'] ?? '') !== $sectionName) {
            continue;
        }

        if (!isset($section['translations']) || !is_array($section['translations'])) {
            continue;
        }

        foreach ($section['translations'] as $translation) {
            if (!is_array($translation) || !array_key_exists($key, $translation)) {
                continue;
            }

            $value = $translation[$key];

            if (is_string($value) && preg_match('/\{[^}]+\}/', $value)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * @param $callback
 */
function pagesSection(callable | null $callback = null)
{
    $query = Section::whereNull('home_id');

    if (is_callable($callback)) {
        $query = $callback($query);
    }

    return $query->get();
}

function defaultMenus()
{
    return DefaultMenusEnum::class;
}

if (!function_exists('hasActiveChild')) {
    /**
     * @param $menu
     */
    function hasActiveChild($menu)
    {
        if (url()->current() == url($menu['link'])) {
            return true;
        }

        if (!empty($menu['child'])) {
            foreach ($menu['child'] as $child) {
                if (hasActiveChild($child)) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('notifyStockChange')) {
    /**
     * Notify admin that a product/variant/flash deal stock was updated
     *
     * @param  string $type      Product | Variant | Flash Deal
     * @param  string $name      Product or Variant name
     * @param  int    $id        Product or Variant ID
     * @param  int    $oldQty    Old stock quantity
     * @param  int    $newQty    New stock quantity
     * @param  string $orderId   Order number
     * @param  int    $productId Parent product ID (used for route)
     * @return void
     */
    function notifyStockChange(string $type, string $name, int $id, int $oldQty, int $newQty, string $orderId, int $productId): void
    {
        // Prevent notification if nothing changed
        if ($oldQty === $newQty) {
            return;
        }

        notifyAdmin(
            "{$type} Stock Updated for Order #{$orderId}",
            "{$type} `{$name}` (ID: {$id}) stock has been reduced from {$oldQty} to {$newQty} for Order #{$orderId}.",
            'info',
            route('admin.product.show', ['product' => $productId])
        );
    }
}

/**
 * @param  string  $base
 * @param  string  $modelClass
 * @param  string  $column
 * @param  bool    $isFake
 * @return mixed
 */
function generateUniqueSlug(string $base, string $modelClass, string $column = 'slug', bool $isFake = false): string
{
    $slug = str($base)->slug()->toString();

    if (!$isFake) {
        $originalSlug = $slug;
        $counter      = 1;

        while ($modelClass::where($column, $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    $maxAttempts = 50;
    $attempt     = 0;

    while ($attempt < $maxAttempts) {
        if (!$modelClass::where($column, $slug)->exists()) {
            return $slug;
        }

        $faker = fake();
        $base  = str($faker->words(2, true))->limit(10, '');
        $slug  = str($base)->slug()->toString();
        $attempt++;
    }

    return str()->uuid()->toString();
}

/**
 * @param  $methodName
 * @return mixed
 */
function getPaymentMethodLabel($methodName)
{
    $paymentService = app(PaymentMethodService::class);

    if ($paymentService->isSupportedGateway($methodName)) {
        return $paymentService->getPaymentName($methodName);
    }

    return __('Unknown');
}

function customPagination()
{
    Cache::rememberForever('CustomPagination', function () {
        $custom_pagination = CustomPagination::all();
        $pagination        = [];
        foreach ($custom_pagination as $item) {
            $pagination[str_replace(' ', '_', strtolower($item?->section_name))] = $item?->item_qty;
        }

        return (object) $pagination;
    });
}

/**
 * @param array $payload
 */
function pushToGTM(array $payload): void
{
    $existing = session('gtm_push', []);

    if (!in_array($payload, $existing, true)) {
        session()->flash('gtm_push', [...$existing, $payload]);
    }
}

function wishlistCount()
{
    return Wishlist::where('user_id', auth('web')->id())
        ->when(getSettings('show_all_homepage') == 1, function ($q) {
            $q->whereHas('product', function ($query) {
                $query->published();
            });
        })->count() ?? 0;
}
