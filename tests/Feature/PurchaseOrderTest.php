<?php

namespace Tests\Feature;

use App\Livewire\Forms\CreatePucharseOrder;
use App\Models\Company;
use App\Models\KanbanBoard;
use App\Models\KanbanStatus;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_create_a_purchase_order()
    {
        // Create a company
        $company = Company::factory()->create();

        // Create a user associated with the company
        $user = User::factory()->create([
            'company_id' => $company->id
        ]);
        $this->actingAs($user);

        // Create a kanban board with a default status
        $kanbanBoard = KanbanBoard::create([
            'name' => 'Purchase Orders',
            'description' => 'Kanban board for purchase orders',
            'company_id' => $company->id,
            'type' => 'purchase_orders',
            'is_active' => true,
        ]);

        $defaultStatus = KanbanStatus::create([
            'name' => 'New',
            'kanban_board_id' => $kanbanBoard->id,
            'position' => 1,
            'is_default' => true,
        ]);

        // Create a product to add to the order
        $product = Product::factory()->create([
            'material_id' => 'TEST-1234',
            'description' => 'Test Product',
            'price_per_unit' => 100.00
        ]);

        // Test the Livewire component
        Livewire::test(CreatePucharseOrder::class)
            ->set('order_number', 'PO-TEST-001')
            ->set('order_date', now()->format('Y-m-d'))
            ->set('currency', 'USD')
            ->set('incoterms', 'FOB')
            ->set('order_place', 'Test Location')

            // Vendor information
            ->set('vendor_id', 'VENDOR-001')
            ->set('vendor_direccion', '123 Vendor St')
            ->set('vendor_pais', 'us')
            ->set('vendor_telefono', '123-456-7890')

            // Ship to information
            ->set('ship_to_nombre', 'Test Shipping')
            ->set('ship_to_direccion', '456 Ship St')
            ->set('ship_to_pais', 'us')
            ->set('ship_to_telefono', '987-654-3210')

            // Bill to information
            ->set('bill_to_nombre', 'Test Billing')
            ->set('bill_to_direccion', '789 Bill St')
            ->set('bill_to_pais', 'us')
            ->set('bill_to_telefono', '555-555-5555')

            // Add a product to the order
            ->call('selectProduct', $product->id)
            ->set('quantity', 5)
            ->call('addProduct')

            // Set costs
            ->set('additional_cost', 50.00)
            ->set('insurance_cost', 25.00)

            // Set dimensions
            ->set('largo', 10)
            ->set('ancho', 10)
            ->set('alto', 10)
            ->set('peso_kg', 20)

            // Create the purchase order
            ->call('createPurchaseOrder');

        // Assert the purchase order was created in the database
        $this->assertDatabaseHas('purchase_orders', [
            'order_number' => 'PO-TEST-001',
            'status' => 'draft',
            'vendor_id' => 'VENDOR-001',
            'currency' => 'USD',
            'incoterms' => 'FOB',
            'kanban_status_id' => $defaultStatus->id,
        ]);

        // Get the created purchase order
        $purchaseOrder = PurchaseOrder::where('order_number', 'PO-TEST-001')->first();

        // Assert the purchase order has the correct total
        $this->assertEquals(575.00, $purchaseOrder->total);

        // Assert the product was attached to the purchase order
        $this->assertDatabaseHas('purchase_order_product', [
            'purchase_order_id' => $purchaseOrder->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 100.00,
        ]);

        // Assert the purchase order has the correct dimensions
        $this->assertEquals(10, $purchaseOrder->length);
        $this->assertEquals(10, $purchaseOrder->width);
        $this->assertEquals(10, $purchaseOrder->height);
        $this->assertEquals(20, $purchaseOrder->weight_kg);
        $this->assertEquals(44.09, round($purchaseOrder->weight_lb, 2));

        // Assert the purchase order is associated with the correct kanban status
        $this->assertEquals($defaultStatus->id, $purchaseOrder->kanban_status_id);
    }
}
