<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel terminates HTTPS before forwarding the request to PHP.
        // Trust its forwarding headers so redirects and secure cookies keep
        // using https:// instead of falling back to http://.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Keep the root cause visible in serverless logs before a long stack
        // trace is truncated by the hosting log viewer.
        $exceptions->report(function (Throwable $exception): void {
            error_log(sprintf(
                '[Laravel exception] %s: %s in %s:%d',
                $exception::class,
                substr($exception->getMessage(), 0, 2000),
                $exception->getFile(),
                $exception->getLine(),
            ));
        });
    })->create();
