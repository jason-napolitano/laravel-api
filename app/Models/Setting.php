<?php

namespace App\Models {

    use Illuminate\Database\Eloquent;

    /**
     * @property $enable_password_reset
     */
    #[Eloquent\Attributes\Fillable('enable_password_reset', 'enable_otp_support')]
    class Setting extends Eloquent\Model
    {
        use Eloquent\Concerns\HasUuids;

        protected function casts(): array
        {
            return [
                'enable_password_reset' => 'boolean',
                'enable_otp_support'    => 'boolean',
            ];
        }
    }
}
