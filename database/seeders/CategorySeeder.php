<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Noticias' => ['Tecnología', 'Negocios', 'Deportes'],
            'Eventos' => ['Conferencias', 'Meetups', 'Seminarios'],
            'Publicaciones' => ['Artículos', 'Blogs', 'Noticias Corporativas']
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::create(['name' => $parent, 'slug' => \Str::slug($parent)]);

            foreach ($children as $child) {
                Category::create(['name' => $child, 'slug' => \Str::slug($child), 'parent_id' => $parentCategory->id]);
            }
        }
    }
}
