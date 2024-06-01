<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Book');

        Session::put('menu', 'items');
        Session::put('submenu', 'books');
    }

    public function index()
    {
        return view('page.item.book.index');
    }
}
