<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaves = LeaveRequest::with('user')->latest()->get();
        return view('dashboard.leaves', compact('leaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        auth()->user()->leaveRequests()->create([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }

    public function updateStatus($id, $status)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => $status]);
        return redirect()->back()->with('success', "Leave status updated to {$status}.");
    }
}