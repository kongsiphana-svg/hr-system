@extends('layouts.dashboard')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0">Dashboard Overview</h3>
    <small class="text-muted">General Summary</small>
</div>

<div class="row g-4 mb-4">
    <!-- Total Employees Box -->
    <div class="col-md-3">
        <div class="stat-card bg-white d-flex flex-column align-items-center justify-content-center text-center py-4">
            <div class="d-flex align-items-center justify-content-center mb-2" style="background: #f1f5f9; width: 48px; height: 48px; border-radius: 8px;">
                <i class="fa-solid fa-users fs-5 text-secondary"></i>
            </div>
            <div class="text-muted small fw-bold mb-1" style="letter-spacing: 0.5px;">TOTAL EMPLOYEES</div>
            <h3 class="fw-bold m-0 text-dark">{{ $metrics['total_employees'] }}</h3>
        </div>
    </div>

    <!-- Pending Leaves Box -->
    <div class="col-md-3">
        <div class="stat-card bg-white d-flex flex-column align-items-center justify-content-center text-center py-4">
            <div class="d-flex align-items-center justify-content-center mb-2" style="background: #fffbeb; width: 48px; height: 48px; border-radius: 8px;">
                <i class="fa-solid fa-hourglass fs-5 text-warning"></i>
            </div>
            <div class="text-muted small fw-bold mb-1" style="letter-spacing: 0.5px;">PENDING LEAVES</div>
            <h3 class="fw-bold m-0 text-dark">{{ $metrics['pending_leaves'] }}</h3>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-1 bg-white">
    <div class="card-header bg-transparent border-bottom p-3 fw-bold">Who's on Leave</div>
    <div class="list-group list-group-flush">
        @forelse($recentLeaves as $leave)
            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-bold text-dark">{{ $leave->user->name }}</div>
                    <small class="text-muted">{{ $leave->start_date }} to {{ $leave->end_date }}</small>
                </div>
                <span class="leave-badge badge-vacation">{{ $leave->leave_type }}</span>
            </div>
        @empty
            <div class="p-4 text-center text-muted">No active leave logs found.</div>
        @endforelse
    </div>
</div>
@endsection