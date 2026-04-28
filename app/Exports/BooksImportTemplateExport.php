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
            'judul_buku', // Kolom A
            'penulis', // Kolom B
            'penerbit', // Kolom C
            'tahun', // Kolom D
            'lokasi_rak', // Kolom E
            'kategori', // Kolom F
            'stok_total', // Kolom G
            'stok_tersedia', // Kolom H
            'isbn', // Kolom I
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
        ['Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 'A1-03', 'Novel', '10', '10', '9786020324781'],

        // Contoh buku 2
        ['Belajar Laravel Dasar', 'Developer', 'Informatika', '2024', 'T2-01', 'Komputer', '5', '5', '9786230001112'],
        ];
    }
}
