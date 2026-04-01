<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksTemplateReadImport implements ToCollection, WithHeadingRow
{
    // Cara paxa:
    // 1. File Excel dibaca jadi Collection
    // 2. Row pertama otomatis jadi header/key
    // 3. Data disimpan di property $rows untuk diproses di controller

    /**
     * Property untuk simpan semua data dari Excel
     * Nanti diakses di controller: $import->rows
     */
    public Collection $rows;

    public function __construct()
    {
        $this->rows = collect();
    }

    /**
     * Method dipanggil otomatis saat file dibaca
     * $collection = semua baris data dari Excel (tanpa header)
     */
    public function collection(Collection $collection): void
    {
        // Simpan data ke property $rows
        // Setiap row jadi Collection dengan header sebagai key
        $this->rows = $collection;

        // Contoh:
        // $this->rows[0]['title'] → 'Laskar Pelangi'
        // $this->rows[0]['author'] → 'Andrea Hirata'
        // $this->rows[0]['category_id'] → '1'
    }
}
