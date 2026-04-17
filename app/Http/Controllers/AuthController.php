<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users',
    //         'password' => 'required|confirmed',
    //     ]);

    //     return DB::transaction(function () use ($request) {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         Member::create([
    //             'user_id' => $user->id,
    //             'member_code' => Member::generateMemberCode(),
    //             'name' => $user->name,
    //             'is_active' => true,
    //         ]);

    //         return $user;
    //     });
    // }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ],
        );

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withInput($request->only('email'))->with('loginError', 'Data user tidak ditemukan.');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withInput($request->only('email'))->with('loginError', 'Password salah.');
        }

        if ($user->member && $user->member->is_active == 0) {
             abort(403, 'Akun Anda telah dinonaktifkan.');
    }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }


        return back()->withInput($request->only('email'))->with('loginError', 'Login gagal. Silakan coba lagi.');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|numeric|digits_between:10,15',
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        Member::create([
            'user_id' => $user->id,
            'member_code' => Member::generateNextMemberCode(),
            'name' => $user->name,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        $user->assignRole(['member']);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
