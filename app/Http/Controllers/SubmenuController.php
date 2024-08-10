<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class SubmenuController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Submenu');
        Session::put('menu', 'Menu-Submenu');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submenus = Submenu::with('menu')->get();

        if (request()->ajax()) {
            return $submenus;
        }

        $menus = Menu::where('has_child', 1)->get();
        $permissions = ['view', 'viewAny', 'create', 'update', 'delete'];
        return view('administration.submenu.index', compact('submenus', 'menus', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'name' => ['required', 'unique:submenus'],
            'order' => ['required', 'numeric', Rule::unique('submenus')->where(function ($query) use ($request) {
                return $query->where('menu_id', $request->menu_id);
            })],
            'url' => ['required'],
        ]);
        Submenu::create($validated);

        if ($request['create-permission']) {
            $permission_name = $request['permission-name'];
            if ($request['permissions'] && $permission_name) {
                $permission_in_database = Permission::all('name')->pluck('name')->toArray();
                foreach ($request['permissions'] as $permission) {
                    // if permission isnt exist, create it
                    if (!in_array("$permission~$permission_name", $permission_in_database))
                        Permission::create(['name' => "$permission~$permission_name"]);
                }
            }
        }

        return Response()->json([
            'content' => 'submenu ' . $validated['name'] . ' added!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $submenu = Submenu::with(['menu'])->find($id);
        return Response()->json($submenu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'name' => [
                'required',
                Rule::unique('submenus', 'name')->ignore($id)
            ],
            'order' => [
                'required',
                'numeric',
                Rule::unique('submenus', 'order')->where(function ($query) use ($request) {
                    return $query->where('menu_id', $request->menu_id);
                })->ignore($id)
            ],
            'url' => ['required'],
        ]);
        Submenu::where('id', $id)->update($validated);

        return Response()->json([
            'content' => 'submenu ' . $validated['name'] . ' updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $submenu = Submenu::find($id);
        $submenu->delete();

        return Response()->json([
            'content' => 'submenu ' . $submenu['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
