@extends('layouts.app')

@section('title', 'Leave Requests')

@push('styles')
    @vite(['resources/css/leave-requests.css', 'resources/js/leave-requests.js'])
@endpush

@section('content')
<div class="leave-page">
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


        </div>
    </header>

    <section class="leave-card" aria-labelledby="recent-requests-title">
        <div class="leave-card-header">
            <h2 class="leave-card-title" id="recent-requests-title">Recent Requests</h2>
            <a class="leave-view-all" href="#requests-table">View All</a>
        </div>

        <div class="leave-table-wrap">
            <table class="leave-table" id="requests-table">
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
                                    @if ($request['employee_id'])
                                        <a href="{{ route('admin.employees.show', $request['employee_id']) }}" class="flex items-center gap-3 group">
                                            <span class="employee-avatar" style="--avatar-color: {{ $request['avatar_color'] }}" aria-hidden="true">{{ $request['initials'] }}</span>
                                            <div>
                                                <div class="employee-name group-hover:text-indigo-700 transition-colors">{{ $request['employee'] }}</div>
                                                <div class="employee-role">{{ $request['role'] }}</div>
                                            </div>
                                        </a>
                                    @else
                                        <span class="employee-avatar" style="--avatar-color: {{ $request['avatar_color'] }}" aria-hidden="true">{{ $request['initials'] }}</span>
                                        <div>
                                            <div class="employee-name">{{ $request['employee'] }}</div>
                                            <div class="employee-role">{{ $request['role'] }}</div>
                                        </div>
                                    @endif
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
                                        <form action="{{ route('admin.leaves.status', ['id' => $request['id'], 'status' => 'Approved']) }}" method="POST">
                                            @csrf
                                            <button class="action-button action-approve" type="submit" aria-label="Approve {{ $request['employee'] }}">✓</button>
                                        </form>
                                        <form action="{{ route('admin.leaves.status', ['id' => $request['id'], 'status' => 'Rejected']) }}" method="POST">
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
                            <td colspan="6" class="leave-empty-cell">No leave requests yet.</td>
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
                    <form action="{{ route('admin.leaves.status', ['id' => $selectedRequest['id'] ?? 0, 'status' => 'Rejected']) }}" method="POST">
                        @csrf
                        <button class="leave-btn leave-btn-secondary" type="submit">Decline</button>
                    </form>
                    <form action="{{ route('admin.leaves.status', ['id' => $selectedRequest['id'] ?? 0, 'status' => 'Approved']) }}" method="POST">
                        @csrf
                        <button class="leave-btn leave-btn-primary" type="submit">Approve Request</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@auth
<div class="leave-modal" data-leave-modal hidden>
    <div class="leave-modal-backdrop" data-leave-modal-close></div>
    <div class="leave-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="leaveModalLabel">
        <div class="leave-modal-header">
            <h2 class="leave-modal-title" id="leaveModalLabel">Request Leave Form</h2>
            <button class="leave-modal-close" type="button" data-leave-modal-close aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('admin.leaves.store') }}" method="POST">
            @csrf
            <div class="leave-modal-body">
                <div class="leave-field">
                    <label class="leave-label" for="leave_type">Leave Type</label>
                    <select class="leave-input" id="leave_type" name="leave_type" required>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Vacation">Vacation / Annual PTO</option>
                        <option value="Medical Leave">Medical Leave</option>
                        <option value="Personal">Personal Leave</option>
                    </select>
                </div>
                <div class="leave-field">
                    <label class="leave-label" for="start_date">Start Date</label>
                    <input class="leave-input" id="start_date" type="date" name="start_date" required>
                </div>
                <div class="leave-field">
                    <label class="leave-label" for="end_date">End Date</label>
                    <input class="leave-input" id="end_date" type="date" name="end_date" required>
                </div>
                <div class="leave-field">
                    <label class="leave-label" for="reason">Reason / Notes</label>
                    <textarea class="leave-input" id="reason" name="reason" rows="3" placeholder="Optional details for reviewers"></textarea>
                </div>
            </div>
            <div class="leave-modal-footer">
                <button class="leave-btn leave-btn-secondary" type="button" data-leave-modal-close>Cancel</button>
                <button class="leave-btn leave-btn-primary" type="submit">File Application</button>
            </div>
        </form>
    </div>
</div>
@endauth
@endsection
