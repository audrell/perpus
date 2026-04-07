<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Novel', 'slug' => 'novel'],
            ['name' => 'Sains', 'slug' => 'sains'],
            ['name' => 'Sejarah', 'slug' => 'sejarah'],
            ['name' => 'Komputer', 'slug' => 'komputer'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
            ]);
        }
    }
}
