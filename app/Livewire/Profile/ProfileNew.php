<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Livewire\Attributes\Watch;

class ProfileNew extends Component {
    use WithFileUploads;

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
    public $profileImage;

    public $editProfile;

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

    public function activeEditProfile() {
        $this->editProfile = true;
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

        $this->editProfile = false;
        $this->dispatch('open-modal', 'successModal');
    }

    public function cancelEditProfile() {
        $this->editProfile = false;
    }

    #[Watch('profileImage')]
    public function updatedProfileImage()
    {
        $this->validate([
            'profileImage' => 'image|max:1024',
        ]);

        // Eliminar los medios anteriores en la colección 'profile-photo'
        $this->user->clearMediaCollection('profile-photo');

        // Agregar la nueva imagen a la colección
        $media = $this->user->addMediaFromStream($this->profileImage->get())
            ->usingName($this->user->name . '-profile')
            ->usingFileName($this->profileImage->hashName())
            ->toMediaCollection('profile-photo');

        // Limpiar la variable temporal
        $this->profileImage = null;

        // Refrescar el usuario para obtener la nueva URL de la imagen
        $this->user->refresh();

        $this->dispatch('open-modal', 'successModal');
    }

    public function render() {
        return view('livewire.profile.profile-new');
    }
}
