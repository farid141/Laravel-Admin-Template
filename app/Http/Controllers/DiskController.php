<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiskController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Disk');
        Session::put('menu', 'Items-Disks');
    }

    public function index()
    {
        return view('page.item.book.index');
    }
}
