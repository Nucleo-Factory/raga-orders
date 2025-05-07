<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\ShipTo;
use App\Models\BillTo;
use App\Models\Vendor;
use App\Models\Hub;
use App\Models\Product;
use App\Models\KanbanBoard;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    /**
     * Create a new purchase order from external API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createFromApi(Request $request): JsonResponse
    {
        $data = $request->all();
        $results = [];

        try {
            DB::beginTransaction();

            Log::info('Iniciando creación de órdenes de compra desde API', ['count' => count($data)]);

            foreach ($data as $orderData) {
                $general = $orderData['general'];

                // Find vendor by vendor_code
                $vendor = Vendor::where('vendo_code', $general['vendor_id'])->first();
                if (!$vendor) {
                    throw new \Exception("Vendor with code {$general['vendor_id']} not found");
                }

                // Find shipTo by name
                $shipTo = ShipTo::where('name', $general['ship_to_id'])->first();
                if (!$shipTo) {
                    throw new \Exception("Ship To '{$general['ship_to_id']}' not found");
                }

                // Find billTo by name
                $billTo = BillTo::where('name', $general['bill_to_id'])->first();
                if (!$billTo) {
                    throw new \Exception("Bill To '{$general['bill_to_id']}' not found");
                }

                // Find hub by code
                $hub = Hub::where('code', $general['planned_hub_id'])->first();
                if (!$hub) {
                    throw new \Exception("Hub with code {$general['planned_hub_id']} not found");
                }

                // Parse date
                $requiredDate = Carbon::createFromFormat('Y/m/d', $general['date_required_in_destination']);

                // Calculate total for items
                $netTotal = 0;
                $totalWeight = 0;

                foreach ($orderData['items'] as $item) {
                    $netTotal += $item['netValue'];
                    $totalWeight += $item['peso_kg'];
                }

                // Valores por defecto
                $companyId = $vendor->company_id;

                // Obtener un status de kanban para nuevas órdenes
                $kanbanStatusId = null;
                $kanbanBoard = KanbanBoard::where('company_id', $companyId)
                    ->where('type', 'po_stages')
                    ->where('is_active', true)
                    ->first();

                if ($kanbanBoard) {
                    $recepcionStatus = $kanbanBoard->statuses()
                        ->where('name', 'Recepción')
                        ->first();

                    if (!$recepcionStatus) {
                        $recepcionStatus = $kanbanBoard->defaultStatus();
                    }

                    if ($recepcionStatus) {
                        $kanbanStatusId = $recepcionStatus->id;
                    }
                }

                // Preparar los datos para la orden de compra
                $poData = [
                    'company_id' => $companyId,
                    'order_number' => $general['order_number'],
                    'status' => 'draft',
                    'vendor_id' => $vendor->id,
                    'ship_to_id' => $shipTo->id,
                    'bill_to_id' => $billTo->id,
                    'order_date' => now(),
                    'currency' => $general['currency'],
                    'incoterms' => $general['incoterms'],
                    'net_total' => $netTotal,
                    'total' => $netTotal,
                    'weight_kg' => $totalWeight,
                    'date_required_in_destination' => $requiredDate,
                    'planned_hub_id' => $hub->id,
                    'material_type' => json_encode(['Standard']), // Valor por defecto
                    'ensurence_type' => 'pending',
                    'mode' => $general['mode'],
                    'kanban_status_id' => $kanbanStatusId,

                    // Campos obligatorios con valores predeterminados
                    'length_cm' => 0,
                    'width_cm' => 0,
                    'height_cm' => 0
                ];

                // Valores adicionales opcionales si vienen en el JSON
                if (isset($general['length_cm'])) {
                    $poData['length_cm'] = $general['length_cm'];
                }

                if (isset($general['width_cm'])) {
                    $poData['width_cm'] = $general['width_cm'];
                }

                if (isset($general['height_cm'])) {
                    $poData['height_cm'] = $general['height_cm'];
                }

                // Filtrar valores nulos o vacíos
                $poData = array_filter($poData, function($value) {
                    return $value !== null && $value !== '' || $value === 0 || $value === 0.0;
                });

                Log::info('Datos preparados para crear la PO', ['order_number' => $general['order_number']]);

                // Crear la orden de compra
                $purchaseOrder = PurchaseOrder::create($poData);

                Log::info('Orden creada', ['id' => $purchaseOrder->id, 'order_number' => $purchaseOrder->order_number]);

                // Procesar y asociar los productos
                foreach ($orderData['items'] as $itemData) {
                    // Encontrar o crear el producto por código de material
                    $product = Product::firstOrCreate(
                        ['material_id' => $itemData['material']],
                        [
                            'short_text' => 'Product ' . $itemData['material'],
                            'unit_of_measure' => 'KG',
                            'price_per_unit' => $itemData['netValue'] / $itemData['orderQuantity'],
                        ]
                    );

                    // Convertir la cantidad a entero - PostgreSQL espera un entero para la columna quantity
                    $quantity = (int)round($itemData['orderQuantity']);

                    // Adjuntar producto a la orden de compra
                    $purchaseOrder->products()->attach($product->id, [
                        'quantity' => $quantity,
                        'unit_price' => $itemData['netValue'] / $itemData['orderQuantity'],
                    ]);
                }

                $results[] = [
                    'order_number' => $purchaseOrder->order_number,
                    'id' => $purchaseOrder->id,
                    'status' => 'success'
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase orders created successfully',
                'data' => $results
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear órdenes de compra desde API: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }
}
