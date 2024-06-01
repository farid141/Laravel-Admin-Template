<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Menu');

        Session::put('menu', 'menu');
        Session::put('submenu', 'menu');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('submenus')->get();
        if (request()->ajax()) {
            return $menus;
        }
        return view('page.menu.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iconPath = public_path('assets/extensions/bootstrap-icons/icons');
        $icons = File::allFiles($iconPath);
        return view('page.menu.menu.create', compact('icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:menus'],
            'order' => ['required', 'numeric',  'unique:menus,order'],
            'icon' => ['required'],
        ]);
        Menu::create($validated);

        session()->flash('message', [
            'content' => 'menu created!',
            'type' => 'success' // or 'error'
        ]);

        return redirect('menu');
    }

    public function show(string $id)
    {
        $menu = Menu::with('submenus')->find($id);
        if (request()->ajax()) {
            return $menu->submenus;
        }

        return view('page.menu.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $iconPath = public_path('assets/extensions/bootstrap-icons/icons');
        $icons = File::allFiles($iconPath);

        $menu = Menu::find($id);
        return view('page.menu.menu.edit', compact('menu', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:menus'],
            'order' => ['required', 'numeric'],
        ]);
        Menu::where('id', $id)->update($validated);

        session()->flash('message', [
            'content' => 'menu updated!',
            'type' => 'success' // or 'error'
        ]);

        return redirect('menu');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::find($id);
        $menu->delete();

        return Response()->json([
            'content' => 'submenu ' . $menu['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
