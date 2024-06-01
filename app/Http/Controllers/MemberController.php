<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Member');

        Session::put('menu', 'members');
        Session::put('submenu', 'members');
    }

    public function index()
    {
        return view('page.member.member.index');
    }
}
