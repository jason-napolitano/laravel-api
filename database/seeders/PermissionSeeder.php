<?php

namespace Database\Seeders {

    use Spatie\Permission\Models\Permission;
    use Illuminate\Database\Seeder;

    class PermissionSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // --------------------------------------------
            // categories
            Permission::create(['name' => 'update_category']);
            Permission::create(['name' => 'delete_category']);
            Permission::create(['name' => 'view_categories']);
            Permission::create(['name' => 'create_category']);
            Permission::create(['name' => 'view_category']);

            // --------------------------------------------
            // products
            Permission::create(['name' => 'update_any_product']);
            Permission::create(['name' => 'update_own_product']);
            Permission::create(['name' => 'delete_any_product']);
            Permission::create(['name' => 'delete_own_product']);
            Permission::create(['name' => 'view_all_products']);
            Permission::create(['name' => 'view_any_product']);
            Permission::create(['name' => 'view_own_product']);
            Permission::create(['name' => 'create_product']);

            // --------------------------------------------
            // admin wildcard (for frontends)
            Permission::create(['name' => '*']);
        }
    }
}
