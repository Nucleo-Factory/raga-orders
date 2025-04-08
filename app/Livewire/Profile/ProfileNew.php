<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class ProfileNew extends Component {
    public $user;
    public $role;
    public $name;
    public $email;
    public $company;
    public $phone;
    public $country;
    public $city;
    public $zip;
    public $description;
    public $website;

    public function mount() {
        $this->user = auth()->user();
        $this->role = $this->user->roles->first()->name;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->company = $this->user->company->name;
        $this->phone = $this->user->phone;
        $this->country = $this->user->company->country;
        $this->city = $this->user->company->city;
        $this->zip = $this->user->company->zip;
        $this->description = $this->user->description;
        $this->website = $this->user->company->website;
    }

    public function updateProfile() {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'company' => 'required',
            'phone' => 'required',
            'country' => 'required',
        ]);


        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
        ]);

        $this->user->company->update([
            'name' => $this->company,
            'phone' => $this->phone,
            'country' => $this->country,
        ]);
    }

    public function render() {
        return view('livewire.profile.profile-new');
    }
}
