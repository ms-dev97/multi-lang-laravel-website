<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware(['can:browse-user'], only: ['index']),
            new Middleware('can:add-user', only: ['create', 'store']),
            new Middleware('can:read-user', only: ['show']),
            new Middleware('can:edit-user', only: ['edit', 'update']),
            new Middleware('can:delete-user', only: ['destroy']),
            new Middleware(function(Request $request, Closure $next) {
                // only super admin can access super admin role
                $reqParamRole = $request->route()->parameter('role')->name;
                $authUserRoles = $request->user()->roles->pluck('name')->toArray();

                if ($reqParamRole == 'super-admin' && !in_array('super-admin', $authUserRoles)) abort(404);
                return $next($request);
            }, except: ['index', 'create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'required|string',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name']
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'تم اضافة الدور');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Role $role)
    {
        $rolePermissions = $role->permissions;

        return view('admin.roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Role $role)
    {
        $permissions = Permission::get();
        $rolePermissions = $role->permissions;
        $rolePermissionsIds = $rolePermissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionsIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|'. Rule::unique('roles')->ignore($role->id),
            'display_name' => 'required|string',
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name']
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'تم تعديل الدور');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $permissions = $role->permissions;

        $role->delete();
        $role->revokePermissionTo($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'تم حذف الدور');
    }
}
