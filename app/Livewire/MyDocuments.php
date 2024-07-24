<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;

class MyDocuments extends Component
{
    public $documents;
    public function mount(){
        $userId = auth()->user()->id;

        // Consultar documentos
        $this->documents = Document::where('user_to', $userId)
            ->orderBy('updated_at', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.my-documents');
    }
}
