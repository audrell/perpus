<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:users.index|users.create|users.edit|users.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select('users.*');
            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('nomor', function () {
                    static $counter = 0;

                    return ++$counter;
                })
                ->addColumn('roles', function (User $user) {
                    if ($user->roles->isEmpty()) {
                        return '<span class="badge badge-secondary">No Role</span>';
                    }

                    return $user->roles
                        ->map(function ($role) {
                            return '<span class="badge bg-label-primary mb-1 mr-1">' . e($role->name) . '</span>';
                        })
                        ->implode(' ');
                })
                ->addColumn('action', function (User $user) {
                    return '<button type="button" class="btn btn-info btn-sm mr-1" data-toggle="modal" data-target="#modalShowUser' .
                        $user->id .
                        '">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm mr-1" data-toggle="modal" data-target="#modalEditUser' .
                        $user->id .
                        '">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form method="POST" action="' .
                        route('users.destroy', $user->id) .
                        '" class="delete-form" style="display:inline;">
                            ' .
                        csrf_field() .
                        '
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-danger btn-sm show_confirm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['nomor', 'roles', 'action'])
                ->make(true);
        }

        return view('users.index', $this->getIndexData());
    }

    /**
     * Data kebutuhan view index user berbasis modal.
     */
    protected function getIndexData(): array
    {
        return [
            'data' => User::with('roles')->latest()->get(),
            'roles' => Role::where('name', '!=', 'member')->pluck('name', 'name'),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('users.index', $this->getIndexData());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        try {
            return DB::transaction(function () use ($input, $request) {
                $user = User::create($input);
                $user->assignRole($request->input('roles'));

                \App\Models\Member::create([
                    'user_id' => $user->id,
                    'name' => $input['name'],
                    'member_code' => 'MBR-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'is_active' => 1,
                ]);

                return redirect()->route('users.index')->with('success', 'User dan Data Member berhasil dibuat');
            });
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        return view('users.index', $this->getIndexData());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        return view('users.index', $this->getIndexData());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
{
    // 1. Validasi input
    $this->validate($request, [
        'name'     => 'required',
        'email'    => 'required|email|unique:users,email,' . $id,
        'password' => 'same:confirm-password',
    ]);

    $user = User::find($id);

    // 2. Olah data User (Hanya ambil name dan email untuk tabel users)
    $input = $request->only(['name', 'email']);
    if (!empty($request->password)) {
        $input['password'] = Hash::make($request->password);
    }

    // Update tabel 'users'
    $user->update($input);

    // 3. Olah data Member (is_active)
    if ($user->member) {
        $user->member->update([
            // Mengubah 'Aktif' jadi 1, 'Nonaktif' jadi 0 agar sesuai tipe integer di database
            'is_active' => ($request->status == 'Aktif') ? 1 : 0
        ]);
    }

    // 4. Update Roles dengan Proteksi (Agar admin tidak menghapus rolenya sendiri)
    if ($user->id !== Auth::id()) {
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
    }

    return redirect()->route('users.index')->with('success', 'User updated successfully');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = $request->status;
        $user->save();
        return back()->with('success', 'Status berhasil diperbarui!');
    }
}
