<?php

namespace App\Observers {

    use App\Models\Category;

    class CategoryObserver
    {
        /**
         * Handle the Category `creating` event.
         */
        public function creating(Category $category): void
        {
            $category['slug'] = str($category['name'])->slug();
        }

        /**
         * Handle the Category `updating` event.
         */
        public function updating(Category $category): void
        {
            $category['slug'] = str($category['name'])->slug();
        }
    }
}
