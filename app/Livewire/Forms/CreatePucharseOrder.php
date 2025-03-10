<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Component;

class CreatePucharseOrder extends Component
{
    // Arrays para selects
    public $modalidadArray = ["op1" => "Modalidad 1", "op2" => "Modalidad 2"];
    public $hubArray = ["1" => "Hub 1", "2" => "Hub 2"];
    public $paisArray = ["mx" => "México", "us" => "Estados Unidos"];
    public $estadoArray = ["cdmx" => "CDMX", "ny" => "New York"];
    public $tiposIncotermArray = ["value1" => "Tipo 1", "value2" => "Tipo 2", "value3" => "Tipo 3"];
    public $currencyArray = ["MXN" => "Peso Mexicano", "USD" => "Dólar Estadounidense", "EUR" => "Euro"];
    public $paymentTermsArray = ["30" => "30 días", "60" => "60 días", "90" => "90 días"];

    // Datos generales
    public $order_number;
    public $status;
    public $notes;
    public $company_id;

    // Vendor information
    public $vendor_id;
    public $vendor_direccion;
    public $vendor_codigo_postal;
    public $vendor_pais;
    public $vendor_estado;
    public $vendor_telefono;

    // Ship to information
    public $ship_to_direccion;
    public $ship_to_codigo_postal;
    public $ship_to_pais;
    public $ship_to_estado;
    public $ship_to_telefono;

    // Bill to information
    public $bill_to_direccion;
    public $bill_to_codigo_postal;
    public $bill_to_pais;
    public $bill_to_estado;
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
    public $height_cm;
    public $width_cm;
    public $length_cm;
    public $volume_m3;

    // Fechas
    public $requested_delivery_date;
    public $estimated_pickup_date;
    public $actual_pickup_date;
    public $estimated_hub_arrival;
    public $actual_hub_arrival;
    public $etd_date; // Estimated Time of Departure
    public $atd_date; // Actual Time of Departure
    public $eta_date; // Estimated Time of Arrival
    public $ata_date; // Actual Time of Arrival

    // Costos
    public $insurance_cost = 0;

    // Productos
    public $orderProducts = [];
    public $searchTerm = '';
    public $searchResults = [];
    public $selectedProduct = null;
    public $quantity = 1;

    public function mount()
    {
        // Inicializar el array de productos vacío
        $this->orderProducts = [];
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
            'order_number' => 'required|string|max:255',
        ]);

        $purchaseOrder = \App\Models\PurchaseOrder::create([
            'company_id' => auth()->user()->company_id ?? 1,
            'order_number' => $this->order_number,
            'status' => 'draft',
            'notes' => $this->notes,

            // Vendor information
            'vendor_id' => $this->vendor_id,
            'vendor_direccion' => $this->vendor_direccion,
            'vendor_codigo_postal' => $this->vendor_codigo_postal,
            'vendor_pais' => $this->vendor_pais,
            'vendor_estado' => $this->vendor_estado,
            'vendor_telefono' => $this->vendor_telefono,

            // Ship to information
            'ship_to_direccion' => $this->ship_to_direccion,
            'ship_to_codigo_postal' => $this->ship_to_codigo_postal,
            'ship_to_pais' => $this->ship_to_pais,
            'ship_to_estado' => $this->ship_to_estado,
            'ship_to_telefono' => $this->ship_to_telefono,

            // Bill to information
            'bill_to_direccion' => $this->bill_to_direccion,
            'bill_to_codigo_postal' => $this->bill_to_codigo_postal,
            'bill_to_pais' => $this->bill_to_pais,
            'bill_to_estado' => $this->bill_to_estado,
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
            'height_cm' => $this->height_cm,
            'width_cm' => $this->width_cm,
            'length_cm' => $this->length_cm,
            'volume_m3' => $this->volume_m3,

            // Fechas
            'requested_delivery_date' => $this->requested_delivery_date,
            'estimated_pickup_date' => $this->estimated_pickup_date,
            'actual_pickup_date' => $this->actual_pickup_date,
            'estimated_hub_arrival' => $this->estimated_hub_arrival,
            'actual_hub_arrival' => $this->actual_hub_arrival,
            'etd_date' => $this->etd_date,
            'atd_date' => $this->atd_date,
            'eta_date' => $this->eta_date,
            'ata_date' => $this->ata_date,

            // Costos
            'insurance_cost' => $this->insurance_cost,
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
        return redirect()->route('purchase-orders.index');
    }

    public function render() {
        return view('livewire.forms.create-pucharse-order');
    }
}
