<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('PRD-####-????')),
            'name' => $this->faker->words(3, true),
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'cost_price' => $this->faker->randomFloat(2, 5, 500),
            'sell_price' => function (array $attributes) {
                return $attributes['cost_price'] * $this->faker->randomFloat(2, 1.2, 2);
            },
            'min_stock' => $this->faker->numberBetween(5, 50),
        ];
    }
}
