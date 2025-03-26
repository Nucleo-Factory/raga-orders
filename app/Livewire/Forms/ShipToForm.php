<?php

namespace App\Livewire\Forms;

use App\Models\ShipTo;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShipToForm extends Component
{
    public $shipTo;
    public $ship_to_id;
    public $name;
    public $email;
    public $contact_person;
    public $ship_to_direccion;
    public $ship_to_codigo_postal;
    public $ship_to_pais;
    public $ship_to_estado;
    public $ship_to_telefono;
    public $status;
    public $notes;
    public $isEdit = false;

    public $title;
    public $subtitle;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'contact_person' => 'nullable|string|max:255',
        'ship_to_direccion' => 'nullable|string|max:255',
        'ship_to_codigo_postal' => 'nullable|string|max:20',
        'ship_to_pais' => 'nullable|string|max:100',
        'ship_to_estado' => 'nullable|string|max:100',
        'ship_to_telefono' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ];

    public function mount($shipTo = null)
    {
        $this->isEdit = $shipTo !== null;

        if ($this->isEdit) {
            $this->shipTo = $shipTo;
            $this->ship_to_id = $shipTo->id;
            $this->name = $shipTo->name;
            $this->email = $shipTo->email;
            $this->contact_person = $shipTo->contact_person;
            $this->ship_to_direccion = $shipTo->ship_to_direccion;
            $this->ship_to_codigo_postal = $shipTo->ship_to_codigo_postal;
            $this->ship_to_pais = $shipTo->ship_to_pais;
            $this->ship_to_estado = $shipTo->ship_to_estado;
            $this->ship_to_telefono = $shipTo->ship_to_telefono;
            $this->status = $shipTo->status;
            $this->notes = $shipTo->notes;
        } else {
            $this->status = 'active';
        }
    }

    public function saveShipTo()
    {
        $this->validate();

        $shipToData = [
            'name' => $this->name,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'ship_to_direccion' => $this->ship_to_direccion,
            'ship_to_codigo_postal' => $this->ship_to_codigo_postal,
            'ship_to_pais' => $this->ship_to_pais,
            'ship_to_estado' => $this->ship_to_estado,
            'ship_to_telefono' => $this->ship_to_telefono,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        if ($this->isEdit) {
            $this->shipTo->update($shipToData);
            session()->flash('message', 'Dirección de envío actualizada correctamente.');
        } else {
            $shipToData['company_id'] = Auth::user()->company_id;
            ShipTo::create($shipToData);
            session()->flash('message', 'Dirección de envío creada correctamente.');
        }

        return redirect()->route('ship-to.index');
    }

    public function render()
    {
        return view('livewire.forms.ship-to-form');
    }
}
