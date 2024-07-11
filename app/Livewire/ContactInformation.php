<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;

class ContactInformation extends Component
{
    public $contact;

    public function mount(){
        $this->contact = Contact::first();
    }
    public function render()
    {
        return view('livewire.contact-information');
    }
}
