<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Electronics', 'Home Appliances', 'Furniture', 
                'Office Supplies', 'Kitchenware', 'Personal Care',
                'Sports Equipment', 'Toys', 'Books', 'Tools'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}
