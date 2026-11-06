<?php

namespace App\Policies {

    use App\Models;

    class CategoryPolicy
    {
        /**
         * Determine whether the user can view any models.
         *
         * @param Models\User $user
         *
         * @return bool
         */
        public function viewAny(Models\User $user): bool
        {
            return $user->can('view_categories');
        }

        /**
         * Determine whether the user can view the model.
         *
         * @param Models\User     $user
         * @param Models\Category $category
         *
         * @return bool
         */
        public function view(Models\User $user, Models\Category $category): bool
        {
            return $user->can('view_category');
        }

        /**
         * Determine whether the user can create models.
         *
         * @param Models\User $user
         *
         * @return bool
         */
        public function create(Models\User $user): bool
        {
            return $user->can('create_category');
        }

        /**
         * Determine whether the user can update the model.
         *
         * @param Models\User     $user
         * @param Models\Category $category
         *
         * @return bool
         */
        public function update(Models\User $user, Models\Category $category): bool
        {
            return $user->can('update_category');
        }

        /**
         * Determine whether the user can delete the model.
         *
         * @param Models\User     $user
         * @param Models\Category $category
         *
         * @return bool
         */
        public function delete(Models\User $user, Models\Category $category): bool
        {
            return $user->can('delete_category');
        }
    }
}
