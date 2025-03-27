<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\ShipTo;
use App\Models\Hub;
use App\Models\BillTo;
use Livewire\Component;

class CreatePucharseOrder extends Component
{
    // Arrays para selects
    public $modalidadArray = ["op1" => "Modalidad 1", "op2" => "Modalidad 2"];
    public $hubArray = ["1" => "Hub 1", "2" => "Hub 2"];
    public $paisArray = ["cr" => "Costa Rica", "us" => "Estados Unidos"];
    public $estadoArray = ["cr" => "San José", "us" => "Miami"];
    public $tiposIncotermArray = [
        "CIF" => "CIF",
        "CIP" => "CIP",
        "CFR" => "CFR",
        "CPT" => "CPT",
        "DAT" => "DAT",
        "DAP" => "DAP",
        "DDP" => "DDP",
        "DEQ" => "DEQ",
        "DES" => "DES",
        "EXD" => "EXD",
        "EXQ" => "EXQ",
        "EXW" => "EXW",
        "FCA" => "FCA",
        "FOB" => "FOB",
    ];
    public $currencyArray = ["CRC" => "Colones", "USD" => "Dólar Estadounidense", "EUR" => "Euro"];
    public $paymentTermsArray = ["30" => "30 días", "60" => "60 días", "90" => "90 días"];
    public $vendorArray = [];
    public $shipToArray = [];

    // Datos generales
    public $order_number;
    public $status;
    public $notes;
    public $company_id;

    // Vendor information
    public $vendor_id;
    public $vendor_direccion;
    public $vendor_pais;
    public $vendor_telefono;

    // Ship to information
    public $ship_to_id;
    public $ship_to_nombre;
    public $ship_to_direccion;
    public $ship_to_pais;
    public $ship_to_telefono;

    // Bill to information
    public $bill_to_nombre;
    public $bill_to_direccion;
    public $bill_to_pais;
    public $bill_to_telefono;

    // Order details
    public $order_date;
    public $currency;
    public $incoterms;
    public $payment_terms;
    public $order_place;
    public $email_agent;

    // Totals
    public $net_total = 0;
    public $additional_cost = 0;
    public $total = 0;

    // Dimensiones
    public $largo;
    public $ancho;
    public $alto;
    public $volumen;
    public $peso_kg;
    public $peso_lb;

    // Fechas
    public $date_required_in_destination;
    public $date_planned_pickup;
    public $date_actual_pickup;
    public $date_estimated_hub_arrival;
    public $date_actual_hub_arrival;
    public $date_etd;
    public $date_atd;
    public $date_eta;
    public $date_ata;
    public $date_consolidation;
    public $release_date;

    // Costos
    public $insurance_cost = 0;

    // Productos
    public $orderProducts = [];
    public $searchTerm = '';
    public $searchResults = [];
    public $selectedProduct = null;
    public $quantity = 1;

    public $id;
    public $purchaseOrder;

    public $planned_hub_id;
    public $actual_hub_id;
    public $hubsArray = [];
    public $billToArray = [];

    // New fields
    public $material_type;
    public $ensurence_type;
    public $mode;
    public $tracking_id;
    public $pallet_quantity;
    public $pallet_quantity_real;
    public $bill_of_lading;
    public $ground_transport_cost_1 = 0;
    public $ground_transport_cost_2 = 0;
    public $cost_nationalization = 0;
    public $cost_ofr_estimated = 0;
    public $cost_ofr_real = 0;
    public $estimated_pallet_cost = 0;
    public $real_cost_estimated_po = 0;
    public $real_cost_real_po = 0;
    public $other_costs = 0;
    public $other_expenses = 0;
    public $variable_calculare_weight = 0;
    public $savings_ofr_fcl = 0;
    public $saving_pickup = 0;
    public $saving_executed = 0;
    public $saving_not_executed = 0;
    public $comments;

    // Watchers
    protected $listeners = [
        'vendorSelected' => 'onVendorSelected',
        'shipToSelected' => 'onShipToSelected'
    ];

