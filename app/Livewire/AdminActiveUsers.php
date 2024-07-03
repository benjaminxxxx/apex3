<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;

class AdminActiveUsers extends Component
{
    public $userData;

    public function mount()
    {
        // Obtener datos de usuario aquí
        $user = auth()->user();
        $this->userData = [
            'name' => Auth::user()->name,
            'avatar' => Auth::user()->profile_photo_url,
            'id' => Auth::id(),
        ];
    }
    public function render()
    {
        $userDataJson = json_encode($this->userData);
        $this->dispatch('userDataUpdated', $userDataJson);

        return view('livewire.admin-active-users');
    }
}
