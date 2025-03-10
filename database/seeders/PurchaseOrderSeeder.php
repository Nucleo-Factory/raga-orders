<?php

namespace Database\Seeders;

use App\Models\BoardingDocument;
use App\Models\Company;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\TrackingDataPO;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = Company::all();

        // Get some products to attach to orders
        $products = Product::take(10)->get();

        foreach ($companies as $company) {
            // Create 5 purchase orders for each company
            PurchaseOrder::factory()
                ->count(5)
                ->sequence(
                    ['status' => 'draft'],
                    ['status' => 'pending'],
                    ['status' => 'approved'],
                    ['status' => 'shipped'],
                    ['status' => 'delivered']
                )
                ->for($company)
                ->create()
                ->each(function ($purchaseOrder) use ($products) {
                    // Attach 2-5 random products to each order
                    $orderProducts = $products->random(rand(2, 5));

                    foreach ($orderProducts as $product) {
                        $purchaseOrder->products()->attach($product->id, [
                            'quantity' => rand(1, 10),
                            'unit_price' => $product->price_per_unit,
                        ]);
                    }

                    // Calculate and update total amount
                    $totalAmount = $purchaseOrder->products->sum(function ($product) {
                        return $product->pivot->quantity * $product->pivot->unit_price;
                    });
                    $purchaseOrder->update(['total_amount' => $totalAmount]);

                    // Add boarding documents if order is approved or later
                    if (in_array($purchaseOrder->status, ['approved', 'shipped', 'delivered'])) {
                        BoardingDocument::factory()
                            ->for($purchaseOrder)
                            ->create(['document_type' => 'invoice']);

                        BoardingDocument::factory()
                            ->for($purchaseOrder)
                            ->create(['document_type' => 'packing_list']);
                    }

                    // Add tracking data if order is shipped or delivered
                    if (in_array($purchaseOrder->status, ['shipped', 'delivered'])) {
                        TrackingDataPO::factory()
                            ->for($purchaseOrder)
                            ->create([
                                'status' => $purchaseOrder->status === 'delivered' ? 'delivered' : 'in_transit'
                            ]);
                    }
                });
        }

        // Create a test purchase order with specific data
        $testOrder = PurchaseOrder::factory()
            ->for($companies->first())
            ->create([
                'order_number' => 'TEST-PO-001',
                'status' => 'pending',
                'notes' => 'This is a test purchase order',
            ]);

        // Attach some products to the test order
        $testProducts = $products->take(3);
        foreach ($testProducts as $product) {
            $testOrder->products()->attach($product->id, [
                'quantity' => 1,
                'unit_price' => $product->price_per_unit,
            ]);
        }
    }
}
