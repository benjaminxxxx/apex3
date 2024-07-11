<?php

namespace App\Livewire;

use Livewire\Component;
use App\Mail\ContactFormSubmission;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;

class ContactUs extends Component
{
    public $name;
    public $email;
    public $message;
    public $terms;
    public $contact;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
        'terms' => 'accepted',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'message.required' => 'El mensaje es obligatorio.',
        'terms.accepted' => 'Debe aceptar los términos y condiciones.',
    ];
    public function mount(){
        $this->contact = Contact::first();
    }
    public function render()
    {
        return view('livewire.contact-us');
    }
    public function submitForm()
    {
        $validatedData = $this->validate();
        $siteEmail = env('MAIL_USERNAME');
        
        if ($this->contact) {
            $siteEmail = $this->contact->email;
        }
        try {

            Mail::to($siteEmail)->send(
                new ContactFormSubmission($validatedData)
            );
            session()->flash('message', 'Tu mensaje ha sido enviado exitosamente.');
        } catch (\Exception $ex) {
            session()->flash('message', 'Error.' . $ex->message);
        }
        $this->reset();

    }
}
