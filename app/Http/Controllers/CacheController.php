<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    /**
     * Clear all application caches
     */
    public function clearAllCache()
    {
        try {
            // Clear Laravel cache
            Artisan::call('cache:clear');
            $cacheClear = Artisan::output();
            
            // Clear config cache
            Artisan::call('config:clear');
            $configClear = Artisan::output();
            
            // Clear permission cache (Spatie)
            Artisan::call('permission:cache-reset');
            $permissionClear = Artisan::output();
            
            // Clear view cache
            Artisan::call('view:clear');
            $viewClear = Artisan::output();
            
            // Clear route cache
            Artisan::call('route:clear');
            $routeClear = Artisan::output();
            
            // Clear application cache
            Cache::flush();
            
            // Return success response
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'All caches cleared successfully!',
                    'commands_output' => [
                        'cache:clear' => $cacheClear,
                        'config:clear' => $configClear,
                        'permission:cache-reset' => $permissionClear,
                        'view:clear' => $viewClear,
                        'route:clear' => $routeClear
                    ]
                ]);
            }
            
            // HTML response for browser
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <title>Cache Cleared</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        background: white;
                        border-radius: 10px;
                        padding: 30px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                        max-width: 600px;
                        width: 100%;
                        text-align: center;
                    }
                    .success-icon {
                        color: #4CAF50;
                        font-size: 60px;
                        margin-bottom: 20px;
                    }
                    h1 {
                        color: #333;
                        margin-bottom: 10px;
                    }
                    .message {
                        color: #4CAF50;
                        font-size: 18px;
                        margin-bottom: 20px;
                        padding: 10px;
                        background: #e8f5e9;
                        border-radius: 5px;
                    }
                    .commands {
                        text-align: left;
                        background: #f5f5f5;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                    }
                    .commands h3 {
                        margin-top: 0;
                        color: #555;
                    }
                    .commands p {
                        margin: 5px 0;
                        font-family: monospace;
                        font-size: 12px;
                        color: #666;
                    }
                    .button {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 10px 20px;
                        background: #667eea;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        transition: background 0.3s;
                    }
                    .button:hover {
                        background: #5a67d8;
                    }
                    .back-button {
                        background: #4CAF50;
                        margin-left: 10px;
                    }
                    .back-button:hover {
                        background: #45a049;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="success-icon">✓</div>
                    <h1>Cache Cleared Successfully!</h1>
                    <div class="message">
                        All application caches have been cleared.
                    </div>
                    
                    <div>
                        <a href="javascript:history.back()" class="button back-button">← Go Back</a>                    </div>
                    
                    <div class="commands">
                        <h3>Commands Executed:</h3>
                        <p><strong>cache:clear</strong> - ' . htmlspecialchars(trim($cacheClear)) . '</p>
                        <p><strong>config:clear</strong> - ' . htmlspecialchars(trim($configClear)) . '</p>
                        <p><strong>permission:cache-reset</strong> - ' . htmlspecialchars(trim($permissionClear)) . '</p>
                        <p><strong>view:clear</strong> - ' . htmlspecialchars(trim($viewClear)) . '</p>
                        <p><strong>route:clear</strong> - ' . htmlspecialchars(trim($routeClear)) . '</p>
                        <p><strong>Cache::flush()</strong> - Application cache flushed</p>
                    </div>
                </div>
            </body>
            </html>';
            
            return response($html);
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error clearing cache: ' . $e->getMessage()
                ], 500);
            }
            
            return '<!DOCTYPE html>
            <html>
            <head>
                <title>Error</title>
                <style>
                    body { font-family: Arial, sans-serif; background: #f44336; color: white; text-align: center; padding: 50px; }
                    .container { background: white; color: #333; border-radius: 10px; padding: 30px; max-width: 500px; margin: 0 auto; }
                    .error-icon { color: #f44336; font-size: 60px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="error-icon">✗</div>
                    <h1>Error Clearing Cache</h1>
                    <p>' . $e->getMessage() . '</p>
                    <a href="javascript:history.back()" class="button">Go Back</a>
                </div>
            </body>
            </html>';
        }
    }
    
    /**
     * Clear only permission cache
     */
    public function clearPermissionCache()
    {
        try {
            Artisan::call('permission:cache-reset');
            
            return response()->json([
                'success' => true,
                'message' => 'Permission cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear specific cache type
     */
    public function clearSpecificCache($type)
    {
        $validTypes = ['cache', 'config', 'view', 'route', 'permission'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cache type. Allowed: ' . implode(', ', $validTypes)
            ], 400);
        }
        
        try {
            $command = match($type) {
                'cache' => 'cache:clear',
                'config' => 'config:clear',
                'view' => 'view:clear',
                'route' => 'route:clear',
                'permission' => 'permission:cache-reset',
            };
            
            Artisan::call($command);
            
            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}