<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use App\Models\EventRole;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Auth;

class EventMain extends Component
{
    use WithFileUploads;
    public $event_type;
    public $event_project;
    public $event_upload;
    public $openCreateNewEvent = false;
    public $description;
    public $visibility;
    public $events;
    public $title;
    public $slug;
    public $start_date;
    public $end_date;
    public $organizer;
    public $phone;
    public $email;
    public $location;
    public $website;
    public $map;
    public $content;
    public $image_path;
    public $cover_image;
    public $categories;
    public $selected_categories = [];
    public $isEditing;
    public $eventId;

    public function mount()
    {
        $this->categories = Category::where('parent_id',5)->get();
    }

    protected function rules()
    {
        $rules = [
            'title' => 'required|string',
            'selected_categories' => 'required',
            'content' => 'required',
            'organizer'=> 'required',
            'slug' => ['required','unique:events,slug,' . $this->eventId],
            'start_date' => 'required|date',
                'email' => 'nullable|email',
        ];

        if ($this->cover_image) {
            $rules['cover_image'] = 'image|max:10240'; // 10MB Max
        }

        if ($this->end_date) {
            $rules['end_date'] = 'after:start_date';
        }

        return $rules;
    }

    protected $messages = [
        'cover_image.image' => 'El archivo debe ser una imagen.',
        'cover_image.max' => 'La imagen no debe ser mayor a 10MB.',
        'title.required' => 'El título es obligatorio.',
        'organizer.required' => 'El organizador es obligatorio.',
        'title.string' => 'El título debe ser una cadena de texto.',
        'selected_categories.required' => 'Debe seleccionar al menos una categoría.',
        'visibility.required' => 'Debe seleccionar al menos una visibilidad.',
        'content.required' => 'El contenido es obligatorio.',
        'slug.required' => 'El slug es obligatorio.',
        'slug.unique' => 'El slug ya está en uso.',
        'allow_comments' => 'boolean',
        'start_date.required' => 'La fecha de inicio es obligatoria para eventos.',
        'start_date.date' => 'La fecha de inicio debe ser una fecha válida.',
        'email.email' => 'El formato del correo electrónico es inválido.',
        'end_date.after' => 'La fecha de cierre debe ser posterior a la fecha de inicio.',
    ];

