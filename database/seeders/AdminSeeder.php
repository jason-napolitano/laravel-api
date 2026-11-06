<?php

namespace Database\Seeders {

    use Spatie\Permission\Models\Role;
    use Illuminate\Database\Seeder;
    use App\Models\User;

    class AdminSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // administrator
            $admin = Role::create(['name' => env('APP_DEFAULT_ADMIN_ROLE')]);
            $admin->givePermissionTo('*');

            $name = fake()->name();
            $user = User::create([
                'password'         => bcrypt('password'),
                'email'            => 'dummy@localhost',
                'name'             => $name,
            ]);
            $user->assignRole($admin);
        }
    }
}
