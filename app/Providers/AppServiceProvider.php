<?php

namespace App\Providers {

    use Illuminate\Support\Facades\RateLimiter;
    use Illuminate\Cache\RateLimiting\Limit;
    use Illuminate\Support;

    class AppServiceProvider extends Support\ServiceProvider
    {
        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            Support\Facades\Gate::before(
                static fn ($user) => $user->hasRole(config('auth.default_admin_role')) ? true : null
            );

            RateLimiter::for('login', function ($request) {
                return Limit::perMinute(5)->by(
                    strtolower($request->input('email')).'|'.$request->ip()
                );
            });
        }
    }
}
