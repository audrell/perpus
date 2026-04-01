<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksImportTemplateExport implements FromArray, WithHeadings
{
    // Cara pakai:
    // 1. Definisikan header (nama kolom di baris 1)
    // 2. Berikan sample data (contoh isi untuk user follow)

    /**
     * Header: Nama kolom di row 1
     * Penting: Urutan harus sama dengan saat import!
     */
    public function headings(): array
    {
        return [
            'category_id',          // Kolom A
            'isbn',                 // Kolom B
            'title',                // Kolom C
            'author',               // Kolom D
            'publisher',            // Kolom E
            'year',                 // Kolom F
            'rack_location',        // Kolom G
            'quantity_total',       // Kolom H
            'quantity_available',   // Kolom I
        ];
    }

    /**
     * Sample data: Baris data contoh untuk user pahami
     * Ini akan muncul di baris 2-3 setelah header
     */
    public function array(): array
    {
        return [
            // Contoh buku 1
            ['1', '9786020324781', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 'A1-03', '10', '10'],

            // Contoh buku 2
            ['2', '9786230001112', 'Belajar Laravel Dasar', 'Developer', 'Informatika', '2024', 'T2-01', '5', '5'],
        ];
    }
}
