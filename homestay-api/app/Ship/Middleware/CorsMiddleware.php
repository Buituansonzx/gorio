<?php

namespace App\Ship\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() == "OPTIONS") {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        // Get CORS configuration from ENV
        $allowedOrigins = explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000'));
        $allowedMethods = env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS,PATCH');
        $allowedHeaders = env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With,Accept,Origin');
        $allowCredentials = env('CORS_ALLOW_CREDENTIALS', 'true');
        $maxAge = env('CORS_MAX_AGE', 86400);

        // Set origin header (handle multiple origins)
        $origin = $request->headers->get('Origin');
        if (in_array($origin, $allowedOrigins) || in_array('*', $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin ?: $allowedOrigins[0]);
        }

        // Add other CORS headers
        $response->headers->set('Access-Control-Allow-Methods', $allowedMethods);
        $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
        $response->headers->set('Access-Control-Allow-Credentials', $allowCredentials);
        $response->headers->set('Access-Control-Max-Age', $maxAge);

        return $response;
    }
}
