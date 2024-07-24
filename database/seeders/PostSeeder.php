<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Reaction;

class PostSeeder extends Seeder
{
    public function run()
    {
        $newsCategory = Category::where('slug', 'noticias')->first();

        for ($i = 1; $i <= 15; $i++) {
            $post = Post::create([
                'created_by' => 1,
                'code' => \Str::random(15),
                'title' => "Noticia de Informática $i",
                'content' => "Contenido de la noticia de informática $i.",
                'slug' => \Str::slug("Noticia de Informática $i"),
                'cover_image' => 'default.jpg'
            ]);

            $post->categories()->attach($newsCategory);

            
        }
    }
}