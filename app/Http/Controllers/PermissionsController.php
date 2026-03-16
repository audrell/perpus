<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::select('id', 'name')->orderby('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center" style="gap: 10px;">';

                    $btn .=
                        '<a href="' .
                        route('permissions.edit', $row->id) .
                        '" class="btn btn-primary btn-sm mr-1">
                            <i class="fa fa-edit"></i>
                        </a>';

                    $btn .=
                        '<form action="' .
                        route('permissions.destroy', $row->id) .
                        '" method="POST" style="display:inline">
                ' .
                        csrf_field() .
                        '
                ' .
                        method_field('DELETE') .
                        '
                <button type="submit" class="btn btn-danger btn-sm show_confirm">
                    <i class="fa fa-trash"></i>
                </button>
             </form>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('permissions.index', ['isEdit' => null]);
    }

    public function create()
    {
        return redirect()->route('permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // Standar Spatie
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
    }

    public function edit($id)
    {
        $isEdit = Permission::findOrFail($id);
        return view('permissions.index', compact('isEdit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $id]);
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission Updated!');
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission Deleted!');
    }
}
