<?php

namespace App\Providers {

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\Gate;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            Gate::before(static fn ($user) => $user->hasRole(env('APP_DEFAULT_ADMIN_ROLE')) ? true : null);
        }
    }
}
