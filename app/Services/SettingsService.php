<?php

namespace App\Services {

    use App\Models\Setting;

    class SettingsService
	{
        public function instance(): Setting
        {
            return Setting::query()->first();
        }
	}
}
