<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use App\Models\DocumentRole;
use App\Models\Document;
use App\Models\User;
use Auth;

class DocumentMain extends Component
{
    use WithFileUploads;
    public $document_type;
    public $document_project;
    public $document_group;
    public $document_upload;
    public $openCreateNewDocument = false;
    public $description;
    public $visibility;
    public $documents;
    public $user_to_search;
    public $users;
    public $user_to;
    public $user_to_name;

    public function mount()
    {
        
    }
    public function checkVisibility(){
        $this->user_to = null;
        $this->user_to_name = null;
        $this->user_to_search = '';
        $this->users = null;
    }
    public function search()
    {
        if ($this->user_to_search) {
            if (Auth::user()->role_id==1 || Auth::user()->role_id==2  ) {
                $this->users = User::where(function ($query) {
                    $query->where('name', 'like', '%' . $this->user_to_search . '%')
                        ->orWhere('email', 'like', '%' . $this->user_to_search . '%');
                })
                    ->where('status', 1)
                    ->get();
            }else
            {
                $this->users = User::where(function ($query) {
                    $query->where('name', 'like', '%' . $this->user_to_search . '%')
                        ->orWhere('email', 'like', '%' . $this->user_to_search . '%');
                })
                    ->where('status', 1)
                    ->where('role_id', 4)
                    ->where('created_by', auth()->id())
                    ->get();
            }
            
        } else {
            $this->users = null;
        }
    }
    public function addMember($userId,$name)
    {
        $this->user_to = $userId;
        $this->user_to_name = $name;
        $this->user_to_search = '';
        $this->users = null;
    }
    public function render()
    {
        $roleId = auth()->user()->role_id;
        $userId = auth()->user()->id;

        // Consultar documentos
        $this->documents = Document::leftJoin('document_roles', 'documents.id', '=', 'document_roles.document_id')
            ->where(function ($query) use ($roleId, $userId) {
                $query->where('documents.created_by', $userId) // Documentos creados por el usuario autenticado
                    ->orWhere(function ($query) use ($roleId) {
                        $query->where('document_roles.role_id', $roleId) // Documentos accesibles para el rol del usuario
                            ->whereNotNull('document_roles.role_id'); // Asegura que solo se seleccionen los documentos con rol
                    });
            })
            ->where('documents.type', $this->document_type)
            ->distinct() // Evita documentos duplicados
            ->orderBy('documents.updated_at', 'desc')
            ->select('documents.*') // Selecciona todas las columnas de documents
            ->get();

        return view('livewire.document-main');
    }
    public function store()
    {
        $validationData = [
            'document_upload' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Máx 10MB
        ];

        if($this->visibility==5){
            $validationData['user_to'] = 'required';
        }
        $this->validate($validationData);

        try {
            $storedPath = $this->storeFile($this->document_upload);
            $documentData = [
                'code' => Str::random(15),
                'title' => null,
                'description' => $this->description,
                'file_path' => $storedPath,
                'created_by' => auth()->id(),
                'status' => true,
                'type' => $this->document_type,
            ];

            if($this->document_type==2){
                $documentData['project_id'] = $this->document_project;
            }
            if($this->document_type==3){
                $documentData['group_id'] = $this->document_group;
            }
            if($this->visibility==5){
                $documentData['user_to'] = $this->user_to;
            }

            $document = Document::create($documentData);

            $roles=[];

            if (empty($this->visibility)) {
                $roles = [1, 2, 3, 4]; // Administradores, Gestores, Socios
            } else {
                if($this->visibility!=5){
                    $roles = [$this->visibility];
                }
            }
            if($roles){
                foreach ($roles as $role) {
                    DocumentRole::create([
                        'document_id' => $document->id,
                        'role_id' => $role,
                    ]);
                }
            }
            

            session()->flash('message', 'Documento creado exitosamente.');
            $this->reset(['description', 'document_upload', 'visibility']);
            $this->openCreateNewDocument = false;

        } catch (QueryException $e) {
            session()->flash('error', 'Ocurrió un error al crear el documento. Por favor, inténtelo de nuevo.' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
    }
    public function deleteFile()
    {
        $this->document_upload = null;
    }
    protected function storeFile($image)
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $directory = "uploads/{$currentYear}/{$currentMonth}";

        $randomName = Str::random(20);
        $extension = $image->getClientOriginalExtension();

        $fullFilename = "{$randomName}.{$extension}";
        $counter = 1;

        while (Storage::exists("public/{$directory}/{$fullFilename}")) {
            $fullFilename = "{$randomName}-{$counter}.{$extension}";
            $counter++;
        }

        $storedPath = $image->storeAs("public/{$directory}", $fullFilename);

        return str_replace('public/', '', $storedPath);
    }
    public function delete($documentCode)
    {
        $user = auth()->user();

        // Buscar el documento con el código proporcionado
        $document = Document::where('code', $documentCode)->first();

        // Verificar si el documento existe
        if (!$document) {
            session()->flash('error', 'El documento no existe.');
        }

        // Verificar si el documento fue creado por el usuario autenticado
        if ($document->created_by !== $user->id) {
            session()->flash('error', 'No tienes permiso para eliminar este documento.');
        }

        // Eliminar el documento
        $document->delete();

        session()->flash('message', 'Documento eliminado exitosamente.');
    }
}
