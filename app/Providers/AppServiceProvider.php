<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || env('VERCEL') || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Storage::extend('database', function ($app, $config) {
            $adapter = new \App\Filesystem\DatabaseAdapter();
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

        // Register Filament view hooks for Login Remember-me and User profile caching
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn () => view('filament.hooks.login-remember-js')
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::body.start',
            function () {
                if (auth()->check()) {
                    $user = auth()->user();
                    return '<script>
                        localStorage.setItem("nks_last_user_name", ' . json_encode($user->name) . ');
                        localStorage.setItem("nks_last_user_avatar", ' . json_encode($user->avatar_url) . ');
                        localStorage.setItem("nks_last_user_email", ' . json_encode($user->email) . ');
                    </script>';
                }
                return '';
            }
        );
    }
}
