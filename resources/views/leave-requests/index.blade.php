@extends('layouts.app')

@section('title', 'Leave Requests')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/leave-requests.css', 'resources/js/leave-requests.js'])
    <style>
        .leave-app-shell { display: block; min-height: auto; }
        .leave-sidebar-slot, .leave-navbar-slot { display: none; }
        .leave-page-column { width: 100%; }
        .leave-page { padding-top: 32px; }
        body { background: #f7f9fb; }
        .leave-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .leave-apply-btn {
            border: 0;
            border-radius: 10px;
            background: #334d75;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 10px 16px;
        }
        .leave-apply-btn:hover { background: #2a3f61; color: #fff; }
        .leave-alert {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .leave-alert-success {
            background: #eaf8f3;
            color: #2f8d76;
            border: 1px solid #cdeedf;
        }
        .leave-alert-error {
            background: #fff0f3;
            color: #c94d66;
            border: 1px solid #f5d0d8;
        }
        .action-group form {
            display: inline;
        }
        .details-actions form {
            display: inline;
        }
    </style>
@endpush

@section('content')
<div class="leave-app-shell">
    <div class="leave-page-column">
        <main class="leave-page">
            @if (session('success'))
                <div class="leave-alert leave-alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="leave-alert leave-alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <header class="leave-page-header">
                <div>
                    <h1 class="leave-title">Leave Requests</h1>
                    <p class="leave-subtitle">Manage employee absence, PTO, and medical leave applications.</p>
                </div>

                <div class="leave-header-actions">
                    <nav class="leave-tabs" aria-label="Leave request status filters">
                        <button class="leave-tab {{ ($filter ?? 'pending') === 'pending' ? 'active' : '' }}" type="button" data-leave-filter="pending" aria-pressed="{{ ($filter ?? 'pending') === 'pending' ? 'true' : 'false' }}">Pending</button>
                        <button class="leave-tab {{ ($filter ?? '') === 'approved' ? 'active' : '' }}" type="button" data-leave-filter="approved" aria-pressed="{{ ($filter ?? '') === 'approved' ? 'true' : 'false' }}">Approved</button>
                        <button class="leave-tab {{ ($filter ?? '') === 'history' ? 'active' : '' }}" type="button" data-leave-filter="history" aria-pressed="{{ ($filter ?? '') === 'history' ? 'true' : 'false' }}">History</button>
                    </nav>

                    @auth
                        <button class="leave-apply-btn" type="button" data-bs-toggle="modal" data-bs-target="#leaveModal">
                            Apply Leave
                        </button>
                    @else
                        <a class="leave-apply-btn" href="{{ route('login') }}">Log in to apply</a>
                    @endauth
                </div>
            </header>

            <section class="leave-card" aria-labelledby="recent-requests-title">
                <div class="leave-card-header">
                    <h2 class="leave-card-title" id="recent-requests-title">Recent Requests</h2>
                    <a class="leave-view-all" href="#requests-table">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table leave-table align-middle" id="requests-table">
                        <thead>
                            <tr>
                                <th scope="col">Employee</th>
                                <th scope="col">Type</th>
                                <th scope="col">Dates</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveRequests as $request)
                                <tr
                                    data-leave-request-row
                                    data-status="{{ $request['status'] }}"
                                    data-employee="{{ $request['employee'] }}"
                                    data-note="{{ $request['note'] }}"
                                    data-id="{{ $request['id'] }}"
                                >
                                    <td class="employee-column" data-label="Employee">
                                        <div class="employee-cell">
                                            <span class="employee-avatar" style="--avatar-color: {{ $request['avatar_color'] }}" aria-hidden="true">{{ $request['initials'] }}</span>
                                            <div>
                                                <div class="employee-name">{{ $request['employee'] }}</div>
                                                <div class="employee-role">{{ $request['role'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Type"><span class="leave-type">{{ $request['type'] }}</span></td>
                                    <td data-label="Dates">
                                        <div class="leave-dates">{{ $request['dates'] }}</div>
                                        <div class="date-year">{{ $request['year'] }}</div>
                                    </td>
                                    <td data-label="Duration"><span class="leave-duration">{{ $request['duration'] }}</span></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-{{ $request['status'] }}">{{ $request['status'] }}</span>
                                    </td>
                                    <td data-label="Actions">
                                        @if ($request['status'] === 'pending')
                                            <div class="action-group" aria-label="Actions for {{ $request['employee'] }}">
                                                <form action="{{ route('leaves.status', ['id' => $request['id'], 'status' => 'Approved']) }}" method="POST">
                                                    @csrf
                                                    <button class="action-button action-approve" type="submit" aria-label="Approve {{ $request['employee'] }}">✓</button>
                                                </form>
                                                <form action="{{ route('leaves.status', ['id' => $request['id'], 'status' => 'Rejected']) }}" method="POST">
                                                    @csrf
                                                    <button class="action-button action-decline" type="submit" aria-label="Decline {{ $request['employee'] }}">×</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="processed-label">Processed<br>{{ $request['processed_at'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No leave requests yet.</td>
                                </tr>
                            @endforelse
                            <tr class="leave-empty-row" data-leave-empty hidden>
                                <td colspan="6">No requests match this filter.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="request-details" data-leave-details @if(!$selectedRequest) hidden @endif aria-labelledby="request-details-title">
                <div class="request-details-inner">
                    <div class="details-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6M8 13h8M8 17h8M8 9h2"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="details-title" id="request-details-title" data-leave-details-title>
                            Request Details: {{ $selectedRequest['employee'] ?? '' }}
                        </h2>
                        <blockquote class="details-note" data-leave-details-note>“{{ $selectedRequest['note'] ?? '' }}”</blockquote>
                        <div class="details-actions" data-leave-details-actions @if(($selectedRequest['status'] ?? '') !== 'pending') hidden @endif>
                            <form action="{{ route('leaves.status', ['id' => $selectedRequest['id'] ?? 0, 'status' => 'Rejected']) }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-secondary" type="submit">Decline</button>
                            </form>
                            <form action="{{ route('leaves.status', ['id' => $selectedRequest['id'] ?? 0, 'status' => 'Approved']) }}" method="POST">
                                @csrf
                                <button class="btn btn-approve" type="submit">Approve Request</button>
                            </form>
                        </div>                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

@auth
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="leaveModalLabel">Request Leave Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Leave Type</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation">Vacation / Annual PTO</option>
                            <option value="Medical Leave">Medical Leave</option>
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
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reason / Notes</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Optional details for reviewers"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color: #334d75;">File Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
@endpush
