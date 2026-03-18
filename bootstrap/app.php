<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            '/midtrans/notification',
        ]);
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\Superadmin::class,
            'admin_kabupaten' => \App\Http\Middleware\AdminKabupaten::class,
            'user' => \App\Http\Middleware\UserBumdes::class,
            'premium_check' => \App\Http\Middleware\CheckPremiumFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            return back()->with('error', 'Ukuran file yang Anda upload terlalu besar. Harap perkecil ukuran file atau cek batasan server.');
        });
    })->create();
