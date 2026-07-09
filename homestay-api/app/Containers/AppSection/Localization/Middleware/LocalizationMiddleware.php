<?php

namespace App\Containers\AppSection\Localization\Middleware;

use App\Ship\Parents\Middleware\Middleware as ParentMiddleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocalizationMiddleware extends ParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->header('Accept-Language');

        $supported = config('localization.supported_locales');

        if ($locale && str_contains($locale, '-')) {
            $locale = explode('-', $locale)[0];
        }

        if (!in_array($locale, $supported)) {
            $locale = config('localization.default_locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
