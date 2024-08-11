<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('super-admin')),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|' . Rule::unique('permissions', 'name')->where('guard_name', 'web'),
            'display_name' => 'required|string',
            'table_name' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'table_name' => $validated['table_name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|' . Rule::unique('permissions', 'name')->where('guard_name', 'web')->ignore($permission->id),
            'display_name' => 'required|string',
            'table_name' => 'nullable|string',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'table_name' => $validated['table_name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;

        $permissions = Permission::latest()
            ->where('name', 'like', "%{$search}%")
            ->paginate(15)
            ->withQueryString();
        return view('admin.permissions.index', compact('permissions', 'search'));
    }
}
