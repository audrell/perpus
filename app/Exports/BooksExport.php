<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BooksExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        // Gunakan with('category') agar tidak berat saat load data kategori
        return Book::with('category')->orderBy('title', 'asc');
    }

    // Menentukan Judul Header di Excel
    public function headings(): array
    {
        return [
            'Judul Buku',
            'Penulis',
            'Penerbit',
            'Tahun',
            'Lokasi_rak',
            'Kategori',
            'Stok Total',
            'Stok Tersedia',
            'ISBN'
        ];
    }

    // menentukan data apa saja yang masuk ke kolom
    public function map($book): array
    {
        return [
            $book->title,
            $book->author,
            $book->publisher,
            $book->year,
            $book->rack_location,
            $book->category->name ?? 'categories', // nama kategori
            $book->quantity_total,
            $book->quantity_available,
            $book->isbn,
        ];
    }
}
