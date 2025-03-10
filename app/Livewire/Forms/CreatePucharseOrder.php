<?php

namespace App\Livewire\Forms;

use Livewire\Component;

class CreatePucharseOrder extends Component
{
    public $modalidadArray = ["op1" => "Modalidad 1", "op2" => "Modalidad 2"];
    public $hubArray = ["1" => "Hub 1", "2" => "Hub 2"];
    public $paisArray = ["mx" => "México", "us" => "Estados Unidos"];
    public $estadoArray = ["cdmx" => "CDMX", "ny" => "New York"];
    public $tiposIncotermArray = ["value1" => "Tipo 1", "value2" => "Tipo 2", "value3" => "Tipo 3"];

    public function createPurchaseOrder() {
        dd("createPurchaseOrder");
    }

    public function render() {
        return view('livewire.forms.create-pucharse-order');
    }
}
