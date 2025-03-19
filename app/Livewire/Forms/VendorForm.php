<?php

namespace App\Livewire\Forms;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VendorForm extends Component
{
    public $vendor;
    public $vendor_id;
    public $name;
    public $email;
    public $contact_person;
    public $vendor_direccion;
    public $vendor_codigo_postal;
    public $vendor_pais;
    public $vendor_estado;
    public $vendor_telefono;
    public $status;
    public $notes;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'contact_person' => 'nullable|string|max:255',
        'vendor_direccion' => 'nullable|string|max:255',
        'vendor_codigo_postal' => 'nullable|string|max:20',
        'vendor_pais' => 'nullable|string|max:100',
        'vendor_estado' => 'nullable|string|max:100',
        'vendor_telefono' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ];

    public function mount($vendor = null)
    {
        $this->isEdit = $vendor !== null;

        if ($this->isEdit) {
            $this->vendor = $vendor;
            $this->vendor_id = $vendor->id;
            $this->name = $vendor->name;
            $this->email = $vendor->email;
            $this->contact_person = $vendor->contact_person;
            $this->vendor_direccion = $vendor->vendor_direccion;
            $this->vendor_codigo_postal = $vendor->vendor_codigo_postal;
            $this->vendor_pais = $vendor->vendor_pais;
            $this->vendor_estado = $vendor->vendor_estado;
            $this->vendor_telefono = $vendor->vendor_telefono;
            $this->status = $vendor->status;
            $this->notes = $vendor->notes;
        } else {
            $this->status = 'active';
        }
    }

    public function saveVendor()
    {
        $this->validate();

        $vendorData = [
            'name' => $this->name,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'vendor_direccion' => $this->vendor_direccion,
            'vendor_codigo_postal' => $this->vendor_codigo_postal,
            'vendor_pais' => $this->vendor_pais,
            'vendor_estado' => $this->vendor_estado,
            'vendor_telefono' => $this->vendor_telefono,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        if ($this->isEdit) {
            $this->vendor->update($vendorData);
            session()->flash('message', 'Proveedor actualizado correctamente.');
        } else {
            $vendorData['company_id'] = Auth::user()->company_id;
            Vendor::create($vendorData);
            session()->flash('message', 'Proveedor creado correctamente.');
        }

        return redirect()->route('vendors.index');
    }

    public function render()
    {
        return view('livewire.forms.vendor-form');
    }
}
