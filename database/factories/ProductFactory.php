<?php

namespace Database\Factories {

    use Illuminate\Database;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\User;

    /**
     * @extends Factory<Product>
     */
    class ProductFactory extends Database\Eloquent\Factories\Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'name'        => $this->faker->name(),
                'price'       => $this->faker->randomFloat(2, 10),
                'category_id' => Category::factory(),
                'user_id'     => User::factory(),
            ];
        }
    }
}
