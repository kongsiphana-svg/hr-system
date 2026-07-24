@extends('employee.layout')

@section('title', 'My Leave Requests')
@section('page-title', 'My Leave Requests')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="font-body-md text-body-md text-secondary">Manage your leave requests.</p>
    <a href="{{ route('employee.leaves.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white rounded-xl font-label-md text-label-md hover:bg-primary/90 transition-colors no-underline">
        <span class="material-symbols-outlined text-[18px]">add</span>
        New Request
    </a>
</div>

<div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
    @if ($leaves->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Type</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Dates</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Duration</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Status</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @foreach ($leaves as $leave)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full bg-secondary-container text-secondary font-label-sm text-label-sm">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-body-md text-body-md text-on-surface">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M j, Y') }}
                                @if ($leave->start_date != $leave->end_date)
                                    – {{ \Carbon\Carbon::parse($leave->end_date)->format('M j, Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-4 font-body-md text-body-md text-secondary">
                                @php
                                    $days = \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                                @endphp
                                {{ $days }} {{ $days === 1 ? 'day' : 'days' }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusClass = match(strtolower($leave->status)) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected', 'declined' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full border text-label-sm font-label-sm {{ $statusClass }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-body-sm text-body-sm text-secondary">
                                {{ $leave->created_at->format('M j, Y') }}
                            </td>
                        </tr>
                        @if ($leave->reason)
                        <tr class="bg-surface-container-low/30">
                            <td colspan="5" class="px-5 py-2 font-body-sm text-body-sm text-secondary italic">
                                Note: {!! $leave->reason !!}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-outline-variant">
            {{ $leaves->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <span class="material-symbols-outlined text-[48px] text-secondary/40 mb-4">event_busy</span>
            <p class="font-body-lg text-body-lg text-secondary">No leave requests yet.</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Submit your first leave request to get started.</p>
            <a href="{{ route('employee.leaves.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-primary text-white rounded-xl font-label-md text-label-md hover:bg-primary/90 transition-colors no-underline">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New Request
            </a>
        </div>
    @endif
</div>
@endsection
