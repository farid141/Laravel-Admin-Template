<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('authentication.index');
    }

    public function store(Request $request)
    {
        return redirect()->intended('/dashboard');
    }
}
