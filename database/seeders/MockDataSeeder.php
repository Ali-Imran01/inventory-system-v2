<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class MockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@ims.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        // 2. Create Categories & Units
        $categories = Category::factory(8)->create();
        $units = Unit::factory(5)->create();

        // 3. Create Warehouses & Suppliers
        $warehouses = Warehouse::factory(4)->create();
        $suppliers = Supplier::factory(10)->create();

        // 4. Create Products
        $products = Product::factory(30)->recycle($categories)->recycle($units)->create();

        // 5. Create Stock Movements
        // Initial Stock IN for all products
        foreach ($products as $product) {
            StockMovement::factory()->create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouses->random()->id,
                'type' => 'IN',
                'quantity' => rand(100, 500),
                'user_id' => $admin->id,
                'reference' => 'INITIAL-STOCK',
                'created_at' => now()->subDays(30)
            ]);
        }

        // Random Historical Movements
        for ($i = 0; $i < 200; $i++) {
            $type = rand(0, 100) > 30 ? 'OUT' : 'IN'; // More OUT than IN for realistic flow
            $product = $products->random();
            
            StockMovement::factory()->create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouses->random()->id,
                'type' => $type,
                'quantity' => rand(1, 20),
                'user_id' => $admin->id,
                'created_at' => now()->subMinutes(rand(1, 43200)) // Random time in last 30 days
            ]);
        }
    }
}
