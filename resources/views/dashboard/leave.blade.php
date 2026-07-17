@extends('layouts.dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">Leave Request Board</h4>
    <button class="btn text-white btn-sm px-4 py-2" style="background-color: #3b3db1;" data-bs-toggle="modal" data-bs-target="#leaveModal">Apply Leave</button>
</div>

<div class="card border-0 shadow-sm p-4 bg-white">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $leave)
                <tr>
                    <td class="fw-bold">{{ $leave->user->name }}</td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td><span class="badge bg-secondary opacity-75">{{ $leave->status }}</span></td>
                    <td class="text-end">
                        @if($leave->status === 'Pending')
                            <div class="d-inline-flex gap-1">
                                <form action="{{ route('leaves.status', ['id' => $leave->id, 'status' => 'Approved']) }}" method="POST">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                                <form action="{{ route('leaves.status', ['id' => $leave->id, 'status' => 'Rejected']) }}" method="POST">@csrf<button class="btn btn-sm btn-danger">Reject</button></form>
                            </div>
                        @else
                            <span class="text-muted small">Processed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No leave history records documented.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold">Request Leave Form</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Leave Type</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Personal">Personal Leave</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn text-white" style="background-color: #3b3db1;">File Application</button></div>
            </form>
        </div>
    </div>
</div>
@endsection