<?php

namespace App\Livewire\Forms;

use Livewire\Component;

class CreateProducts extends Component {
    // Product fields
    // Item ID is autogenerated in the database
    public $material_id;
    public $description;
    public $legacy_material;
    public $contract;
    public $order_quantity;
    public $qty_unit;
    public $price_per_unit;
    public $price_per_uon;
    public $net_value;
    public $vat_rate;
    public $vat_value;
    public $delivery_date;

    // Arrays for select options
    public $qtyUnitOptions = ["kg" => "Kilogramos", "pcs" => "Piezas", "lt" => "Litros", "m" => "Metros"];
    public $vatRateOptions = ["0" => "0%", "16" => "16%", "8" => "8%"];

    public function createProduct()
    {
        // Validate form
        $this->validate([
            'material_id' => 'required|string|max:50',
            'description' => 'required|string',
            'legacy_material' => 'nullable|string|max:100',
            'contract' => 'nullable|string|max:100',
            'order_quantity' => 'required|numeric|min:0',
            'qty_unit' => 'required',
            'price_per_unit' => 'required|numeric|min:0',
            'price_per_uon' => 'required|numeric|min:0',
            'net_value' => 'required|numeric|min:0',
            'vat_rate' => 'required',
            'vat_value' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',
        ]);

        // Process the form submission
        // You would save to database here

        // Reset form
        session()->flash('message', 'Producto creado exitosamente!');
        $this->reset();
    }

    // Calculate net value when price or quantity changes
    public function updatedOrderQuantity()
    {
        $this->calculateNetValue();
    }

    public function updatedPricePerUnit()
    {
        $this->calculateNetValue();
    }

    public function updatedVatRate()
    {
        $this->calculateVatValue();
    }

    private function calculateNetValue()
    {
        if (is_numeric($this->order_quantity) && is_numeric($this->price_per_unit)) {
            $this->net_value = $this->order_quantity * $this->price_per_unit;
            $this->calculateVatValue();
        }
    }

    private function calculateVatValue()
    {
        if (is_numeric($this->net_value) && is_numeric($this->vat_rate)) {
            $this->vat_value = $this->net_value * ($this->vat_rate / 100);
        }
    }

    public function render() {
        return view('livewire.forms.create-products');
    }
}
