<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatatablesRequestMiddleware
{
    /**
     * Some live servers / proxies strip X-Requested-With, so Laravel's
     * $request->ajax() returns false and controllers return HTML instead
     * of JSON — leaving every DataTables index page empty.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('draw') && !$request->ajax()) {
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }

        return $next($request);
    }
}
