<?php

namespace App\Livewire\Forms;

use App\Models\Hub;
use Livewire\Component;

class HubForm extends Component {

    public $hub;
    public $title;
    public $subtitle;

    public $code;
    public $name;
    public $country;
    public $documentary_cut;
    public $zarpe;

    public $isEdit = false;

    public function mount($id = null) {
        if ($id) {
            $this->isEdit = true;
            $this->hub = Hub::find($id);
            $this->code = $this->hub->code;
            $this->name = $this->hub->name;
            $this->country = $this->hub->country;
            $this->documentary_cut = $this->hub->documentary_cut;
            $this->zarpe = $this->hub->zarpe;
        }
    }

    public function saveHub() {
        $this->validate([
            'name' => 'required',
            'code' => 'required',
            'country' => 'required',
            'documentary_cut' => 'required',
            'zarpe' => 'required',
        ], [
            'name.required' => 'El nombre es requerido',
            'code.required' => 'El código es requerido',
            'country.required' => 'El país es requerido',
            'documentary_cut.required' => 'El corte documental es requerido',
            'zarpe.required' => 'El zarpe es requerido',
        ]);

        if ($this->hub) {
            $this->hub->update($this->all());
        } else {
            $this->hub = Hub::create([
                'name' => $this->name,
                'code' => $this->code,
                'country' => $this->country,
                'documentary_cut' => $this->documentary_cut,
                'zarpe' => $this->zarpe,
            ]);
        }

        return redirect()->route('hub.index');
    }

    public function render() {
        return view('livewire.forms.hub-form');
    }
}
