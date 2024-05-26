<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        Session::put('menu', 'dashboard');
        Session::put('submenu', '');
    }

    public function index()
    {
        return view('page.dashboard');
    }
}
