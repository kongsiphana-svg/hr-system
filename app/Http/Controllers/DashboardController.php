<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_employees' => Employee::count(),
            'pending_leaves' => LeaveRequest::whereIn('status', ['Pending', 'pending'])->count(),
        ];
        $recentLeaves = LeaveRequest::with('user')->latest()->take(4)->get();

        return view('dashboard.index', compact('metrics', 'recentLeaves'));
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255|unique:users,name,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $this->storeAvatar($request->file('avatar'));
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Your profile details have been successfully saved!');
    }

    /**
     * Store an uploaded avatar and return its public URL.
     */
    protected function storeAvatar(\Illuminate\Http\UploadedFile $file): string
    {
        $directory = public_path('uploads/avatars');

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException("Unable to create avatar directory.");
        }

        $filename = uniqid('avatar_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return asset('uploads/avatars/'.$filename);
    }
}