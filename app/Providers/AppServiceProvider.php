<?php

namespace App\Providers {

    use Illuminate\Support;

    class AppServiceProvider extends Support\ServiceProvider
    {
        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            Support\Facades\Gate::before(
                static fn ($user) => $user->hasRole(env('APP_DEFAULT_ADMIN_ROLE')) ? true : null
            );
        }
    }
}
