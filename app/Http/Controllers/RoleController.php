<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::with('division')->get();
        $divisions = Division::all();

        return view('roles.index', compact('users', 'divisions'));
    }

     public function create()
    {
        $divisions = Division::all();
        return view('roles.create', compact('divisions'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required|in:user,manager',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'division_id' => $request->division_id,
            'role' => $request->role,
        ]);
        return redirect()->route('roles.index')->with('success', 'User created successfully.');
    }
    public function edit(User $user)
    {
        $divisions = Division::all();
        return view('roles.edit', compact('user', 'divisions'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required|in:user,manager',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id,
            'role' => $request->role,
        ]);

        return redirect()->route('roles.index')->with('success', 'User updated successfully.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('roles.index')->with('success', 'User deleted successfully.');
    }
}
