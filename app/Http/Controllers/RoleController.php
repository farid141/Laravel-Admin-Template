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
        Session::put('page_title', 'Role');
        Session::put('menu', 'Access-Role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!(request()->user()->can('viewAny~Access-Role')))
            return abort(403, 'unauthorized access');

        $roles = Role::all();
        if (request()->ajax()) {
            return $roles;
        }
        $permissions = Permission::all()->pluck('name')->toArray();

        return view('administration.role.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!(request()->user()->can('view~Access-Role')))
            return abort(403, 'unauthorized access');

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
        if (!(request()->user()->can('update~Access-Role')))
            return abort(403, 'unauthorized access');

        $role = Role::with('permissions')->find($id);
        return Response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!(request()->user()->can('update~Access-Role')))
            return abort(403, 'unauthorized access');

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
        if (!(request()->user()->can('delete~Access-Role')))
            return abort(403, 'unauthorized access');

        $role = Role::find($id);
        $role->delete();

        return Response()->json([
            'content' => 'role ' . $role['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
