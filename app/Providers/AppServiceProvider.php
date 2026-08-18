<?php

namespace App\Providers;

use App\Services\CartService;
use App\Services\MailSenderService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\CustomMenu\app\Enums\DefaultMenusEnum;
use Modules\GlobalSetting\app\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!function_exists('isDatatablesRequest')) {
            function isDatatablesRequest($request): bool
            {
                return $request->ajax() || $request->has('draw');
            }
        }

        if (!function_exists('asset_ver')) {
            /**
             * Fallback when composer autoload on server is stale/missing helper.php.
             */
            function asset_ver(string $path): string
            {
                $fullPath = public_path(ltrim($path, '/'));
                $version = is_file($fullPath) ? filemtime($fullPath) : time();

                return asset($path) . '?v=' . $version;
            }
        }

        $this->app->singleton('wsuscart', function ($app) {
            return new CartService();
        });

        $this->app->singleton('wsusmailsender', function ($app) {
            return new MailSenderService();
        });
    }

    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        try {
            $setting = Cache::rememberForever('setting', function () {
                return (object) Setting::select('key', 'value')->get()
                    ->pluck('value', 'key')
                    ->toArray();
            });

            $this->setupMailConfiguration($setting);
            $this->setupTimezone($setting);
            $this->shareViewData($setting);

        } catch (Exception $ex) {

            logError('Error in AppServiceProvider: ' . $ex->getMessage(), $ex);

            if (strtolower(config('app.app_mode')) == 'live' && !app()->isLocal()) {
                Artisan::call('optimize:clear');
                http_response_code(500);
                echo view('errors.init-failed', [
                    'error' => $ex->getMessage()
                ])->render();
                exit;
            }
        }

        $this->registerBladeDirectives();

        Paginator::useBootstrapFour();

        $this->setPaginationForCollection();

        view()->share('nonce', base64_encode(random_bytes(16)));

        $this->loadViewsFrom(resource_path('views/website/components'), 'components');

        // ❌ Seller views removed
        // $this->loadViewsFrom(resource_path('views/seller'), 'vendor');
    }

    protected function setupMailConfiguration($setting): void
    {
        $mailConfig = [
            'transport'  => 'smtp',
            'host'       => $setting?->mail_host,
            'port'       => $setting?->mail_port,
            'encryption' => $setting?->mail_encryption,
            'username'   => $setting?->mail_username,
            'password'   => $setting?->mail_password,
            'timeout'    => null,
        ];

        config(['mail.mailers.smtp' => $mailConfig]);

        $senderName = loyalSanitizeBrandText((string) ($setting?->mail_sender_name ?? ''));
        $smtpUser = trim((string) ($setting?->mail_username ?? ''));
        $senderEmail = trim((string) ($setting?->mail_sender_email ?? ''));

        // SMTP servers reject From addresses that are not the authenticated mailbox.
        $fromAddress = $smtpUser !== '' ? $smtpUser : $senderEmail;

        config(['mail.from.address' => $fromAddress]);
        config(['mail.from.name' => $senderName !== '' ? $senderName : loyalBrandName()]);
    }

    protected function setupTimezone($setting): void
    {
        config(['app.timezone' => $setting?->timezone]);
    }

    protected function setPaginationForCollection(): void
    {
        Collection::macro('paginate', function ($perPage = 16, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path'     => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function registerBladeDirectives(): void
    {
        // ✅ ADMIN
        Blade::directive('adminCan', function ($permission) {
            return "<?php 
                \$__adminCanUser = auth()->guard('admin')->user();
                if(\$__adminCanUser && method_exists(\$__adminCanUser, 'can') && \$__adminCanUser->can({$permission})):
            ?>";
        });

        Blade::directive('endadminCan', fn() => '<?php endif; ?>');

        // ✅ STAFF
        Blade::directive('staffCan', function ($permission) {
            return "<?php 
                \$__staffCanUser = auth()->guard('staff')->user();
                if(\$__staffCanUser && method_exists(\$__staffCanUser, 'can') && \$__staffCanUser->can({$permission})):
            ?>";
        });

        Blade::directive('endstaffCan', fn() => '<?php endif; ?>');

        // ✅ GENERIC CAN
        // NOTE: uses a uniquely-named variable ($__genericCanUser) on purpose so it never
        // clobbers a same-named loop variable in the view (e.g. @foreach($users as $user)).
        Blade::directive('can', function ($permission) {
            return "<?php 
                \$__genericCanUser = auth()->user() ?? auth()->guard('staff')->user() ?? auth()->guard('admin')->user();
                if(\$__genericCanUser && method_exists(\$__genericCanUser, 'can') && \$__genericCanUser->can({$permission})):
            ?>";
        });

        Blade::directive('endcan', fn() => '<?php endif; ?>');

        // ✅ AUTH CHECK
        Blade::directive('authcheck', function ($guard = 'staff') {
            return "<?php if(auth()->guard({$guard})->check()): ?>";
        });

        Blade::directive('endauthcheck', fn() => '<?php endif; ?>');
    }

    public function shareViewData($setting): void
    {
        try {
            $defaultMenus = DefaultMenusEnum::class;

            config([
                'custom.admin_login_prefix' => $setting->admin_login_prefix ?? 'admin',
            ]);

            View::share('setting', $setting);
            View::share('defaultMenus', $defaultMenus);

            // Scoped composers — avoid auth lookups on every website/email partial
            View::composer('staff.*', function ($view) {
                $staffUser = auth()->guard('staff')->user();
                $view->with('staffUser', $staffUser);
                $view->with('isStaffLoggedIn', !is_null($staffUser));
            });

            View::composer('admin.*', function ($view) {
                $adminUser = auth()->guard('admin')->user();
                $view->with('adminUser', $adminUser);
                $view->with('isAdminLoggedIn', !is_null($adminUser));
            });

        } catch (Exception $e) {
            logError("Error in ViewDataService::shareViewData: ", $e);
            abort(500, $e->getMessage());
        }
    }
}