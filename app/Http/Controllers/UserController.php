<?php 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Required for handling file deletions

class UserController extends Controller
{
    public function index()
    {
        // Eager load posts count for the index table
        $users = User::withCount('posts')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Image validation
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle File Upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('posts');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Image validation
        ]);

        // Handle optional password update
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        // Handle File Upload & Replace Old File
        if ($request->hasFile('avatar')) {
            // Delete the old avatar from the server if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Store the new avatar
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Delete the avatar file from the server to free up space
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}