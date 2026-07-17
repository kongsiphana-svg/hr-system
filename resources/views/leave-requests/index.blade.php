@php
    /*
     * UI-only mock data for testing the page and JavaScript filters.
     * TODO (database integration): Remove this block when real leave requests are
     * passed into the view by the backend implementation.
     */
    $leaveRequests = collect([
        [
            'id' => 1,
            'employee' => 'Alex Thompson',
            'initials' => 'AT',
            'role' => 'Product Designer',
            'type' => 'Annual PTO',
            'dates' => 'Oct 12 – Oct 15',
            'year' => '2026',
            'duration' => '4 Days',
            'status' => 'pending',
            'processed_at' => null,
            'note' => 'I am requesting a 4-day leave to attend a family wedding in Denver. I have coordinated with the design team and Sarah will be covering my active Jira tickets during this period.',
            'avatar_color' => '#475569',
        ],
        [
            'id' => 2,
            'employee' => 'Sarah Jenkins',
            'initials' => 'SJ',
            'role' => 'Sr. Developer',
            'type' => 'Medical Leave',
            'dates' => 'Oct 20',
            'year' => '2026',
            'duration' => '1 Day',
            'status' => 'pending',
            'processed_at' => null,
            'note' => 'I have a scheduled medical appointment and will be unavailable for the day.',
            'avatar_color' => '#7c5c46',
        ],
        [
            'id' => 3,
            'employee' => 'Marcus Chen',
            'initials' => 'MC',
            'role' => 'Financial Analyst',
            'type' => 'Sick Leave',
            'dates' => 'Oct 22 – Oct 24',
            'year' => '2026',
            'duration' => '3 Days',
            'status' => 'approved',
            'processed_at' => 'Oct 10',
            'note' => 'I need time to recover and will keep the finance team updated if anything changes.',
            'avatar_color' => '#315b7d',
        ],
        [
            'id' => 4,
            'employee' => 'Robert Vance',
            'initials' => 'RV',
            'role' => 'Head of Logistics',
            'type' => 'Personal',
            'dates' => 'Nov 01 – Nov 05',
            'year' => '2026',
            'duration' => '5 Days',
            'status' => 'pending',
            'processed_at' => null,
            'note' => 'I am requesting personal leave and have handed off urgent logistics items to the operations team.',
            'avatar_color' => '#596b55',
        ],
        [
            'id' => 5,
            'employee' => 'Elena Garcia',
            'initials' => 'EG',
            'role' => 'People Operations',
            'type' => 'Personal',
            'dates' => 'Sep 18 – Sep 19',
            'year' => '2026',
            'duration' => '2 Days',
            'status' => 'declined',
            'processed_at' => 'Sep 12',
            'note' => 'I requested two personal days and shared coverage notes with the People Operations team.',
            'avatar_color' => '#8b5c72',
        ],
    ]);

    $selectedRequest = $leaveRequests->first();
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Requests | {{ config('app.name', 'HR Portal') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    @vite(['resources/css/leave-requests.css', 'resources/js/leave-requests.js'])
</head>
<body>
    <div class="leave-app-shell">
        <div class="leave-sidebar-slot">
            {{-- SIDEBAR INTEGRATION: Insert the shared sidebar component/layout slot here. --}}
        </div>

        <div class="leave-page-column">
            <div class="leave-navbar-slot">
                <!-- {{-- NAVBAR INTEGRATION: Insert the shared navbar component/layout slot here. --}} -->
            </div>

            <main class="leave-page">
                <header class="leave-page-header">
                    <div>
                        <h1 class="leave-title">Leave Requests</h1>
                        <p class="leave-subtitle">Manage employee absence, PTO, and medical leave applications.</p>
                    </div>

                    <nav class="leave-tabs" aria-label="Leave request status filters">
                        <button class="leave-tab active" type="button" data-leave-filter="pending" aria-pressed="true">Pending</button>
                        <button class="leave-tab" type="button" data-leave-filter="approved" aria-pressed="false">Approved</button>
                        <button class="leave-tab" type="button" data-leave-filter="history" aria-pressed="false">History</button>
                    </nav>
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
                                <!-- TODO (database integration): This loop is ready for LeaveRequest model records. -->
                                @foreach ($leaveRequests as $request)
                                    <tr
                                        data-leave-request-row
                                        data-status="{{ $request['status'] }}"
                                        data-employee="{{ $request['employee'] }}"
                                        data-note="{{ $request['note'] }}"
                                    >
                                    <!--TODO (database integration): Update the row to link to the request details view when a request is selected.-->
                                        <td class="employee-column" data-label="Employee">
                                            <div class="employee-cell">
                                                <!-- TODO (database integration): Replace the initials with an <img> tag for the employee's profile picture when available. -->
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
                                                <!-- TODO (database integration): Connect these controls to approve/decline endpoints. -->
                                                <div class="action-group" aria-label="Actions for {{ $request['employee'] }}">
                                                    <button class="action-button action-approve" type="button" aria-label="Approve {{ $request['employee'] }}">✓</button>
                                                    <button class="action-button action-decline" type="button" aria-label="Decline {{ $request['employee'] }}">×</button>
                                                </div>
                                            @else
                                                <span class="processed-label">Processed<br>{{ $request['processed_at'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="leave-empty-row" data-leave-empty hidden>
                                    <td colspan="6">No requests match this filter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- TODO (database integration): Update this panel when a request is selected. -->
                @if ($selectedRequest)
                    <section class="request-details" data-leave-details aria-labelledby="request-details-title">
                        <div class="request-details-inner">
                            <div class="details-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6M8 13h8M8 17h8M8 9h2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="details-title" id="request-details-title" data-leave-details-title>Request Details: {{ $selectedRequest['employee'] }}</h2>
                                <blockquote class="details-note" data-leave-details-note>“{{ $selectedRequest['note'] }}”</blockquote>
                                <div class="details-actions">
                                    <button class="btn btn-outline-secondary" type="button">Decline with Comment</button>
                                    <button class="btn btn-approve" type="button">Approve Request</button>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
