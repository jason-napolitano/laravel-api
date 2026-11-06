<?php

namespace App\Policies {

    use App\Models;

    class ProductPolicy
    {
        /**
         * Determine whether the user can view any models.
         */
        public function viewAny(Models\User $user): bool
        {
            return $user->can('view_all_products');
        }

        /**
         * Determine whether the user can view the model.
         */
        public function view(Models\User $user, Models\Product $product): bool
        {
            return $user->can('view_any_product');
        }

        /**
         * Determine whether the user can create models.
         */
        public function create(Models\User $user): bool
        {
            return $user->can('create_product');
        }

        /**
         * Determine whether the user can update the model.
         */
        public function update(Models\User $user, Models\Product $product): bool
        {
            return $this->isOwnerWhoCan($user, $product, 'update_own_product');
        }

        /**
         * Determine whether the user can delete the model.
         */
        public function delete(Models\User $user, Models\Product $product): bool
        {
            return $this->isOwnerWhoCan($user, $product, 'delete_own_product');
        }

        public function isOwnerWhoCan(Models\User $user, Models\Product $product, string $permission): bool
        {
            return $user->is($product->user) && $user->can($permission);
        }
    }
}
