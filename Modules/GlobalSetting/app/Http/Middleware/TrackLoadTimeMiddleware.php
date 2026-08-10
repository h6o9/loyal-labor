<?php

namespace Modules\GlobalSetting\app\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackLoadTimeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $start = microtime(true);

            $response = $next($request);

            $loadTime = round(microtime(true) - $start, 2);

            if (
                $response instanceof \Illuminate\Http\Response  &&
                str_contains($response->headers->get('Content-Type'), 'text/html') &&
                str_contains($response->getContent(), '%%LOAD_TIME%%')
            ) {
                $content = $response->getContent();
                $content = str_replace('%%LOAD_TIME%%', "{$loadTime} s", $content);
                $response->setContent($content);
            }

            return $response;
        } catch (Exception $e) {
            Log::error('LoadTimeMiddleware error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
