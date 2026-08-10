<?php

namespace Modules\Installer\app\Enums;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

enum InstallerInfo: string {
    case LICENSE_FILE_PATH       = 'app/license.json';
    case VERIFICATION_URL        = 'https://pcv.websolutionus.com/api/v1/validate';
    case VERIFICATION_HASHED_URL = 'https://pcv.websolutionus.com/api/v1/validate/code';
    case ITEM_ID                 = '36231125';

    case DUMMY_DATABASE_PATH = 'database/backup/topcommerce_with_data.sql';
    case FRESH_DATABASE_PATH = 'database/backup/topcommerce_fresh.sql';

    public static function getDummyDatabaseFilePath(): string
    {
        return base_path(self::DUMMY_DATABASE_PATH->value);
    }

    public static function getFreshDatabaseFilePath(): string
    {
        return base_path(self::FRESH_DATABASE_PATH->value);
    }

    public static function getLicenseFilePath(): string
    {
        return storage_path(self::LICENSE_FILE_PATH->value);
    }

    public static function getAllLocalIp(): array
    {
        return [
            'localhost',
            '127.0.0.1',
            '::1',
            '0:0:0:0:0:0:0:1',
            '::ffff:127.0.0.1',
            '0:0:0:0:0:0:127.0.0.1',
            '0.0.0.0',
        ];
    }

    /**
     * @param $value
     */
    public static function isLocal($value): bool
    {
        return in_array($value, self::getAllLocalIp());
    }

    public static function isRemoteLocal(): bool
    {
        return self::isLocal(self::getRemoteAddr());
    }

    public static function getHost(): string
    {
        return parse_url(request()->root())['host'];
    }

    public static function getRemoteAddr(): string
    {
        return request()->server('REMOTE_ADDR');
    }

    public static function licenseFileExist(): bool
    {
        return File::exists(self::getLicenseFilePath());
    }

    public static function hasLocalInLicense(): bool
    {
        return self::isLocal(self::getHost());
    }

    /**
     * @param $isJson
     */
    public static function getLicenseFileData($isJson = true): mixed
    {
        if (self::licenseFileExist()) {
            if ($isJson) {
                return json_decode(file_get_contents(self::getLicenseFilePath()), true);
            }

            return file_get_contents(self::getLicenseFilePath());
        }

        return null;
    }

    public static function licenseFileDataHasLocalTrue(): bool
    {
        if ($data = self::getLicenseFileData() && !is_null(self::getLicenseFileData())) {
            return isset($data['isLocal']) && ($data['isLocal'] == true) ? true : false;
        }

        return false;
    }

    public static function deleteLicenseFile(): void
    {
        if (self::licenseFileExist()) {
            File::delete(self::getLicenseFilePath());
        }
    }

    /**
     * @param $response
     * @param $purchaseCode
     */
    public static function rewriteHashedFile($response, $purchaseCode = null): bool
    {
        if (is_array($response) && isset($response['last_updated_at']) && !is_null($response['last_updated_at'])) {
            Cache::put('last_updated_at', $response['last_updated_at']);
        }

        if (is_array($response) && array_key_exists('success', $response) && $response['success'] && array_key_exists('isLocal', $response) && $response['isLocal'] == 'false') {
            try {
                file_put_contents(self::getLicenseFilePath(), json_encode(['verification_hashed' => $response['newHash']], JSON_PRETTY_PRINT));

                return true;
            } catch (Exception $e) {
                Log::error($e->getMessage());

                return false;
            }
        } elseif (is_array($response) && array_key_exists('success', $response) && $response['success']) {
            try {
                $data = [];
                if (!is_null($purchaseCode) && InstallerInfo::isRemoteLocal()) {
                    $data['isLocal']       = true;
                    $data['purchase_code'] = $purchaseCode;
                }
                $data['verification_hashed'] = $response['verification_hashed'];
                file_put_contents(InstallerInfo::getLicenseFilePath(), json_encode($data, JSON_PRETTY_PRINT));

                return true;
            } catch (Exception $e) {
                Log::error($e->getMessage());

                return false;
            }
        }

        return false;
    }

    public static function writeAssetUrl(): bool
    {
        try {
            $plainUrl = url('/');

            $assetUrl = self::isRemoteLocal() ? $plainUrl : url('/public');

            if (config('app.asset_url') !== $assetUrl) {
                changeEnvValues('ASSET_URL', $assetUrl);
            }

            if (config('app.url') !== $plainUrl) {
                changeEnvValues('APP_URL', $plainUrl);
            }

            return true;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return false;
        }
    }
}