    public function mount($id = null)
    {
        $this->id = $id;
        $this->loadHubs();
        $this->loadBillTo();

        if ($this->id) {
            $this->purchaseOrder = \App\Models\PurchaseOrder::with('products')->find($this->id);

            if ($this->purchaseOrder) {
                // Cargar datos generales
                $this->order_number = $this->purchaseOrder->order_number;
                $this->status = $this->purchaseOrder->status;
                $this->notes = $this->purchaseOrder->notes;
                $this->company_id = $this->purchaseOrder->company_id;

                // Vendor information
                $this->vendor_id = $this->purchaseOrder->vendor_id;
                $this->vendor_direccion = $this->purchaseOrder->vendor_direccion;
                $this->vendor_pais = $this->purchaseOrder->vendor_pais;
                $this->vendor_telefono = $this->purchaseOrder->vendor_telefono;

                // Ship to information
                $this->ship_to_id = $this->purchaseOrder->ship_to_id;
                $this->ship_to_nombre = $this->purchaseOrder->ship_to_nombre;
                $this->ship_to_direccion = $this->purchaseOrder->ship_to_direccion;
                $this->ship_to_pais = $this->purchaseOrder->ship_to_pais;
                $this->ship_to_telefono = $this->purchaseOrder->ship_to_telefono;

                // Bill to information
                $this->bill_to_nombre = $this->purchaseOrder->bill_to_nombre;
                $this->bill_to_direccion = $this->purchaseOrder->bill_to_direccion;
                $this->bill_to_pais = $this->purchaseOrder->bill_to_pais;
                $this->bill_to_telefono = $this->purchaseOrder->bill_to_telefono;

                // Order details
                $this->order_date = $this->purchaseOrder->order_date ? $this->purchaseOrder->order_date->format('Y-m-d') : null;
                $this->currency = $this->purchaseOrder->currency;
                $this->incoterms = $this->purchaseOrder->incoterms;
                $this->payment_terms = $this->purchaseOrder->payment_terms;
                $this->order_place = $this->purchaseOrder->order_place;
                $this->email_agent = $this->purchaseOrder->email_agent;

                // Totals
                $this->net_total = $this->purchaseOrder->net_total;
                $this->additional_cost = $this->purchaseOrder->additional_cost;
                $this->total = $this->purchaseOrder->total;

                // Dimensiones
                $this->largo = $this->purchaseOrder->length;
                $this->ancho = $this->purchaseOrder->width;
                $this->alto = $this->purchaseOrder->height;
                $this->volumen = $this->purchaseOrder->volume;
                $this->peso_kg = $this->purchaseOrder->weight_kg;
                $this->peso_lb = $this->purchaseOrder->weight_lb;

                // Fechas
                $this->date_required_in_destination = $this->purchaseOrder->date_required_in_destination ? $this->purchaseOrder->date_required_in_destination->format('Y-m-d') : null;
                $this->date_planned_pickup = $this->purchaseOrder->date_planned_pickup ? $this->purchaseOrder->date_planned_pickup->format('Y-m-d') : null;
                $this->date_actual_pickup = $this->purchaseOrder->date_actual_pickup ? $this->purchaseOrder->date_actual_pickup->format('Y-m-d') : null;
                $this->date_estimated_hub_arrival = $this->purchaseOrder->date_estimated_hub_arrival ? $this->purchaseOrder->date_estimated_hub_arrival->format('Y-m-d') : null;
                $this->date_actual_hub_arrival = $this->purchaseOrder->date_actual_hub_arrival ? $this->purchaseOrder->date_actual_hub_arrival->format('Y-m-d') : null;
                $this->date_etd = $this->purchaseOrder->date_etd ? $this->purchaseOrder->date_etd->format('Y-m-d') : null;
                $this->date_atd = $this->purchaseOrder->date_atd ? $this->purchaseOrder->date_atd->format('Y-m-d') : null;
                $this->date_eta = $this->purchaseOrder->date_eta ? $this->purchaseOrder->date_eta->format('Y-m-d') : null;
                $this->date_ata = $this->purchaseOrder->date_ata ? $this->purchaseOrder->date_ata->format('Y-m-d') : null;
                $this->date_consolidation = $this->purchaseOrder->date_consolidation ? $this->purchaseOrder->date_consolidation->format('Y-m-d') : null;
                $this->release_date = $this->purchaseOrder->release_date ? $this->purchaseOrder->release_date->format('Y-m-d') : null;

                $this->planned_hub_id = $order->planned_hub_id;
                $this->actual_hub_id = $order->actual_hub_id;

                // Costos
                $this->insurance_cost = $this->purchaseOrder->insurance_cost;

                // Cargar productos
                $this->orderProducts = [];
                foreach ($this->purchaseOrder->products as $product) {
                    $this->orderProducts[] = [
                        'id' => $product->id,
                        'material_id' => $product->material_id,
                        'description' => $product->description,
                        'price_per_unit' => $product->pivot->unit_price,
                        'quantity' => $product->pivot->quantity,
                        'subtotal' => $product->pivot->unit_price * $product->pivot->quantity
                    ];
                }
            }
        } else {
            // Inicializar el array de productos vacío
            $this->orderProducts = [];

            // Generar un número de orden único
            //$this->generateUniqueOrderNumber();
        }
    }

