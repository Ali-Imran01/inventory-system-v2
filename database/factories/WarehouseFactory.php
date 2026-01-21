<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
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
                'Main Hub', 'District North', 'South Logisitics', 'Regional Outlet', 'Primary Depot'
            ]),
            'location' => $this->faker->address(),
            'is_active' => true,
        ];
    }
}
