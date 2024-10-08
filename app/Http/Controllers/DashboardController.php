<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Dashboard');
        Session::put('menu', 'Dashboard');
    }

    public function index()
    {
        return view('page.dashboard');
    }
}
