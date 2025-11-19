<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <-- PASTIKAN INI ADA DI ATAS

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // FUNGSI INI MEMBERI TAHU "SATPAM" KE MANA HARUS MENGARAHKAN TAMU
        $middleware->redirectGuestsTo(function (Request $request) {
            
            // 1. Cek apakah rute yang coba diakses diawali dengan 'portal'
            if ($request->is('portal') || $request->is('portal/*')) {
                // 2. Jika ya, arahkan ke halaman login PENYEWA
                return route('penyewa.login');
            }

            // 3. Jika tidak (misal /dashboard), arahkan ke halaman login ADMIN (default)
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();