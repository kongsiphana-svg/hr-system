<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $leaves = LeaveRequest::with('user')->latest()->get();

        $leaveRequests = $leaves->map(function (LeaveRequest $leave) {
            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);
            $days = $start->diffInDays($end) + 1;
            $status = $this->normalizeStatus($leave->status);

            $name = $leave->user?->name ?? 'Unknown';
            $parts = preg_split('/\s+/', trim($name)) ?: [];
            $initials = collect($parts)
                ->filter()
                ->take(2)
                ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                ->implode('') ?: '?';

            return [
                'id' => $leave->id,
                'employee' => $name,
                'initials' => $initials,
                'role' => $leave->user?->email ?? '',
                'type' => $leave->leave_type,
                'dates' => $start->equalTo($end)
                    ? $start->format('M j')
                    : $start->format('M j').' – '.$end->format('M j'),
                'year' => $start->format('Y'),
                'duration' => $days.' '.($days === 1 ? 'Day' : 'Days'),
                'status' => $status,
                'processed_at' => $status === 'pending' ? null : optional($leave->updated_at)->format('M j'),
                'note' => $leave->reason ?: 'No additional notes provided.',
                'avatar_color' => $this->avatarColor($leave->id),
                'raw_status' => $leave->status,
            ];
        });

        $selectedRequest = $leaveRequests->firstWhere('status', 'pending') ?? $leaveRequests->first();

        return view('leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'selectedRequest' => $selectedRequest,
            'filter' => $request->query('filter', 'pending'),
        ]);
    }

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->withErrors(['name' => 'Please log in to submit a leave request.']);
        }

        $data = $request->validate([
            'leave_type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        Auth::user()->leaveRequests()->create([
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        $status = ucfirst(strtolower($status));
        if ($status === 'Declined') {
            $status = 'Rejected';
        }

        $request->merge(['status' => $status]);
        $request->validate([
            'status' => ['required', Rule::in(['Approved', 'Rejected', 'Pending'])],
        ]);

        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => $status]);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', "Leave status updated to {$status}.");
    }

    protected function normalizeStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'approved' => 'approved',
            'rejected', 'declined' => 'declined',
            default => 'pending',
        };
    }

    protected function avatarColor(int $id): string
    {
        $palette = ['#475569', '#7c5c46', '#315b7d', '#596b55', '#8b5c72', '#334d75'];

        return $palette[$id % count($palette)];
    }
}
