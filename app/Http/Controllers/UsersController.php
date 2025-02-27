<?php

namespace App\Http\Controllers;

use App\Mail\AccountInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'DESC')
            ->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')[0],
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        $roles = Role::select('id', 'name')->get();

        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        $temporaryPassword = Str::random(10);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($temporaryPassword),
        ]);

        $user->assignRole($request->role);

        Mail::to($request->email)->send(new AccountInvite($user, $temporaryPassword));

        return redirect()->route('users.index')->with('flash', [
            'message' => 'User created successfully!'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('flash', [
            'message' => 'User deleted successfully!'
        ]);
    }
}
