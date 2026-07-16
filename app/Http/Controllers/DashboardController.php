<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_employees' => User::count(),
            'pending_leaves'  => LeaveRequest::where('status', 'Pending')->count(),
        ];
        $recentLeaves = LeaveRequest::with('user')->latest()->take(4)->get();

        return view('dashboard.index', compact('metrics', 'recentLeaves'));
    }

    public function employees()
    {
        $employees = User::latest()->paginate(10);
        return view('dashboard.employees', compact('employees'));
    }

    public function schedule()
    {
        return view('dashboard.schedule');
    }

    public function payroll()
    {
        return view('dashboard.payroll');
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
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Your profile details have been successfully saved!');
    }
}