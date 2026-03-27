<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Book::with('category')->select('books.*');
            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('isbn', function ($row) {
                    return $row->isbn;
                })
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
                ->addColumn('cover', function ($row) {
                    if ($row->cover_path) {

                        $url = asset('storage/' . $row->cover_path);
                    } else {
                        
                        $url = 'https://via.placeholder.com/50x70?text=No+Cover';
                    }
                    return '<img src="' . $url . '" width="50" class="img-thumbnail">';
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<button class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i></button>';
                    $btn .=
                        '<form action="' .
                        route('books.destroy', $row->id) .
                        '" method="POST" class="d-inline">
                    ' .
                        csrf_field() .
                        method_field('DELETE') .
                        '
                    <button type="submit" class="btn btn-sm btn-danger show_confirm"><i class="fas fa-trash"></i></button>
                </form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['cover', 'action'])
                ->make(true);
        }

        $categories = Category::all();
        return view('auth.management.books.index', compact('categories'));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['quantity_available'] = $request->quantity_total;

        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'year' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'rack_location' => 'nullable|string',
            'quantity_total' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
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

        return redirect()->route('books.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }
}
