<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
{
    die("SAYA SEDANG DIEDIT!");
    // 1. Jalankan validasi seperti biasa
    $validator = Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // 2. JIKA VALIDASI LOLOS, KITA PAKSA BUAT USER & MEMBER DI SINI
    if (!$validator->fails()) {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Member::create([
            'user_id'     => $user->id,
            'member_code' => Member::generateNextMemberCode(),
            'name'        => $user->name,
            'is_active'   => 1,
        ]);

        // Setelah dibuat, kita bikin validatornya "seolah-olah" gagal unik
        // supaya tidak dibuat dua kali oleh sistem bawaan Laravel
        // ATAU kita biarkan saja dan hapus isi fungsi create() di bawahnya.
    }

    return $validator;
}

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::where('email', $data['email'])->first();
    }

    protected function registered(Request $request, $user)
    {
        // Kita gunakan Transaction agar jika member gagal dibuat, user juga dibatalkan
        DB::transaction(function () use ($user) {
            // Memanggil fungsi dari Model Member untuk membuat data member baru
            Member::create([
                'user_id' => $user->id, // [Logika: Ambil ID user yang baru saja sukses daftar]
                'member_code' => Member::generateNextMemberCode(), // [Logika: Panggil fungsi otomatis dari model]
                'name' => $user->name, // Ambil nama dari data user
                'is_active' => 1, // Beri status aktif
            ]);
        });

        // Setelah beres, biarkan Laravel lanjut ke halaman dashboard
        return redirect($this->redirectPath());
    }
}
