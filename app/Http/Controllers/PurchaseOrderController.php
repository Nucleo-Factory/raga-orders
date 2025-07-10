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
        try {
            DB::beginTransaction();

            $data = $request->all();
            $results = [];

            // Verificar si el JSON es un array o un objeto simple
            if (!isset($data['general'])) {
                // Es un array de órdenes
                Log::info('Recibido array de órdenes', ['count' => count($data)]);
                $orders = $data;
            } else {
                // Es una sola orden
                Log::info('Recibida una sola orden', ['order' => $data['general']['order_number'] ?? 'unknown']);
                $orders = [$data];
            }

            foreach ($orders as $orderData) {
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

                // Calcular el peso total a partir de los items
                $totalWeight = 0;
                foreach ($orderData['items'] as $item) {
                    $totalWeight += $item['peso_kg'];
                }

                // Obtener el netValue directamente del JSON
                $netTotal = $general['netValue'];

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
                    'net_total' => $netTotal, // Usar el valor exacto del JSON
                    'total' => $netTotal, // El total también es el mismo valor
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

                Log::info('Datos preparados para crear la PO', [
                    'order_number' => $general['order_number'],
                    'net_total' => $netTotal
                ]);

                // Crear la orden de compra
                $purchaseOrder = PurchaseOrder::create($poData);

                Log::info('Orden creada', [
                    'id' => $purchaseOrder->id,
                    'order_number' => $purchaseOrder->order_number,
                    'net_total' => $purchaseOrder->net_total
                ]);

                // Procesar y asociar los productos
                foreach ($orderData['items'] as $itemData) {
                    // Buscar el producto por material_id
                    $product = Product::where('material_id', $itemData['material'])->first();

                    $newPrice = (float) $itemData['price_per_unit'];
                    $priceUpdated = false;

                    if ($product) {
                        // Verificar si el precio ha cambiado
                        $currentPrice = (float) $product->price_per_unit;

                        if (abs($currentPrice - $newPrice) > 0.0001) { // Comparar con margen para evitar problemas de redondeo
                            // Actualizar el precio si es diferente
                            Log::info("Actualizando precio del producto {$product->material_id}", [
                                'old_price' => $currentPrice,
                                'new_price' => $newPrice
                            ]);

                            $product->price_per_unit = $newPrice;
                            $product->save();
                            $priceUpdated = true;
                        }
                    } else {
                        // Si el producto no existe, crearlo
                        $product = Product::create([
                            'material_id' => $itemData['material'],
                            'short_text' => 'Product ' . $itemData['material'],
                            'unit_of_measure' => 'KG',
                            'price_per_unit' => $newPrice,
                        ]);

                        Log::info("Producto creado con ID {$product->id}", [
                            'material_id' => $itemData['material'],
                            'price' => $newPrice
                        ]);

                        $priceUpdated = true; // Consideramos que un producto nuevo también tiene "precio actualizado"
                    }

                    // Usar peso_kg como la cantidad del producto
                    $quantity = (int) round($itemData['peso_kg']);

                    // Adjuntar producto a la orden de compra
                    $purchaseOrder->products()->attach($product->id, [
                        'quantity' => $quantity,
                        'unit_price' => $newPrice, // Usar el nuevo precio para esta orden
                    ]);

                    Log::info("Producto asociado a la orden", [
                        'product_id' => $product->id,
                        'material_id' => $product->material_id,
                        'quantity' => $quantity,
                        'unit_price' => $newPrice
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

    /**
     * Get a purchase order by ID or order number
     *
     * @param string $identifier
     * @return JsonResponse
     */
    public function getOrder(string $identifier): JsonResponse
    {
        try {
            // Buscar por ID si es numérico, o por order_number si no
            $isNumeric = is_numeric($identifier);
            $purchaseOrder = $isNumeric 
                ? PurchaseOrder::find($identifier) 
                : PurchaseOrder::where('order_number', $identifier)->first();

            if (!$purchaseOrder) {
                return response()->json([
                    'success' => false,
                    'message' => $isNumeric 
                        ? "Purchase order with ID {$identifier} not found" 
                        : "Purchase order with number {$identifier} not found",
                    'data' => null
                ], 404);
            }

            // Cargar relaciones importantes
            $purchaseOrder->load([
                'vendor', 
                'shipTo', 
                'billTo', 
                'products', 
                'plannedHub', 
                'actualHub', 
                'kanbanStatus',
                'trackingData'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase order retrieved successfully',
                'data' => $purchaseOrder
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener orden de compra: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving purchase order: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * List all purchase orders with pagination and filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listOrders(Request $request): JsonResponse
    {
        try {
            $query = PurchaseOrder::query();

            // Aplicar filtros si existen
            if ($request->has('vendor_id')) {
                $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }

            if ($request->has('date_from')) {
                $query->whereDate('order_date', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('order_date', '<=', $request->date_to);
            }

            if ($request->has('kanban_status_id')) {
                $query->where('kanban_status_id', $request->kanban_status_id);
            }

            // Ordenar resultados
            $sortField = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Cargar relaciones importantes
            $query->with([
                'vendor', 
                'shipTo', 
                'billTo', 
                'kanbanStatus'
            ]);

            // Paginar resultados
            $perPage = $request->get('per_page', 15);
            $purchaseOrders = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Purchase orders retrieved successfully',
                'data' => $purchaseOrders
            ]);

        } catch (\Exception $e) {
            Log::error('Error al listar órdenes de compra: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error listing purchase orders: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Update the status of a purchase order
     *
     * @param Request $request
     * @param string $identifier
     * @return JsonResponse
     */
    public function updateStatus(Request $request, string $identifier): JsonResponse
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'status' => 'required|string|in:draft,pending,in_progress,completed,cancelled',
                'notes' => 'nullable|string',
                'kanban_status_id' => 'nullable|exists:kanban_statuses,id',
            ]);

            // Buscar por ID si es numérico, o por order_number si no
            $isNumeric = is_numeric($identifier);
            $purchaseOrder = $isNumeric 
                ? PurchaseOrder::find($identifier) 
                : PurchaseOrder::where('order_number', $identifier)->first();

            if (!$purchaseOrder) {
                return response()->json([
                    'success' => false,
                    'message' => $isNumeric 
                        ? "Purchase order with ID {$identifier} not found" 
                        : "Purchase order with number {$identifier} not found",
                    'data' => null
                ], 404);
            }

            // Actualizar el estado
            $purchaseOrder->status = $validated['status'];
            
            // Actualizar notas si se proporcionan
            if (isset($validated['notes'])) {
                $purchaseOrder->notes = $validated['notes'];
            }
            
            // Actualizar el estado del kanban si se proporciona
            if (isset($validated['kanban_status_id'])) {
                $purchaseOrder->kanban_status_id = $validated['kanban_status_id'];
            }
            
            $purchaseOrder->save();

            // Cargar relaciones importantes
            $purchaseOrder->load([
                'vendor', 
                'shipTo', 
                'billTo', 
                'kanbanStatus'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase order status updated successfully',
                'data' => $purchaseOrder
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de orden de compra: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating purchase order status: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Add a comment to a purchase order
     *
     * @param Request $request
     * @param string $identifier
     * @return JsonResponse
     */
    public function addComment(Request $request, string $identifier): JsonResponse
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'comment' => 'required|string',
                'user_id' => 'required|exists:users,id',
            ]);

            // Buscar por ID si es numérico, o por order_number si no
            $isNumeric = is_numeric($identifier);
            $purchaseOrder = $isNumeric 
                ? PurchaseOrder::find($identifier) 
                : PurchaseOrder::where('order_number', $identifier)->first();

            if (!$purchaseOrder) {
                return response()->json([
                    'success' => false,
                    'message' => $isNumeric 
                        ? "Purchase order with ID {$identifier} not found" 
                        : "Purchase order with number {$identifier} not found",
                    'data' => null
                ], 404);
            }

            // Crear el comentario
            $comment = $purchaseOrder->comments()->create([
                'user_id' => $validated['user_id'],
                'comment' => $validated['comment'],
            ]);

            // Cargar la relación de usuario para el comentario
            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al agregar comentario a orden de compra: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding comment to purchase order: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
