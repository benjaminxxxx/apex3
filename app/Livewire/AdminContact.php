<?php

namespace App\Livewire;

use Livewire\Component;
use App\Mail\ContactFormSubmission;
use Illuminate\Support\Facades\Mail;

class AdminContact extends Component
{
    public $name;
    public $email;
    public $message;
    public $terms;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
        'terms' => 'accepted',
    ];

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'email.required' => 'El campo correo electrónico es obligatorio.',
        'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
        'message.required' => 'El campo mensaje es obligatorio.',
        'terms.accepted' => 'Debe aceptar los términos y condiciones.',
    ];

    public function render()
    {
        return view('livewire.admin-contact');
    }
    public function submitForm()
    {
        $validatedData = $this->validate();
        $siteEmail = "benjamin_unitek@hotmail.com";

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
