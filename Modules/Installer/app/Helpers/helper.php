<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Installer\app\Enums\InstallerInfo;
use Modules\Installer\app\Models\Configuration;

if (! function_exists('setup_complete_status')) {
    function setupStatus()
    {
        $cacheKey = 'setup_complete_status';
        if (! Cache::has($cacheKey)) {
            try {
                Cache::rememberForever($cacheKey, function () {
                    return Configuration::where('config', 'setup_complete')->first()?->value == 0 ? false : true;
                });
            } catch (Exception $e) {
                Log::error($e->getMessage());
                Cache::rememberForever($cacheKey, function () {
                    return false;
                });
            }
        }

        return Cache::get($cacheKey);
    }
}
if (! function_exists('purchaseVerificationHashed')) {
    function purchaseVerificationHashed($filepath, $isLocal = false)
    {
        if (file_exists($filepath)) {
            $licenseFile = InstallerInfo::getLicenseFileData();

            $data = [];

            if ($isLocal) {
                $data['isLocal'] = InstallerInfo::licenseFileDataHasLocalTrue() ? 'false' : 'true';
                $data['purchase_code'] = $licenseFile['purchase_code'];
            }
            $data['verification_hashed'] = $licenseFile['verification_hashed'];
            $data['incoming_url'] = InstallerInfo::getHost();
            $data['incoming_ip'] = InstallerInfo::getRemoteAddr();

            return Http::post(InstallerInfo::VERIFICATION_HASHED_URL->value, $data)->json();
        } else {
            return false;
        }
    }
}

if (! function_exists('changeEnvValues')) {
    function changeEnvValues($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key.'='.env($key),
            $key.'='.$value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}