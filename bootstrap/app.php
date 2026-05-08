<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            $adminPath = trim((string) env('ADMIN_PATH', 'panel'), '/') ?: 'panel';

            if ($request->is('admin') || $request->is('admin/*') || $request->is($adminPath) || $request->is($adminPath . '/*')) {
                return url('/' . $adminPath . '/login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $exception, Request $request) {
            $adminPath = trim((string) env('ADMIN_PATH', 'panel'), '/');
            $isAdminRequest = $request->is('admin') || $request->is('admin/*')
                || ($adminPath !== '' && ($request->is($adminPath) || $request->is($adminPath . '/*')));

            if (
                $request->expectsJson()
                || $request->is('api/*')
                || $isAdminRequest
                || $exception instanceof ValidationException
                || $exception instanceof AuthenticationException
            ) {
                return null;
            }

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $statusCode = $statusCode >= 400 ? $statusCode : 500;

            $errorTitle = match ($statusCode) {
                403 => 'Access denied',
                404 => 'Page not found',
                419 => 'Page expired',
                default => 'Something went wrong',
            };

            $errorMessage = match ($statusCode) {
                403 => 'You do not have permission to access this page.',
                404 => 'Sorry, the page you are looking for could not be found.',
                419 => 'Your session has expired. Please refresh and try again.',
                default => 'Sorry for the inconvenience. Please go back home or try again after some time.',
            };

            $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];

            return response()->view('error', compact('statusCode', 'errorTitle', 'errorMessage'), $statusCode, $headers);
        });
    })->create();