    protected function loadHubs()
    {
        $hubs = Hub::orderBy('name')->get();
        $this->hubsArray = $hubs->pluck('name', 'id')->toArray();
    }

    public function loadBillTo()
    {
        $billTo = BillTo::orderBy('name')->get();
        $this->billToArray = $billTo->pluck('name', 'id')->toArray();
    }

    /**
     * Genera un número de orden único
     */
    public function generateUniqueOrderNumber()
    {
        // Formato: PO-YYYYMMDD-XXXX donde XXXX es un número secuencial
        $prefix = 'PO-' . date('Ymd') . '-';

        // Obtener el último número de orden con este prefijo
        $lastOrder = \App\Models\PurchaseOrder::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            // Extraer el número secuencial y aumentarlo en 1
            $lastNumber = substr($lastOrder->order_number, strlen($prefix));
            $newNumber = intval($lastNumber) + 1;
            $this->order_number = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            // Si no hay órdenes previas con este prefijo, empezar con 0001
            $this->order_number = $prefix . '0001';
        }
    }

    /**
     * Cuando se selecciona un vendor, rellenar los datos automáticamente
     */
    public function onVendorSelected()
    {
        if ($this->vendor_id) {
            $vendor = Vendor::find($this->vendor_id);
            if ($vendor) {
                $this->vendor_direccion = $vendor->vendor_direccion;
                $this->vendor_pais = $vendor->vendor_pais;
                $this->vendor_telefono = $vendor->vendor_telefono;
            }
        }
    }

    /**
     * Cuando se selecciona un ship to, rellenar los datos automáticamente
     */
    public function onShipToSelected()
    {
        if ($this->ship_to_id) {
            $shipTo = ShipTo::find($this->ship_to_id);
            if ($shipTo) {
                $this->ship_to_nombre = $shipTo->name;
                $this->ship_to_direccion = $shipTo->ship_to_direccion;
                $this->ship_to_pais = $shipTo->ship_to_pais;
                $this->ship_to_telefono = $shipTo->ship_to_telefono;
            }
        }
    }

    public function updatedVendorId()
    {
        $this->onVendorSelected();
    }

    public function updatedShipToId()
    {
        $this->onShipToSelected();
    }

    public function searchProducts()
    {
        if (strlen($this->searchTerm) >= 2) {
            $this->searchResults = Product::where('material_id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
                ->take(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->searchResults = [];
    }

    public function addProduct()
    {
        if ($this->selectedProduct) {
            // Verificar si el producto ya está en la lista
            $existingIndex = null;
            foreach ($this->orderProducts as $index => $product) {
                if ($product['id'] == $this->selectedProduct->id) {
                    $existingIndex = $index;
                    break;
                }
            }

            if ($existingIndex !== null) {
                // Si el producto ya existe, actualizar la cantidad
                $this->orderProducts[$existingIndex]['quantity'] += $this->quantity;
                $this->orderProducts[$existingIndex]['subtotal'] =
                    $this->orderProducts[$existingIndex]['quantity'] * $this->orderProducts[$existingIndex]['price_per_unit'];
            } else {
                // Si es un nuevo producto, agregarlo al array
                $this->orderProducts[] = [
                    'id' => $this->selectedProduct->id,
                    'material_id' => $this->selectedProduct->material_id,
                    'description' => $this->selectedProduct->description,
                    'price_per_unit' => $this->selectedProduct->price_per_unit,
                    'quantity' => $this->quantity,
                    'subtotal' => $this->selectedProduct->price_per_unit * $this->quantity
                ];
            }

            // Limpiar la selección
            $this->selectedProduct = null;
            $this->searchTerm = '';
            $this->quantity = 1;

            // Recalcular totales
            $this->calculateTotals();
        }
    }

    public function removeProduct($index)
    {
        // Eliminar el producto del array
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts); // Reindexar el array

        // Recalcular totales
        $this->calculateTotals();
    }

    public function updateQuantity($index, $newQuantity)
    {
        // Actualizar la cantidad y el subtotal
        $this->orderProducts[$index]['quantity'] = $newQuantity;
        $this->orderProducts[$index]['subtotal'] =
            $this->orderProducts[$index]['quantity'] * $this->orderProducts[$index]['price_per_unit'];

        // Recalcular totales
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        // Calcular el total neto sumando los subtotales de todos los productos
        $this->net_total = 0;
        foreach ($this->orderProducts as $product) {
            $this->net_total += $product['subtotal'];
        }

        // Calcular el total final (neto + adicionales)
        $this->total = $this->net_total + $this->additional_cost + $this->insurance_cost;
    }

    public function updatedAdditionalCost()
    {
        $this->calculateTotals();
    }

    public function updatedInsuranceCost()
    {
        $this->calculateTotals();
    }

    /**
     * Prepara los campos de fecha para el procesamiento, convirtiendo strings vacíos a null
     */
    protected function prepareDateFields()
    {
        $dateFields = [
            'order_date',
            'date_required_in_destination',
            'date_planned_pickup',
            'date_actual_pickup',
            'date_estimated_hub_arrival',
            'date_actual_hub_arrival',
            'date_etd',
            'date_atd',
            'date_eta',
            'date_ata',
            'date_consolidation',
            'release_date'
        ];

        foreach ($dateFields as $field) {
            if (empty($this->$field)) {
                $this->$field = null;
            }
        }
    }

    public function createPurchaseOrder() {
        // Validar los datos
        $this->validate([
            'order_number' => 'required|string|max:255|unique:purchase_orders,order_number' . ($this->id ? ','.$this->id : ''),
            'order_date' => 'required|date',
        ], [
            'order_number.required' => 'El número de PO es requerido',
            'order_number.unique' => 'El número de PO ya existe',
            'order_date.required' => 'La fecha de PO es requerida',
        ]);

        $companyId = auth()->user()->company_id ?? 1;

        // Asegurar que las fechas sean null si están vacías
        $this->prepareDateFields();

        // Datos para crear o actualizar
        $poData = [
            'company_id' => $companyId,
            'order_number' => $this->order_number,
            'status' => $this->id ? $this->status : 'draft',
            'notes' => $this->notes,
            'total_amount' => $this->total,

            // Vendor information
            'vendor_id' => $this->vendor_id,
            'vendor_direccion' => $this->vendor_direccion,
            'vendor_codigo_postal' => null,
            'vendor_pais' => $this->vendor_pais,
            'vendor_estado' => null,
            'vendor_telefono' => $this->vendor_telefono,

            // Ship to information
            'ship_to_id' => $this->ship_to_id,
            'ship_to_nombre' => $this->ship_to_nombre,
            'ship_to_direccion' => $this->ship_to_direccion,
            'ship_to_codigo_postal' => null,
            'ship_to_pais' => $this->ship_to_pais,
            'ship_to_estado' => null,
            'ship_to_telefono' => $this->ship_to_telefono,

            // Bill to information
            'bill_to_nombre' => $this->bill_to_nombre,
            'bill_to_direccion' => $this->bill_to_direccion,
            'bill_to_codigo_postal' => null,
            'bill_to_pais' => $this->bill_to_pais,
            'bill_to_estado' => null,
            'bill_to_telefono' => $this->bill_to_telefono,

            // Order details
            'order_date' => $this->order_date,
            'currency' => $this->currency,
            'incoterms' => $this->incoterms,
            'payment_terms' => $this->payment_terms,
            'order_place' => $this->order_place,
            'email_agent' => $this->email_agent,

            // Totals
            'net_total' => $this->net_total,
            'additional_cost' => $this->additional_cost,
            'total' => $this->total,

            // Dimensiones
            'length' => $this->largo,
            'width' => $this->ancho,
            'height' => $this->alto,
            'volume' => $this->volumen,
            'weight_kg' => $this->peso_kg,
            'weight_lb' => $this->peso_lb,

            // Fechas
            'date_required_in_destination' => $this->date_required_in_destination,
            'date_planned_pickup' => $this->date_planned_pickup,
            'date_actual_pickup' => $this->date_actual_pickup,
            'date_estimated_hub_arrival' => $this->date_estimated_hub_arrival,
            'date_actual_hub_arrival' => $this->date_actual_hub_arrival,
            'date_etd' => $this->date_etd,
            'date_atd' => $this->date_atd,
            'date_eta' => $this->date_eta,
            'date_ata' => $this->date_ata,
            'date_consolidation' => $this->date_consolidation,
            'release_date' => $this->release_date,

            // Costos
            'insurance_cost' => $this->insurance_cost,
            'ground_transport_cost_1' => $this->ground_transport_cost_1,
            'ground_transport_cost_2' => $this->ground_transport_cost_2,
            'estimated_pallet_cost' => $this->estimated_pallet_cost,
            'cost_nationalization' => $this->cost_nationalization,
            'cost_ofr_estimated' => $this->cost_ofr_estimated,
            'cost_ofr_real' => $this->cost_ofr_real,
            'real_cost_estimated_po' => $this->real_cost_estimated_po,
            'real_cost_real_po' => $this->real_cost_real_po,
            'other_costs' => $this->other_costs,
            'other_expenses' => $this->other_expenses,
            'variable_calculare_weight' => $this->variable_calculare_weight,
            'savings_ofr_fcl' => $this->savings_ofr_fcl,
            'saving_pickup' => $this->saving_pickup,
            'saving_executed' => $this->saving_executed,
            'saving_not_executed' => $this->saving_not_executed,
            'comments' => $this->comments,
            'planned_hub_id' => $this->planned_hub_id,
            'actual_hub_id' => $this->actual_hub_id,
        ];

        // Si es una nueva orden, establecer el status en kanban
        if (!$this->id) {
            // Obtener el tablero Kanban predeterminado para la compañía del usuario
            $kanbanBoard = \App\Models\KanbanBoard::where('company_id', $companyId)
                ->where('type', 'po_stages')
                ->where('is_active', true)
                ->first();

            // Buscar específicamente el estado "Recepción" en el tablero Kanban
            $recepcionStatus = null;
            if ($kanbanBoard) {
                $recepcionStatus = $kanbanBoard->statuses()
                    ->where('name', 'Recepción')
                    ->first();

                // Si no se encuentra "Recepción", usar el estado por defecto como respaldo
                if (!$recepcionStatus) {
                    $recepcionStatus = $kanbanBoard->defaultStatus();
                }
            }

            $poData['kanban_status_id'] = $recepcionStatus ? $recepcionStatus->id : null;
        }

        if ($this->id) {
            // Actualizar orden de compra existente
            $purchaseOrder = \App\Models\PurchaseOrder::find($this->id);
            $purchaseOrder->update($poData);

            // Limpiar productos previos y agregar los nuevos
            $purchaseOrder->products()->detach();
        } else {
            // Crear nueva orden de compra
            $purchaseOrder = \App\Models\PurchaseOrder::create($poData);
        }

        // Guardar los productos asociados a la orden de compra
        foreach ($this->orderProducts as $product) {
            $purchaseOrder->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['price_per_unit']
            ]);
        }

        // Redireccionar o mostrar mensaje de éxito
        $successMessage = $this->id ? 'Orden de compra actualizada con éxito.' : 'Orden de compra creada con éxito.';
        session()->flash('message', $successMessage);
        return redirect()->route('purchase-orders.index');
    }

    public function render() {
        // Cargar los vendors y ship tos desde la base de datos
        $companyId = auth()->user()->company_id ?? 1;

        // Obtener los vendors y formatearlos para el selector
        $vendors = Vendor::where('company_id', $companyId)
                          ->where('status', 'active')
                          ->get();
        $this->vendorArray = $vendors->pluck('name', 'id')->toArray();

        // Obtener los ship tos y formatearlos para el selector
        $shipTos = ShipTo::where('company_id', $companyId)
                          ->where('status', 'active')
                          ->get();
        $this->shipToArray = $shipTos->pluck('name', 'id')->toArray();

        return view('livewire.forms.create-pucharse-order');
    }
}
