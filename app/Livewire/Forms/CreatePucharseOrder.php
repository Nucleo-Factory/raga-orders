<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\ShipTo;
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

    // Watchers
    protected $listeners = [
        'vendorSelected' => 'onVendorSelected',
        'shipToSelected' => 'onShipToSelected'
    ];

    public function mount()
    {
        // Inicializar el array de productos vacío
        $this->orderProducts = [];

        // Generar un número de orden único
        $this->generateUniqueOrderNumber();
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

    public function createPurchaseOrder() {
        // Validar los datos
        $validated = $this->validate([
            'order_number' => 'required|string|max:255|unique:purchase_orders,order_number',
        ]);

        // Obtener el tablero Kanban predeterminado para la compañía del usuario
        $companyId = auth()->user()->company_id ?? 1;
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

        $purchaseOrder = \App\Models\PurchaseOrder::create([
            'company_id' => $companyId,
            'order_number' => $this->order_number,
            'status' => 'draft',
            'kanban_status_id' => $recepcionStatus ? $recepcionStatus->id : null,
            'notes' => $this->notes,
            'total_amount' => $this->total, // Asegurar que total_amount se llene

            // Vendor information
            'vendor_id' => $this->vendor_id,
            'vendor_direccion' => $this->vendor_direccion,
            'vendor_codigo_postal' => null, // Agregar campo faltante
            'vendor_pais' => $this->vendor_pais,
            'vendor_estado' => null, // Agregar campo faltante
            'vendor_telefono' => $this->vendor_telefono,

            // Ship to information
            'ship_to_id' => $this->ship_to_id, // Añadir ship_to_id para relacionar con ShipTo
            'ship_to_nombre' => $this->ship_to_nombre,
            'ship_to_direccion' => $this->ship_to_direccion,
            'ship_to_codigo_postal' => null, // Agregar campo faltante
            'ship_to_pais' => $this->ship_to_pais,
            'ship_to_estado' => null, // Agregar campo faltante
            'ship_to_telefono' => $this->ship_to_telefono,

            // Bill to information
            'bill_to_nombre' => $this->bill_to_nombre, // Este campo no existe en la migración
            'bill_to_direccion' => $this->bill_to_direccion,
            'bill_to_codigo_postal' => null, // Agregar campo faltante
            'bill_to_pais' => $this->bill_to_pais,
            'bill_to_estado' => null, // Agregar campo faltante
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
            'length' => $this->largo, // Corregir nombre del campo
            'width' => $this->ancho, // Corregir nombre del campo
            'height' => $this->alto, // Corregir nombre del campo
            'volume' => $this->volumen, // Corregir nombre del campo
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
            'ground_transport_cost_1' => null, // Agregar campos faltantes
            'ground_transport_cost_2' => null,
            'estimated_pallet_cost' => null,
            'other_costs' => null,
            'other_expenses' => null,

            // Comentarios
            'comments' => null,
        ]);

        // Guardar los productos asociados a la orden de compra
        foreach ($this->orderProducts as $product) {
            $purchaseOrder->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['price_per_unit']
            ]);
        }

        // Redireccionar o mostrar mensaje de éxito
        session()->flash('message', 'Orden de compra creada con éxito.');
        return redirect()->route('dashboard');
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
