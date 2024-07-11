<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Database\QueryException;

class AdminContact extends Component
{
    public $name;
    public $email;
    public $number;
    public $number1;
    public $address;
    public $contact;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:contacts,email',
        'number' => 'required|string|max:20',
        'number1' => 'nullable|string|max:20',
        'address' => 'required|string|max:500',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'address.required' => 'El mensaje es obligatorio.',
        'number.required' => 'El número es obligatorio.',
    ];

    public function render()
    {
        $this->contact = Contact::first();
        if ($this->contact) {
            $this->name = $this->contact->name;
            $this->email = $this->contact->email;
            $this->number = $this->contact->number;
            $this->number1 = $this->contact->number1;
            $this->address = $this->contact->address;
        }
        return view('livewire.admin-contact');
    }
    public function submitForm()
    {
        $this->validate();

        try {
            $contact = Contact::first();
            if ($contact) {
                // Update existing record
                $contact->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'number' => $this->number,
                    'number1' => $this->number1,
                    'address' => $this->address,
                ]);
                session()->flash('message', 'Contact successfully updated.');
            } else {
                // Create new record
                Contact::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'number' => $this->number,
                    'number1' => $this->number1,
                    'address' => $this->address,
                ]);
                session()->flash('message', 'Contact successfully saved.');
            }
            $this->reset();
        } catch (QueryException $e) {
            session()->flash('error', 'There was an error saving the contact: ' . $e->getMessage());
        }

    }
}
