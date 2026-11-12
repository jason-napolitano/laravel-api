<?php

namespace App\Models {

    use App\Notifications\ResetPasswordNotification;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Spatie\Permission\Traits\HasRoles;
    use Laravel\Sanctum\HasApiTokens;
    use Illuminate\Database\Eloquent;

    #[Eloquent\Attributes\Fillable(['name', 'email', 'password'])]
    #[Eloquent\Attributes\Hidden(['password', 'remember_token'])]
    class User extends Authenticatable
    {
        use Eloquent\Factories\HasFactory;
        use Eloquent\Concerns\HasUuids;
        use HasApiTokens;
        use Notifiable;
        use HasRoles;

        /**
         * Get the attributes that should be cast.
         *
         * @return array<string, string>
         */
        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password'          => 'hashed',
            ];
        }

        public function products(): Eloquent\Relations\HasMany
        {
            return $this->hasMany(Product::class, 'user_id', 'id');
        }

        public function sendPasswordResetNotification($token): void
        {
            $this->notify(new ResetPasswordNotification($token));
        }
    }
}
