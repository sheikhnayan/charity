<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Website;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $websiteId = $currentUser->website_id;

        $filterType = $request->query('type');
        $roleMap = [
            'participant' => 'individual',
            'parent' => 'parents',
        ];

        $usersQuery = User::with(['roles', 'website', 'parent', 'teacher', 'donations'])
            ->where('website_id', $websiteId);

        if (isset($roleMap[$filterType])) {
            $usersQuery->where('role', $roleMap[$filterType]);
        }

        $users = $usersQuery->paginate(20)->appends($request->query());

        return view('user.users.index', compact('users', 'filterType'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('user.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array|min:1'
        ]);

        $currentUser = Auth::user();
        $websiteId = $currentUser->website_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'website_id' => $websiteId,
            'status' => 'active',
        ]);

        foreach ($request->input('roles', []) as $roleName) {
            $user->assignRoleForWebsite($roleName, $websiteId);
        }

        return redirect()->route('users.manage-users.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();

        $assigned = \DB::table('role_user_website')
            ->where('user_id', $user->id)
            ->pluck('role_id')
            ->toArray();

        $assignedNames = [];
        if (!empty($assigned)) {
            $roleModels = Role::whereIn('id', $assigned)->get();
            $assignedNames = $roleModels->pluck('name')->toArray();
        }

        return view('user.users.edit', compact('user', 'roles', 'assignedNames'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'required|array|min:1'
        ]);

        $currentUser = Auth::user();
        $websiteId = $currentUser->website_id;

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->website_id = $websiteId;
        $user->save();

        $user->syncRolesForWebsite($request->input('roles', []), $websiteId);

        return redirect()->route('users.manage-users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.manage-users.index')->with('success', 'User deleted successfully');
    }
}
