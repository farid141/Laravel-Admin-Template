<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        Session::put('page_title', 'Permission');
        Session::put('menu', 'Access-Permission');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!(request()->user()->can('viewAny~Access-Permission')))
            return abort(403, 'unauthorized access');

        $permissions = Permission::all();
        if (request()->ajax()) {
            return $permissions;
        }

        $actions = ['viewAny', 'view', 'create', 'update', 'delete'];

        return view('administration.permission.index', compact('permissions', 'actions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!(request()->user()->can('create~Access-Permission')))
            return abort(403, 'unauthorized access');

        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => ['required', 'unique:permissions,name'],
        ]);

        foreach ($validated['permissions'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        return Response()->json([
            'content' => 'Permission ' . implode(',', $validated['permissions']) . ' created!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!(request()->user()->can('update~Access-Permission')))
            return abort(403, 'unauthorized access');

        $permission = Permission::with('permissions')->find($id);
        return Response()->json($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!(request()->user()->can('update~Access-Permission')))
            return abort(403, 'unauthorized access');

        $validated = $request->validate([
            'name' => ['required', Rule::unique('permissions', 'name')->ignore($id)],
        ]);

        $permission = Permission::find($id);
        $permission->update($validated);

        return Response()->json([
            'content' => 'Permission ' . $permission['name'] . ' created!',
            'type' => 'success' // or 'error'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!(request()->user()->can('delete~Access-Permission')))
            return abort(403, 'unauthorized access');

        $permission = Permission::find($id);
        $permission->delete();

        return Response()->json([
            'content' => 'Permission ' . $permission['name'] . ' deleted!',
            'type' => 'success' // or 'error'
        ]);
    }
}
