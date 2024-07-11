<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class AdminPost extends Component
{
    use WithFileUploads;
    public $postId;
    public $posts;
    public $categories;
    public $selected_categories = [];
    public $title;
    public $content;
    public $slug;
    public $cover_image;
    public $allow_comments;
    public $excerpt;
    public $type_post;
    public $isFormOpen;
    public $types;
    public $starts_at;
    public $ends_at;
    public $organizer;
    public $phone;
    public $email;
    public $location;
    public $website;
    public $map;
    public $image_path;
    public $isDeleting;
    public $postIdToDelete;
    public $isEditing;
    public function mount($type = null)
    {
       
        $this->type_post = $type;

        if($this->type_post !=null){
            $this->isFormOpen = true;
        }
        $this->categories = Category::with('children')->whereNull('parent_id')->get();
    }
    protected function rules()
    {
        $rules = [
            'title' => 'required|string',
            'selected_categories' => 'required',
            'content' => 'required',
            'slug' => ['required','unique:posts,slug,' . $this->postId],
        ];

        if ($this->cover_image) {
            $rules['cover_image'] = 'image|max:1024'; // 1MB Max
        }

        if ($this->type_post == 'evento') {
            $rules += [
                'starts_at' => 'required|date',
                'email' => 'nullable|email',
            ];
    
            if ($this->ends_at) {
                $rules['ends_at'] = 'after:starts_at';
            }
        }

        return $rules;
    }

    protected $messages = [
        'cover_image.image' => 'El archivo debe ser una imagen.',
        'cover_image.max' => 'La imagen no debe ser mayor a 1MB.',
        'title.required' => 'El título es obligatorio.',
        'title.string' => 'El título debe ser una cadena de texto.',
        'selected_categories.required' => 'Debe seleccionar al menos una categoría.',
        'content.required' => 'El contenido es obligatorio.',
        'slug.required' => 'El slug es obligatorio.',
        'slug.unique' => 'El slug ya está en uso.',
        'allow_comments' => 'boolean',
        'starts_at.required' => 'La fecha de inicio es obligatoria para eventos.',
        'starts_at.date' => 'La fecha de inicio debe ser una fecha válida.',
        'email.email' => 'El formato del correo electrónico es inválido.',
        'ends_at.after' => 'La fecha de cierre debe ser posterior a la fecha de inicio.',
    ];
    public function render()
    {
        $this->posts = Post::with('categories')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.admin-post');
    }
    public function settype(){
        $this->render();
    }
    public function updateSlug()
    {
        $this->slug = Str::slug($this->title);
    }
    public function store()
    {

        try {
           
            $this->validate();

            $allowCommentsValue = $this->allow_comments ? 1 : 0;

            $coverImagePath = null;
            if ($this->cover_image) {
                $coverImagePath = $this->storeCoverImage($this->cover_image);
            }

            $postData = [
                'title' => $this->title,
                'content' => $this->content,
                'slug' => $this->slug,
                'cover_image' => $coverImagePath,
                'allow_comments' => $allowCommentsValue,
                'excerpt' => $this->excerpt,
                'type' => $this->type_post,
            ];
            
            if ($this->type_post == 'evento') {
                $postData += [
                    'starts_at' => $this->starts_at,
                    'ends_at' => $this->ends_at,
                    'organizer' => $this->organizer,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'location' => $this->location,
                    'website' => $this->website,
                    'map' => $this->map,
                ];
            }

            if ($this->isEditing) {
                
                $post = Post::findOrFail($this->postId);
                if ($coverImagePath!=null && $coverImagePath != $post->cover_image) {
                   
                    Storage::delete('public/' . $post->cover_image);
                }
                if ($coverImagePath==null && $this->image_path==null && $post->cover_image!=null) {
                   
                    Storage::delete('public/' . $post->cover_image);
                }
                if($coverImagePath==null){
                    $postData['cover_image'] = $this->image_path;
                }

                $post->update($postData);    
                $post->categories()->sync($this->selected_categories);
                session()->flash('message', 'Post actualizado con éxito.');
            } else {

                $postData['user_id'] = Auth::id();
                $post = Post::create($postData);
                $post->categories()->sync($this->selected_categories);
                session()->flash('message', 'Post guardado con éxito.');
            }
            $this->closeForm();

        } catch (QueryException $e) {

            $errorCode = $e->errorInfo[1];
            $this->addError('error_message', 'Hubo un problema al guardar el post (' . $errorCode . ') ' . $e->getMessage());
        }

    }
    protected function storeCoverImage($image)
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $directory = "uploads/{$currentYear}/{$currentMonth}";

        $filename = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $image->getClientOriginalExtension();

        $fullFilename = "{$filename}.{$extension}";
        $counter = 1;

        // Check if file already exists and append counter if it does
        while (Storage::exists("public/{$directory}/{$fullFilename}")) {
            $fullFilename = "{$filename}-{$counter}.{$extension}";
            $counter++;
        }

        // Store the image and return the path relative to the uploads directory
        $storedPath = $image->storeAs("public/{$directory}", $fullFilename);

        // Remove 'public/' from the stored path
        return str_replace('public/', '', $storedPath);
    }
    public function edit($postId){
        
        $post = Post::findOrFail($postId);

        $this->postId = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->slug = $post->slug;
        $this->image_path = $post->cover_image;
        $this->allow_comments = (bool) $post->allow_comments;
        $this->excerpt = $post->excerpt;
        $this->selected_categories = $post->categories->pluck('id')->toArray(); 
        $this->type_post = $post->type;

        //CAMPOS PARA EVENTOS
        $this->starts_at = $post->starts_at;
        $this->ends_at = $post->ends_at;
        $this->organizer = $post->organizer;
        $this->phone = $post->phone;
        $this->email = $post->email;
        $this->location = $post->location;
        $this->website = $post->website;
        $this->map = $post->map;
        

        $this->isEditing = true;
        $this->isFormOpen = true;
        $this->dispatch('tinymce-update', $this->content);
    }

    public function enable($PostId)
    {
        $user = Post::findOrFail($PostId);
        $user->status = '1'; 
        $user->save();
    }

    public function disable($PostId)
    {
        $user = Post::findOrFail($PostId);
        $user->status = '0'; 
        $user->save();
    }
    public function confirmDelete($userId)
    {
        $this->postIdToDelete = $userId;
        $this->isDeleting = true;
        
    }
    public function deletePost()
    {
        if ($this->postIdToDelete) {
            Post::findOrFail($this->postIdToDelete)->delete();
            session()->flash('message', 'Usuario eliminado correctamente.');
        }
        $this->postIdToDelete = null; 
        $this->isDeleting = false;
    }
    public function cancelDelete()
    {
        $this->postIdToDelete = null; 
        $this->isDeleting = false;
    }
    public function openForm(){
        $this->resetForm();
        $this->isFormOpen = true;
        $this->isEditing = false;
    }
    public function closeForm(){
        $this->resetForm();
        $this->isFormOpen = false;
        $this->isEditing = false;
    }
    public function deleteImage(){
        $this->cover_image = null;
        $this->image_path = null;
    }
    public function resetForm(){
        $this->postId = null;
        $this->title = null;
        $this->content = null;
        $this->slug = null;
        $this->cover_image = null;
        $this->allow_comments = false;
        $this->excerpt = null;
        $this->selected_categories = []; 
        $this->type_post = 'noticia';
        $this->isEditing = false;
        $this->isFormOpen = false;
        $this->dispatch('tinymce-update', '');
    }
   
    
}
