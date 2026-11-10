<?php

namespace Database\Factories {

    use Illuminate\Database\Eloquent;

    class CategoryFactory extends Eloquent\Factories\Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'name'        => fake()->name(),
                'description' => fake()->paragraph(3),
            ];
        }
    }
}
