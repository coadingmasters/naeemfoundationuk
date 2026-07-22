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
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'admin.region' => \App\Http\Middleware\SetAdminRegion::class,
            'super' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);

        // Share the visitor's active region (currency, phone, charity) with every view.
        $middleware->web(append: [
            \App\Http\Middleware\SetCountry::class,
        ]);

        // Send unauthenticated visitors to the admin login, and already
        // authenticated visitors away from the guest-only login page.
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // API routes, plus any caller that explicitly asks for JSON — the header
        // mini-cart posts over fetch and needs 422 validation errors back rather
        // than an HTML redirect.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
