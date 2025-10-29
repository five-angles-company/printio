<?php

use App\Http\Middleware\HandleInertiaRequests;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            Log::error('Error: ' . $e->getMessage());
        });
        $exceptions->render(function (Throwable $e, $request) {
            if (
                $e instanceof ValidationException ||
                $e instanceof AuthenticationException ||
                $e instanceof AuthorizationException
            ) {
                return null;
            }

            if (method_exists($e, 'render')) {
                return null;
            }

            if (app()->isLocal()) {
                return null;
            }
            Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Something went wrong. Please try again.');
        });
    })->create();
