<?php

namespace App\Ship\Middleware;

use App\Ship\Parents\Middleware\Middleware as ParentMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentSecurityPolicy extends ParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $response = $next($request);

        // Don't override CSP header if it's already set (e.g., by controller)
        if ($response->headers->has('Content-Security-Policy')) {
            return $response;
        }

        // Set default CSP header for routes that don't have one
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self' http: https: data: blob: 'unsafe-inline' 'unsafe-eval'"
        );

        return $response;
    }
}
