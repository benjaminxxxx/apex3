<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Post;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = ['Laravel', 'PHP', 'JavaScript', 'Web Development', 'Programming'];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName, 'slug' => \Str::slug($tagName)]);
        }

        $posts = Post::all();
        foreach ($posts as $post) {
            $post->tags()->attach(Tag::inRandomOrder()->take(2)->pluck('id'));
        }
    }
}