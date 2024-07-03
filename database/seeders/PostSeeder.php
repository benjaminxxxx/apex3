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
                'user_id' => 1,
                'title' => "Noticia de Informática $i",
                'content' => "Contenido de la noticia de informática $i.",
                'slug' => \Str::slug("Noticia de Informática $i"),
                'cover_image' => 'default.jpg'
            ]);

            $post->categories()->attach($newsCategory);

            if ($i % 3 == 0) {
                for ($j = 1; $j <= 3; $j++) {
                    Comment::create([
                        'post_id' => $post->id,
                        'user_id' => 1,
                        'parent_id' => null,
                        'content' => "Comentario $j en Noticia de Informática $i."
                    ]);
                }
            }

            for ($k = 1; $k <= 3; $k++) {
                Reaction::create([
                    'post_id' => $post->id,
                    'user_id' => 1,
                    'type' => 'like'
                ]);
            }
        }
    }
}