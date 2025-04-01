<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Update user status (Activate/Deactivate)
    public function updateStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User status updated!');
    }

    // Update user role (Make Admin/Make User)
    public function updateRole(User $user)
    {
        $user->role = $user->role === 'user' ? 'admin' : 'user';
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User role updated!');
    }
}
