<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiskController extends Controller
{
    public function __construct()
    {
        Session::put('menu', 'items');
        Session::put('submenu', 'disks');
    }

    public function index()
    {
        return view('page.item.book.index');
    }
}
