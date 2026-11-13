<?php

namespace App\Providers {

    use App\Services\SettingsService;
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

            $this->app->bind(SettingsService::class, fn() => new SettingsService()->instance());
        }
    }
}
