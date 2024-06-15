<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'role');

        Session::put('menu', 'access');
        Session::put('submenu', 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        if (request()->ajax()) {
            return $roles;
        }
        $grouppedPermissions = Permission::all()->pluck('name')->sort()
            ->groupBy(function ($item) {
                return explode(' ', $item)[1];
            });

        return view('administration.role.index', compact('roles', 'grouppedPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:roles'],
            'permissions' => ['required'],
        ]);

        Role::create(['name' => $validated['name']])->syncPermissions($validated['permissions']);

        return Response()->json([
            'content' => 'role ' . $validated['name'] . ' created!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->find($id);
        return Response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => ['required', Rule::unique('roles', 'name')->ignore($id)],
            'permissions' => ['required'],
        ]);

        $role = Role::find($id);
        $role->update($validated);
        $role->syncPermissions($validated['permissions']);

        return Response()->json([
            'content' => 'role ' . $role['name'] . ' updated!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();

        return Response()->json([
            'content' => 'role ' . $role['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
