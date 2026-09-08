<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Vercel serverless runtime preparation
|--------------------------------------------------------------------------
|
| A Vercel Function may only write to the system temporary directory.  The
| application storage path and every Laravel-generated cache therefore have
| to be redirected before Laravel is bootstrapped.  Values are deliberately
| enforced here so a stale Vercel dashboard variable cannot send logging or
| sessions back to the read-only deployment directory.
|
*/
if (getenv('VERCEL') !== false) {
    register_shutdown_function(static function (): void {
        $error = error_get_last();

        if ($error === null || ! in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            return;
        }

        error_log(sprintf(
            '[Laravel fatal] %s in %s:%d',
            $error['message'],
            $error['file'],
            $error['line'],
        ));
    });

    $runtimeRoot = rtrim(sys_get_temp_dir(), '/\\').DIRECTORY_SEPARATOR.'panti-asuhan';
    $storagePath = $runtimeRoot.DIRECTORY_SEPARATOR.'storage';
    $bootstrapCachePath = $runtimeRoot.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache';

    $writableDirectories = [
        $bootstrapCachePath,
        $storagePath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'private',
        $storagePath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'photos',
        $storagePath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'videos',
        $storagePath.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'data',
        $storagePath.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'sessions',
        $storagePath.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views',
        $storagePath.DIRECTORY_SEPARATOR.'logs',
    ];

    foreach ($writableDirectories as $directory) {
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException("Unable to prepare writable runtime directory: {$directory}");
        }
    }

    $runtimeEnvironment = [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'LARAVEL_STORAGE_PATH' => $storagePath,
        // The exception callback in bootstrap/app.php writes a concise message
        // directly to stderr. Suppress Laravel's multi-kilobyte stack trace on
        // Vercel because the platform keeps only its tail and hides the cause.
        'LOG_CHANNEL' => 'null',
        'LOG_STACK' => 'null',
        'CACHE_STORE' => 'array',
        'SESSION_DRIVER' => 'cookie',
        'SESSION_ENCRYPT' => 'true',
        'VIEW_COMPILED_PATH' => $storagePath.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views',
    ];

    // Laravel recognises Unix absolute cache paths natively. Vercel runs on
    // Linux; leaving these unset during a Windows-only local simulation keeps
    // drive-letter paths from being interpreted as paths relative to the app.
    if (DIRECTORY_SEPARATOR !== '\\') {
        $runtimeEnvironment += [
            'APP_CONFIG_CACHE' => $bootstrapCachePath.DIRECTORY_SEPARATOR.'config.php',
            'APP_EVENTS_CACHE' => $bootstrapCachePath.DIRECTORY_SEPARATOR.'events.php',
            'APP_PACKAGES_CACHE' => $bootstrapCachePath.DIRECTORY_SEPARATOR.'packages.php',
            'APP_ROUTES_CACHE' => $bootstrapCachePath.DIRECTORY_SEPARATOR.'routes-v7.php',
            'APP_SERVICES_CACHE' => $bootstrapCachePath.DIRECTORY_SEPARATOR.'services.php',
        ];
    }

    foreach ($runtimeEnvironment as $name => $value) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    if (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === '/__deployment-diagnostic') {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store');

        $stage = 'autoload';

        try {
            require_once __DIR__.'/../vendor/autoload.php';

            $stage = 'application';
            $application = require __DIR__.'/../bootstrap/app.php';

            $stage = 'bootstrap';
            $kernel = $application->make(Illuminate\Contracts\Http\Kernel::class);
            $diagnosticRequest = Illuminate\Http\Request::create('/members', 'GET');
            $application->instance('request', $diagnosticRequest);
            Illuminate\Http\Request::clearResolvedInstance();
            $kernel->bootstrap();

            $stage = 'database';
            Illuminate\Support\Facades\DB::select('select 1');

            $stage = 'routes';
            Illuminate\Support\Facades\Route::getRoutes()->count();

            $stage = 'view';
            view('members.index')->render();

            echo json_encode([
                'ok' => true,
                'stage' => 'complete',
                'pdo_mysql' => extension_loaded('pdo_mysql'),
            ], JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            http_response_code(500);
            error_log(sprintf(
                '[Deployment diagnostic] stage=%s exception=%s message=%s',
                $stage,
                $exception::class,
                $exception->getMessage(),
            ));

            echo json_encode([
                'ok' => false,
                'stage' => $stage,
                'exception' => $exception::class,
            ], JSON_THROW_ON_ERROR);
        }

        exit;
    }
}

require __DIR__.'/../public/index.php';
