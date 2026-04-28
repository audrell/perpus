<?php

namespace App\Http\Controllers;

use App\Exports\BooksImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\BooksTemplateReadImport;
use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\BooksExport;

class BookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Book::with('category')->select('books.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cover', function ($row) {
                    // Gunakan path lengkap ke folder storage
                    $url = $row->cover_path ? asset('storage/' . $row->cover_path) : asset('storage/books/default.jpg');

                   $default = asset('storage/books/default.jpg');
                    return '<img src="' . $url . '" width="50" class="img-thumbnail" onerror="this.src=\'' . $default . '\'">';
                })

                ->rawColumns(['cover', 'action'])

                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('author', function ($row) {
                    return $row->author;
                })
                ->editColumn('publisher', function ($row) {
                    return $row->publisher;
                })
                ->editColumn('category', function ($row) {
                    return $row->category ? $row->category->name : '-';
                })

                ->editColumn('isbn', function ($row) {
                    return $row->isbn;
                })

                ->addColumn('action', function ($row) {
                    return '

                        <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#modalShowBook' .
                        $row->id .
                        '">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#modalEditBook' .
                        $row->id .
                        '">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger show_confirm" data-id="' .
                        $row->id .
                        '">
                            <i class="fas fa-trash"></i>
                        </button>';
                })
                ->rawColumns(['cover', 'action'])
                ->make(true);
        }

        $books = Book::all();

        $categories = Category::all();
        return view('management.books.index', compact('books', 'categories'));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'year' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'rack_location' => 'required|string',
            'quantity_total' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
       DB::beginTransaction();
        try {

        $data = $request->all();
        $data['quantity_available'] = $request->quantity_total;

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('books', 'public');
        }

        Book::create([
            'category_id' => $request->category_id,
            'isbn' => $request->isbn,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'rack_location' => $request->rack_location,
            'quantity_total' => $request->quantity_total,
            'quantity_available' => $request->quantity_total,
            'cover_path' => $coverPath,
        ]);
        
        // ISI LOGIC
        DB::commit();
        return redirect()->route('books.index')->with('success', 'Buku baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Book $book)
    {
        //dd($request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {

        $data = $request->only(['title', 'cover', 'category_id']);

        if ($request->hasFile('cover')) {
            if ($book->cover_path && \Storage::disk('public')->exists($book->cover_path)) {
                \Storage::disk('public')->delete($book->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('books', 'public');
        }

        $data['quantity_available'] = $book->quantity_available + ($request->quantity_total - $book->quantity_total);

        $book->update($data);
        DB::commit();
        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function downloadImportTemplate()
    {
        // Pakai Excel facade untuk download
        // Parameter:
        // 1. BooksImportTemplateExport() = instance class export
        // 2. 'template_import_buku.xlsx' = nama file saat didownload

        return Excel::download(new BooksImportTemplateExport(), 'template_import_buku.xlsx');

        // Hasil: File akan di-download otomatis ke komputer user
    }

    public function import(Request $request): RedirectResponse
    {
        // ========== STEP 1: VALIDASI FILE ==========
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);
        // Cek: File harus ada, format xlsx/xls, max 5MB

        $file = $request->file('import_file');

        // ========== STEP 2: VALIDASI HEADER ==========
        // Header HARUS cocok dengan template, jika tidak akan error
        $expectedHeaders = ['judul_buku', 'penulis', 'penerbit', 'tahun', 'lokasi_rak', 'kategori', 'stok_total', 'stok_tersedia', 'isbn'];

        // Baca header dari file Excel (baris 1 saja)
        $headerRows = (new HeadingRowImport)->toArray($file);
        $normalizedHeader = $headerRows[0][0] ?? [];

        // Cek apakah header cocok
        if ($normalizedHeader !== $expectedHeaders) {
            return redirect()->route('books.index')->with('error', 'Format header file Excel tidak sesuai template default.');
        }

        // ========== STEP 3: BACA FILE EXCEL ==========
        // Import file ke dalam object, nanti ambil data dari $rows
        $import = new BooksTemplateReadImport();
        Excel::import($import, $file);
        $rows = $import->rows; // Collection berisi semua data buku

        // ========== STEP 4: PROSES SETIAP BARIS ==========
        $rowErrors = []; // Tempat simpan error per baris
        $payloads = []; // Tempat simpan data yang valid

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Baris di Excel (header = baris 1)

            // Skip baris kosong
            if (count(array_filter($row->toArray(), fn($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            // Extract data dari setiap kolom, buang spasi
            $rowData = [
                'kategori' => trim((string) $row->get('kategori', '')),
                'isbn' => trim((string) $row->get('isbn', '')),
                'title' => trim((string) $row->get('judul_buku', '')),
                'author' => trim((string) $row->get('penulis', '')),
                'publisher' => trim((string) $row->get('penerbit', '')),
                'year' => trim((string) $row->get('tahun', '')),
                'rack_location' => trim((string) $row->get('lokasi_rak', '')),
                'quantity_total' => trim((string) $row->get('stok_total', '')),
                'quantity_available' => trim((string) $row->get('stok_tersedia', '')),
            ];

            // ========== VALIDASI FIELD ==========
            $validator = Validator::make($rowData, [
                'kategori' => 'required|string|exists:categories,name',
                // Wajib diisi, harus angka, harus ada di tabel categories

                'isbn' => 'nullable|string|max:50',
                // Opsional, boleh kosong

                'title' => 'required|string|max:255|unique:books,title',
                // Wajib diisi

                'author' => 'required|string|max:255',
                // Wajib diisi

                'publisher' => 'nullable|string|max:255',
                // Opsional

                'year' => 'nullable|integer|digits:4',
                // Opsional, format 4 digit (YYYY)

                'rack_location' => 'nullable|string|max:100',
                // Opsional

                'quantity_total' => 'required|integer|min:0',
                // Wajib diisi, harus angka

                'quantity_available' => 'required|integer|min:0',
                // Wajib diisi, harus angka
            ]);

            // ========== VALIDASI CUSTOM ==========
            // quantity_available tidak boleh lebih besar dari quantity_total
            $validator->after(function ($validator) use ($rowData) {
                if (isset($rowData['quantity_total'], $rowData['quantity_available']) && is_numeric($rowData['quantity_total']) && is_numeric($rowData['quantity_available']) && (int) $rowData['quantity_available'] > (int) $rowData['quantity_total']) {
                    $validator->errors()->add('quantity_available', 'Quantity available tidak boleh lebih besar dari quantity total.');
                }
            });

            // Jika ada error, catat tapi lanjut ke baris berikutnya
            if ($validator->fails()) {
                $rowErrors[] = 'Baris ' . $rowNumber . ': ' . implode(' | ', $validator->errors()->all());
                continue;
            }

            $category = \App\Models\Category::where('name', $rowData['kategori'])->first();

            // Data valid, simpan untuk nanti di-insert ke DB
            $payloads[] = [
                'category_id' => $category->id,
                'isbn' => $rowData['isbn'] ?: null,
                'title' => $rowData['title'],
                'author' => $rowData['author'],
                'publisher' => $rowData['publisher'] ?: null,
                'year' => $rowData['year'] !== '' && $rowData['year'] !== null ? (int) $rowData['year'] : null,
                'rack_location' => $rowData['rack_location'] ?: null,
                'quantity_total' => (int) $rowData['quantity_total'],
                'quantity_available' => (int) $rowData['quantity_available'],
            ];
        }

        // ========== STEP 5: CEK HASIL VALIDASI ==========

        // Jika tidak ada data valid sama sekali
        if (empty($payloads)) {
            if (!empty($rowErrors)) {
                return redirect()->route('books.index')->with('error', 'Import gagal. Periksa detail error per baris.')->with('import_errors', $rowErrors);
            }
            return redirect()->route('books.index')->with('error', 'Tidak ada data yang bisa diimport.');
        }

        // Jika ada data valid tapi juga ada error di baris lain, reject semua
        if (!empty($rowErrors)) {
            return redirect()->route('books.index')->with('error', 'Import dibatalkan karena ada data tidak valid. Perbaiki lalu upload ulang.')->with('import_errors', $rowErrors);
        }

        // ========== STEP 6: SIMPAN KE DATABASE ==========
        // Gunakan transaction: jika ada error, rollback semua
        DB::transaction(function () use ($payloads) {
            foreach ($payloads as $payload) {
                $exists = Book::where('isbn', $payload['isbn'])->orWhere('title', $payload['title'])->exists();

                if (!$exists) {
                    Book::create($payload);
                }
            }
        });

        // ========== STEP 7: RETURN RESPONSE ==========
        return redirect()
            ->route('books.index')
            ->with('success', count($payloads) . ' buku berhasil diimport.');
    }

    public function export()
    {
        return Excel::download(new BooksExport(), 'Data-Buku-Perpustakaan.xlsx');
    }
}
