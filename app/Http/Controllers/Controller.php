<?php

namespace App\Http\Controllers {

    use App\Services\SettingsService;
    use App\Models\Setting;

    abstract class Controller
    {
        /**
         * Injection of the SettingsService
         */
        public function __construct(protected ?Setting $settings = null)
        {
            $this->settings = app(SettingsService::class);
        }
    }
}
