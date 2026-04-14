<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }
}
