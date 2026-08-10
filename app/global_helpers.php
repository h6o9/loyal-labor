<?php

/**
 * Loaded on every request via public/index.php — works even when
 * composer autoload cache on live server is stale.
 */

if (!function_exists('isDatatablesRequest')) {
    function isDatatablesRequest($request): bool
    {
        return $request->ajax() || $request->has('draw');
    }
}

if (!function_exists('asset_ver')) {
    function asset_ver(string $path): string
    {
        $fullPath = public_path(ltrim($path, '/'));
        $version = is_file($fullPath) ? filemtime($fullPath) : time();

        return asset($path) . '?v=' . $version;
    }
}
