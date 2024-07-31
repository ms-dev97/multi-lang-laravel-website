<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-user', only: ['index']),
            new Middleware('can:add-user', only: ['create', 'store']),
            new Middleware('can:read-user', only: ['show']),
            new Middleware('can:edit-user', only: ['edit', 'update']),
            new Middleware('can:delete-user', only: ['destroy']),
            new Middleware(function(Request $request, Closure $next) {
                $authUserID = $request->user()->id;
                $routeParamID = $request->route()->parameter('user')->id;
                
                if ( ($routeParamID == 1 && $authUserID != 1) || ($routeParamID == 2 && !in_array($authUserID, [1, 2])) ) {
                    abort(404);
                }
                return $next($request);
            }, except: ['index', 'create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::hideAdmin()->with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select(['id', 'name'])->whereNotIn('id', [1, 2])->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($validated['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'تم اضافة المستخدم');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $userRoles = $user->roles;
        return view('admin.users.show', compact('user', 'userRoles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $roles = Role::select(['id', 'name'])->whereNotIn('id', [1, 2])->get();
        $userRolesNames = $user->getRoleNames()->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRolesNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|' . Rule::unique('users')->ignore($user->id),
            'password' => 'nullable|min:6',
            'roles' => 'nullable|array',
        ]);

        $password = !is_null($validated['password']) ? Hash::make($validated['password']) : $user->password;

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $password,
        ]);

        if ($request->has('roles')) {
            if (!$user->hasExactRoles($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }
        } else {
            $user->syncRoles([]);
        }


        return redirect()->route('admin.users.index')->with('success', 'تم تعديل بيانات المستخدم');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم');
    }
}
