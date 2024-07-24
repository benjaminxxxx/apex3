<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use App\Models\PostVisibilityLevel;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Auth;

class NewMain extends Component
{
    use WithFileUploads;
    public $news_type;
    public $openCreateNewNews = false;
    public $visibility;
    public $news;
    public $title;
    public $slug;
    public $content_noticia;
    public $image_path;
    public $cover_image;
    public $categories;
    public $selected_categories = [];
    public $isEditing;
    public $newsId;
    public $offset;

    public function mount()
    {
        $this->categories = Category::where('parent_id',1)->get();
        $this->news = collect();
        $this->offset = 0;
        $this->loadMore();
    }
    public function render()
    {

        return view('livewire.new-main');
    }
    public function loadMore()
    {
        $user = auth()->user();
        $additionalNews = $user->getMyNews($this->offset,$this->news_type);
        $this->news = $this->news->merge($additionalNews);
        $this->offset += 5;
    }
    protected function rules()
    {
        $rules = [
            'title' => 'required|string',
            'selected_categories' => 'required',
            'content_noticia' => 'required',
            'slug' => ['required','unique:posts,slug,' . $this->newsId],
        ];

        if ($this->cover_image) {
            $rules['cover_image'] = 'image|max:10240'; // 10MB Max
        }

        return $rules;
    }

    protected $messages = [
        'cover_image.image' => 'El archivo debe ser una imagen.',
        'cover_image.max' => 'La imagen no debe ser mayor a 10MB.',
        'title.required' => 'El título es obligatorio.',
        'title.string' => 'El título debe ser una cadena de texto.',
        'selected_categories.required' => 'Debe seleccionar al menos una categoría.',
        'visibility.required' => 'Debe seleccionar al menos una visibilidad.',
        'content_noticia.required' => 'El contenido es obligatorio.',
        'slug.required' => 'El slug es obligatorio.',
        'slug.unique' => 'El slug ya está en uso.',
        'allow_comments' => 'boolean'
    ];

    public function updateSlug()
    {
        $this->slug = Str::slug($this->title);
    }
    public function setOpenNewArticle(){
        $this->resetFields();
        $this->openCreateNewNews = true;
    }
    
    /*
        if(Auth::user()->role_id==1 || Auth::user()->role_id==2){
            $this->news = Post::take(5);
        }else{
            // Obtener IDs de eventos basados en roles
            $newsIdsByRole = PostVisibilityLevel::where('visibility_level', $roleId)
            ->pluck('post_id');

            // Obtener eventos creados por el usuario
            $newsIdsByCreator = Post::where('created_by', $userId)
            ->pluck('id');

            // Combinar ambos conjuntos de eventos
            $newsIds = $newsIdsByRole->merge($newsIdsByCreator)->unique();

            // Obtener eventos basados en los IDs combinados
            $this->news = Post::whereIn('id', $newsIds)
            ->where('type', $this->news_type)
            ->get();
        }*/
    public function store()
    {
        $this->validate();

        try {

            $coverImagePath = null;
            if ($this->cover_image)
                $coverImagePath = $this->storeCoverImage($this->cover_image);
            
            $newData = [
                'title' => $this->title,
                'content' => $this->content_noticia,
                'slug' => $this->slug,
                'cover_image' => $coverImagePath,
                'type' => $this->news_type,
            ];
            
            if ($this->isEditing) {
                
                $new = Post::findOrFail($this->newsId);
                if ($coverImagePath!=null && $coverImagePath != $new->cover_image) {
                   
                    Storage::delete('public/' . $new->cover_image);
                }
                if ($coverImagePath==null && $this->image_path==null && $new->cover_image!=null) {
                   
                    Storage::delete('public/' . $new->cover_image);
                }
                if($coverImagePath==null){
                    $newData['cover_image'] = $this->image_path;
                }

                $new->update($newData);    
                $new->categories()->sync($this->selected_categories);

                $roles=[];

                if (empty($this->visibility)) {
                    $roles = [3, 4]; // Administradores, Gestores, Socios
                } else {
                    $roles = [$this->visibility];
                }
                PostVisibilityLevel::where('post_id',$new->id)->delete();
                if($roles){
                    foreach ($roles as $role) {
                        PostVisibilityLevel::create([
                            'post_id' => $new->id,
                            'visibility_level' => $role,
                        ]);
                    }
                }

                session()->flash('message', 'Noticia actualizada con éxito.');
            } else {

                $newData['created_by'] = Auth::id();
                $newData['code'] = Str::random(15);
                $new = Post::create($newData);
                $new->categories()->sync($this->selected_categories);
                
                if (empty($this->visibility)) {
                    $roles = [3, 4]; // Administradores, Gestores, Socios
                } else {
                    $roles = [$this->visibility];
                }
                PostVisibilityLevel::where('post_id',$new->id)->delete();
                if($roles){
                    foreach ($roles as $role) {
                        PostVisibilityLevel::create([
                            'post_id' => $new->id,
                            'visibility_level' => $role,
                        ]);
                    }
                }
                session()->flash('message', 'Noticia publicada con éxito.');
            }

            $this->resetFields();
            $this->openCreateNewNews = false;

            $this->news = collect();
            $this->offset = 0;
            $this->loadMore();

        } catch (QueryException $e) {
            session()->flash('error', 'Ocurrió un error al crear la noticia. Por favor, inténtelo de nuevo.' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.' . $e->getMessage());
        }
    }

    public function edit($newCode){
        $user = auth()->user();
        $new = Post::where('code', $newCode)->first();
        
        if (!$new) {
            session()->flash('error', 'la noticia no existe.');
            return;
        }
        
        if ($new->created_by !== $user->id) {
            session()->flash('error', 'No tienes permiso para eliminar esta noticia.');
            return;
        }
        
        $this->isEditing = true;
        $this->newsId = $new->id;
        $this->title = $new->title;
        $this->content_noticia = $new->content;
        $this->slug = $new->slug;
        $this->image_path = $new->cover_image;
        $this->openCreateNewNews = true;
        
        $this->selected_categories = $new->categories->pluck('id')->toArray(); 
        $roles = PostVisibilityLevel::where('post_id', $new->id)->pluck('visibility_level')->toArray();
    
        if (count($roles) > 1) {
            $this->visibility = null;
        } else {
            $this->visibility = $roles[0];
        }

        $this->dispatch('tinymce-update', $this->content_noticia);
       
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

        $directory = "news/{$currentYear}/{$currentMonth}";

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
    public function delete($newCode)
    {
        $article = Post::where('code', $newCode)->first();

        if ($article) {
            $article->delete();
            $this->news = $this->news->reject(function ($item) use ($newCode) {
                return $item['code'] === $newCode;
            });
            session()->flash('message', 'Post eliminado exitosamente.');
        } else {
            session()->flash('error', 'Post no encontrado.');
        }
    }
    public function resetFields()
    {
        $this->newsId = null;
        $this->title = '';
        $this->content_noticia = '';
        $this->slug = '';
        $this->cover_image = null;
        $this->image_path = null;
        $this->visibility = null;
        $this->selected_categories = [];
        $this->dispatch('tinymce-update', $this->content_noticia);
    }
}
