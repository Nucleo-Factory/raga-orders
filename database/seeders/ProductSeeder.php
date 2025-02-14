<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 random active products
        Product::factory()
            ->active()
            ->count(20)
            ->create();

        // Create 5 inactive products
        Product::factory()
            ->inactive()
            ->count(5)
            ->create();

        // Create some specific test products
        $testProducts = [
            [
                'name' => 'Test Product 1',
                'description' => 'This is a test product with high stock',
                'price' => 99.99,
                'sku' => 'TEST-001',
                'stock' => 100,
                'status' => 'active',
            ],
            [
                'name' => 'Test Product 2',
                'description' => 'This is a test product with low stock',
                'price' => 149.99,
                'sku' => 'TEST-002',
                'stock' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Test Product 3',
                'description' => 'This is an inactive test product',
                'price' => 199.99,
                'sku' => 'TEST-003',
                'stock' => 0,
                'status' => 'inactive',
            ],
        ];

        foreach ($testProducts as $product) {
            Product::factory()->create($product);
        }
    }
}
