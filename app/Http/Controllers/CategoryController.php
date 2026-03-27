<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
     function __construct()
    {
        $this->middleware('permission:categories.index|categories.create|categories.edit|categories.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:categories.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Category::orderBy('created_at', 'desc')->get();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('name', function ($datas) {
                    return $datas->name ?? 'N/A';
                })
                ->addColumn('action', function ($datas) {
                    return '
                        <button type="button" class="btn btn-warning btn-sm mr-1" data-toggle="modal" data-target="#modalEditCategory' . $datas->id . '">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form method="POST" action="' . route('categories.destroy', $datas->id) . '" class="delete-form" style="display:inline;">
                            ' . csrf_field() . '
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-danger btn-sm show_confirm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = Category::all();
        return view('auth.management.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
        ]);
        // Source - https://stackoverflow.com/a/55149553
// Posted by Piotr, modified by community. See post 'Timeline' for change history
// Retrieved 2026-03-27, License - CC BY-SA 4.0

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
            ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
