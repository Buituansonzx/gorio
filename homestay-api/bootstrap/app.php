<?php

use Apiato\Foundation\Apiato;
use Apiato\Http\Middleware\ProcessETag;
use Apiato\Http\Middleware\ValidateJsonContent;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\HomePageController;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\LoginController;
use App\Containers\SharedSection\Room\Models\Setting;
use App\Ship\Middleware\CorsMiddleware;
use App\Ship\Middleware\ValidateAppId;
use App\Ship\Services\SettingService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServerErrorMail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use App\Ship\Kernels\ConsoleKernel;
use \App\Containers\AppSection\Localization\Middleware\LocalizationMiddleware;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

$basePath = dirname(__DIR__);
$apiato = Apiato::configure(basePath: $basePath)->create();

return Application::configure(basePath: $basePath)
    ->withProviders($apiato->providers())
    ->withEvents($apiato->events())
    ->withRouting(
        web: $apiato->webRoutes(),
        channels: __DIR__ . '/../app/Ship/Broadcasting/channels.php',
        health: '/up',
        then: static fn() => $apiato->registerApiRoutes(),
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
        $middleware->use([
            CorsMiddleware::class,
            ValidateAppId::class,
            \App\Http\Middleware\LogRequestMiddleware::class
            // ContentSecurityPolicy::class,
        ]);
        $middleware->api(prepend: [
            LocalizationMiddleware::class,
        ]);
        $middleware->api(append: [
            ValidateJsonContent::class,
            ProcessETag::class,
        ]);
        $middleware->redirectUsersTo(static function (Request $request): string {
            return action(HomePageController::class);
        });
        $middleware->redirectGuestsTo(static function (Request $request): string {
            return action([LoginController::class, 'showForm']);
        });
    })
    ->withCommands($apiato->commands())
    ->withExceptions(static function (Exceptions $exceptions) {


        $exceptions->report(function (Throwable $e) {

            // Bỏ qua lỗi nhẹ
            if ($e instanceof ValidationException ||
                $e instanceof NotFoundHttpException ||
                $e instanceof AuthenticationException) {
                return;
            }

            try {
                $setting = app(SettingService::class);
                $emails = $setting->get(Setting::DEVELOPER_EMAILS);
                $emails = array_filter(array_map('trim', explode(',', $emails)));
                $error = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ];
                Mail::to($emails)->queue(new ServerErrorMail($error));
            } catch (Throwable $mailException) {
                logger()->error('Không gửi được mail báo lỗi: ' . $mailException->getMessage());
            }
        });
    })
    ->withSingletons([ConsoleKernelContract::class => ConsoleKernel::class])
    ->create();
