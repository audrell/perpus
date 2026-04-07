<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
{

    $category = \App\Models\Category::first();

    \App\Models\Book::create([
        'category_id' => $category->id,
        'title'       => 'Laut Bercerita',
        'isbn'        => '978-602-424-694-5',
        'author'      => 'Leila S. Chudori',
        'stock'       => 10,
        'cover_path'  => 'books/LAUT BERCERITA.jpg',
        'publisher'   => 'Kepustakaan Populer Gramedia'
    ]);

    \App\Models\Book::create([
        'category_id' => $category->id,
        'title'       => '3726MDPL',
        'isbn'        => '978-623-310-259-9',
        'author'      => 'Nurwina Sari',
        'stock'       => 5,
        'cover_path'  => 'books/3726MDPL.jpg',
        'publisher'   => 'Romancious'
    ]);
}
}
