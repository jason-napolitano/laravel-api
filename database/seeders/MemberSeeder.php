<?php

namespace Database\Seeders {

    use Spatie\Permission\Models\Role;
    use Illuminate\Database\Seeder;

    class MemberSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // administrator
            $member = Role::create(['name' => env('APP_DEFAULT_ROLE')]);

            $member->givePermissionTo('update_category');
            $member->givePermissionTo('view_categories');
            $member->givePermissionTo('create_category');
            $member->givePermissionTo('view_category');

            $member->givePermissionTo('update_own_product');
            $member->givePermissionTo('update_any_product');
            $member->givePermissionTo('delete_own_product');
            $member->givePermissionTo('delete_any_product');
            $member->givePermissionTo('view_all_products');
            $member->givePermissionTo('view_any_product');
            $member->givePermissionTo('view_own_product');
            $member->givePermissionTo('create_product');
        }
    }
}