    public function updateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function render()
    {
        $user = auth()->user();
        $roleId = $user->role_id;
        $userId = $user->id;        

        if(Auth::user()->role_id==1 || Auth::user()->role_id==2){
            $this->events = Event::all();
        }else{
            // Obtener IDs de eventos basados en roles
            $eventIdsByRole = EventRole::where('role_id', $roleId)
            ->pluck('event_id');

            // Obtener eventos creados por el usuario
            $eventIdsByCreator = Event::where('created_by', $userId)
            ->pluck('id');

            // Combinar ambos conjuntos de eventos
            $eventIds = $eventIdsByRole->merge($eventIdsByCreator)->unique();

            // Obtener eventos basados en los IDs combinados
            $this->events = Event::whereIn('id', $eventIds)
            ->where('type', $this->event_type)
            ->get();
        }

        return view('livewire.event-main');
    }
    public function store()
    {
        $this->validate();

        try {

            $coverImagePath = null;
            if ($this->cover_image)
                $coverImagePath = $this->storeCoverImage($this->cover_image);
            
            $eventData = [
                'title' => $this->title,
                'content' => $this->content,
                'slug' => $this->slug,
                'cover_image' => $coverImagePath,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'organizer' => $this->organizer,
                'phone' => $this->phone,
                'email' => $this->email,
                'location' => $this->location,
                'website' => $this->website,
                'map' => $this->map,
                'type' => $this->event_type,
            ];
            
            if ($this->isEditing) {
                
                $event = Event::findOrFail($this->eventId);
                if ($coverImagePath!=null && $coverImagePath != $event->cover_image) {
                   
                    Storage::delete('public/' . $event->cover_image);
                }
                if ($coverImagePath==null && $this->image_path==null && $event->cover_image!=null) {
                   
                    Storage::delete('public/' . $event->cover_image);
                }
                if($coverImagePath==null){
                    $eventData['cover_image'] = $this->image_path;
                }

                $event->update($eventData);    
                $event->categories()->sync($this->selected_categories);

                $roles=[];

                if (empty($this->visibility)) {
                    $roles = [3, 4]; // Administradores, Gestores, Socios
                } else {
                    $roles = [$this->visibility];
                }
                EventRole::where('event_id',$event->id)->delete();
                if($roles){
                    foreach ($roles as $role) {
                        EventRole::create([
                            'event_id' => $event->id,
                            'role_id' => $role,
                        ]);
                    }
                }

                session()->flash('message', 'Evento actualizado con éxito.');
            } else {

                $eventData['created_by'] = Auth::id();
                $eventData['code'] = Str::random(15);
                $event = Event::create($eventData);
                $event->categories()->sync($this->selected_categories);
                
                if (empty($this->visibility)) {
                    $roles = [3, 4]; // Administradores, Gestores, Socios
                } else {
                    $roles = [$this->visibility];
                }
                EventRole::where('event_id',$event->id)->delete();
                if($roles){
                    foreach ($roles as $role) {
                        EventRole::create([
                            'event_id' => $event->id,
                            'role_id' => $role,
                        ]);
                    }
                }
                session()->flash('message', 'Evento publicado con éxito.');
            }
            $this->resetFields();
            $this->openCreateNewEvent = false;

        } catch (QueryException $e) {
            session()->flash('error', 'Ocurrió un error al crear el evento. Por favor, inténtelo de nuevo.' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
    }

    public function edit($eventCode){
        $user = auth()->user();
        $event = Event::where('code', $eventCode)->first();
        
        if (!$event) {
            session()->flash('error', 'El evento no existe.');
            return;
        }
        
        if ($event->created_by !== $user->id) {
            session()->flash('error', 'No tienes permiso para eliminar este evento.');
            return;
        }
        
        $this->isEditing = true;
        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->content = $event->content;
        $this->slug = $event->slug;
        $this->image_path = $event->cover_image;
        $this->start_date = $event->start_date;
        $this->end_date = $event->end_date;
        $this->organizer = $event->organizer;
        $this->phone = $event->phone;
        $this->email = $event->email;
        $this->location = $event->location;
        $this->website = $event->website;
        $this->map = $event->map;
        $this->openCreateNewEvent = true;
        
        $this->selected_categories = $event->categories->pluck('id')->toArray(); 
        $roles = EventRole::where('event_id', $event->id)->pluck('role_id')->toArray();
    
        if (count($roles) > 1) {
            $this->visibility = null;
        } else {
            $this->visibility = $roles[0];
        }

        $this->dispatch('tinymce-update', $this->content);
       
    }
  
    public function deleteImage()
    {
        $this->cover_image = null;
        $this->image_path = null;
    }
    protected function storeCoverImage($image)
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $directory = "events/{$currentYear}/{$currentMonth}";

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
    public function delete($eventCode)
    {
        $user = auth()->user();
        $event = Event::where('code', $eventCode)->first();
        
        if (!$event) {
            session()->flash('error', 'El evento no existe.');
            return;
        }
        
        if ($event->created_by !== $user->id) {
            session()->flash('error', 'No tienes permiso para eliminar este evento.');
            return;
        }
        
        $event->delete();
        session()->flash('message', 'Evento eliminado exitosamente.');
    }
    public function resetFields()
    {
        $this->evenId = null;
        $this->title = '';
        $this->content = '';
        $this->slug = '';
        $this->cover_image = '';
        $this->start_date = null;
        $this->end_date = null;
        $this->organizer = '';
        $this->phone = '';
        $this->email = '';
        $this->location = '';
        $this->website = '';
        $this->map = '';
        $this->visibility = null;
        $this->selected_categories = null;
        $this->dispatch('tinymce-update', $this->content);
    }
}
