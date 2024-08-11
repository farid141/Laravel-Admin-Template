<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Menu');
        Session::put('menu', 'Menu-Menu');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!(request()->user()->can('viewAny~Menu-Menu')))
            return abort(403, 'unauthorized access');

        $menus = Menu::with('submenus')->get();
        if (request()->ajax()) {
            return $menus;
        }

        $iconPath = public_path('assets/extensions/bootstrap-icons/icons');
        $icons = File::allFiles($iconPath);
        return view('administration.menu.index', compact('menus', 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!(request()->user()->can('create~Menu-Menu')))
            return abort(403, 'unauthorized access');

        $validated = $request->validate([
            'name' => ['required', 'unique:menus'],
            'order' => ['required', 'numeric',  'unique:menus,order'],
            'icon' => ['required'],
            'has_child' => 'sometimes|accepted',
            'url' => ['required_unless:has_child,on'],
        ]);

        $validated['has_child'] = $request->has('has_child') ? 1 : 0;
        Menu::create($validated);

        return Response()->json([
            'content' => 'menu created!',
            'type' => 'success' // or 'error'
        ]);
    }

    public function show(string $id)
    {
        if (!(request()->user()->can('view~Menu-Menu')))
            return abort(403, 'unauthorized access');

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
        if (!(request()->user()->can('update~Menu-Menu')))
            return abort(403, 'unauthorized access');

        $menu = Menu::find($id);
        return Response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!(request()->user()->can('update~Menu-Menu')))
            return abort(403, 'unauthorized access');

        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('menus', 'name')->ignore($id)
            ],
            'order' => ['required', 'numeric'],
            'icon' => ['required'],
            'has_child' => 'sometimes|accepted',
            'url' => ['required_unless:has_child,on'],
        ]);

        $validated['has_child'] = $request->has('has_child') ? 1 : 0;
        Menu::where('id', $id)->update($validated);

        return Response()->json([
            'content' => 'menu updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(request()->user()->can('delete~Menu-Menu')))
            return abort(403, 'unauthorized access');

        $menu = Menu::find($id);
        $menu->delete();

        return Response()->json([
            'content' => 'menu ' . $menu['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
