<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $novelCategory = Category::where('name', 'Novel')->first();
        $sainsCategory = Category::where('name', 'Sains')->first();
        $sejarahCategory = Category::where('name', 'Sejarah')->first();
        $komputerCategory = Category::where('name', 'Komputer')->first();

        // Novel Category
        Book::create([
            'category_id' => $novelCategory->id,
            'title'       => 'Laut Bercerita',
            'slug'        => Str::slug('Laut Bercerita'),
            'isbn'        => '978-602-424-694-5',
            'author'      => 'Leila S. Chudori',
            'publisher'   => 'Kepustakaan Populer Gramedia',
            'year'        => 2017,
            'rack_location' => 'A1',
            'stock'       => 10,
            'quantity_total' => 10,
            'quantity_available' => 10,
            'cover_path'  => 'books/LAUT_BERCERITA.jpg',
        ]);

        Book::create([
            'category_id' => $novelCategory->id,
            'title'       => '3726MDPL',
            'slug'        => Str::slug('3726MDPL'),
            'isbn'        => '978-623-310-259-9',
            'author'      => 'Nurwina Sari',
            'publisher'   => 'Romancious',
            'year'        => 2019,
            'rack_location' => 'A2',
            'stock'       => 5,
            'quantity_total' => 5,
            'quantity_available' => 5,
            'cover_path'  => 'books/3726MDPL.jpg',
        ]);

        Book::create([
            'category_id' => $novelCategory->id,
            'title'       => 'Laskar Pelangi',
            'slug'        => Str::slug('Laskar Pelangi'),
            'isbn'        => '978-979-500-954-0',
            'author'      => 'Andrea Hirata',
            'publisher'   => 'Bentang Pustaka',
            'year'        => 2005,
            'rack_location' => 'A3',
            'stock'       => 8,
            'quantity_total' => 8,
            'quantity_available' => 8,
            'cover_path'  => 'books/LASKAR_PELANGI.jpg',
        ]);

        // Sains Category
        Book::create([
            'category_id' => $sainsCategory->id,
            'title'       => 'Pengantar Fisika Modern',
            'slug'        => Str::slug('Pengantar Fisika Modern'),
            'isbn'        => '978-602-8637-99-1',
            'author'      => 'Kenneth S. Krane',
            'publisher'   => 'ITB Press',
            'year'        => 2012,
            'rack_location' => 'B1',
            'stock'       => 6,
            'quantity_total' => 6,
            'quantity_available' => 6,
            'cover_path'  => 'books/FISIKA_MODERN.jpg',
        ]);

        Book::create([
            'category_id' => $sainsCategory->id,
            'title'       => 'Biologi Sel dan Molekuler',
            'slug'        => Str::slug('Biologi Sel dan Molekuler'),
            'isbn'        => '978-602-496-110-9',
            'author'      => 'Alberts, B.',
            'publisher'   => 'Penerbit UGM',
            'year'        => 2010,
            'rack_location' => 'B2',
            'stock'       => 7,
            'quantity_total' => 7,
            'quantity_available' => 7,
            'cover_path'  => 'books/BIOLOGI_SEL.jpg',
        ]);

        // Sejarah Category
        Book::create([
            'category_id' => $sejarahCategory->id,
            'title'       => 'Sejarah Indonesia Modern',
            'slug'        => Str::slug('Sejarah Indonesia Modern'),
            'isbn'        => '978-602-50524-0-7',
            'author'      => 'Ricklefs, M.C.',
            'publisher'   => 'Penerbit Serambi',
            'year'        => 2008,
            'rack_location' => 'C1',
            'stock'       => 5,
            'quantity_total' => 5,
            'quantity_available' => 5,
            'cover_path'  => 'books/SEJARAH_INDONESIA.jpg',
        ]);

        Book::create([
            'category_id' => $sejarahCategory->id,
            'title'       => 'Peradaban pada Persimpangan Jalan',
            'slug'        => Str::slug('Peradaban pada Persimpangan Jalan'),
            'isbn'        => '978-602-421-999-9',
            'author'      => 'Soekarno',
            'publisher'   => 'Kepustakaan Populer Gramedia',
            'year'        => 2009,
            'rack_location' => 'C2',
            'stock'       => 4,
            'quantity_total' => 4,
            'quantity_available' => 4,
            'cover_path'  => 'books/PERADABAN.jpg',
        ]);

        // Komputer Category
        Book::create([
            'category_id' => $komputerCategory->id,
            'title'       => 'Clean Code',
            'slug'        => Str::slug('Clean Code'),
            'isbn'        => '978-0132350884',
            'author'      => 'Robert C. Martin',
            'publisher'   => 'Prentice Hall',
            'year'        => 2008,
            'rack_location' => 'D1',
            'stock'       => 9,
            'quantity_total' => 9,
            'quantity_available' => 9,
            'cover_path'  => 'books/CLEAN_CODE.jpg',
        ]);

        Book::create([
            'category_id' => $komputerCategory->id,
            'title'       => 'Design Patterns',
            'slug'        => Str::slug('Design Patterns'),
            'isbn'        => '978-0201633610',
            'author'      => 'Gang of Four',
            'publisher'   => 'Addison-Wesley',
            'year'        => 1994,
            'rack_location' => 'D2',
            'stock'       => 6,
            'quantity_total' => 6,
            'quantity_available' => 6,
            'cover_path'  => 'books/DESIGN_PATTERNS.jpg',
        ]);

        Book::create([
            'category_id' => $komputerCategory->id,
            'title'       => 'The Pragmatic Programmer',
            'slug'        => Str::slug('The Pragmatic Programmer'),
            'isbn'        => '978-0201616224',
            'author'      => 'David Thomas, Andrew Hunt',
            'publisher'   => 'Addison-Wesley',
            'year'        => 1999,
            'rack_location' => 'D3',
            'stock'       => 7,
            'quantity_total' => 7,
            'quantity_available' => 7,
            'cover_path'  => 'books/PRAGMATIC_PROGRAMMER.jpg',
        ]);
    }
}
