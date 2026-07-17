@extends('Schedule.layout')

@section('title', 'Work Schedule')
@section('page-title', 'Work Schedule')

@section('content')
    @php
        /**
         * Demo data — swap for controller/API payload later.
         * Expected shift fields for backend:
         * id, employee_name, employee_email, role, department, date,
         * start_time, end_time, hours, status
         */
        $shifts = [
            [
                'id' => 1,
                'employee_name' => 'Sarah Miller',
                'employee_email' => 'sarah.m@company.com',
                'initials' => 'SM',
                'avatar' => 'bg-indigo-800 text-white',
                'role' => 'Senior Support Lead',
                'department' => 'Customer Success',
                'date' => 'Oct 16, 2024',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'hours' => '8.0h',
                'status' => 'clocked_in',
            ],
            [
                'id' => 2,
                'employee_name' => 'Jordan Davis',
                'employee_email' => 'j.davis@company.com',
                'initials' => 'JD',
                'avatar' => 'bg-blue-200 text-blue-700',
                'role' => 'Frontend Engineer',
                'department' => 'Engineering',
                'date' => 'Oct 16, 2024',
                'start_time' => '10:00',
                'end_time' => '18:00',
                'hours' => '8.0h',
                'status' => 'scheduled',
            ],
            [
                'id' => 3,
                'employee_name' => 'Lana Cooper',
                'employee_email' => 'lana.c@company.com',
                'initials' => 'LC',
                'avatar' => 'bg-red-800 text-white',
                'role' => 'Operations Analyst',
                'department' => 'Human Resources',
                'date' => 'Oct 16, 2024',
                'start_time' => '08:00',
                'end_time' => '16:00',
                'hours' => '8.0h',
                'status' => 'conflict',
            ],
            [
                'id' => 4,
                'employee_name' => 'Thomas Blair',
                'employee_email' => 'tblair@company.com',
                'initials' => 'TB',
                'avatar' => 'bg-slate-200 text-slate-600',
                'role' => 'HR Manager',
                'department' => 'Human Resources',
                'date' => 'Oct 15, 2024',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'hours' => '8.0h',
                'status' => 'completed',
            ],
            [
                'id' => 5,
                'employee_name' => 'Elena Kozlov',
                'employee_email' => 'elena.k@company.com',
                'initials' => 'EK',
                'avatar' => 'bg-blue-200 text-blue-700',
                'role' => 'Account Executive',
                'department' => 'Sales & Marketing',
                'date' => 'Oct 16, 2024',
                'start_time' => '11:00',
                'end_time' => '19:00',
                'hours' => '8.0h',
                'status' => 'scheduled',
            ],
        ];

        $statusStyles = [
            'clocked_in' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'scheduled' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'conflict' => 'bg-red-100 text-red-800 border-red-200',
            'completed' => 'bg-slate-100 text-slate-500 border-slate-200',
        ];

        $statusLabels = [
            'clocked_in' => 'Clocked In',
            'scheduled' => 'Scheduled',
            'conflict' => 'Conflict',
            'completed' => 'Completed',
        ];
    @endphp

    <div class="p-6 lg:p-8">
        {{-- Summary Card --}}
        <section class="relative mb-8 rounded-xl border border-gray-200 bg-white p-8">
            <div class="absolute top-8 right-8">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-600">On Track</span>
            </div>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                @include('Schedule.partials.icons', ['name' => 'clock', 'class' => 'h-6 w-6'])
            </div>
            <h3 class="mb-1 text-xs font-bold uppercase tracking-widest text-slate-400">Today's Active Shifts</h3>
            <p class="text-5xl font-bold text-slate-900">42</p>
        </section>

        {{-- Controls --}}
        <section class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <button type="button" class="border-r border-gray-200 p-2.5 hover:bg-gray-50" aria-label="Previous week">
                        @include('Schedule.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4 text-slate-500'])
                    </button>
                    <span class="px-4 py-2 text-sm font-semibold text-slate-700">Oct 16 - Oct 22, 2024</span>
                    <button type="button" class="border-l border-gray-200 p-2.5 hover:bg-gray-50" aria-label="Next week">
                        @include('Schedule.partials.icons', ['name' => 'chevron-right', 'class' => 'h-4 w-4 text-slate-500'])
                    </button>
                </div>
                <button type="button" class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-gray-200">
                    Today
                </button>
                <a href="{{ route('schedule.calendar') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition-colors hover:bg-gray-50">
                    Weekly View
                </a>
            </div>

            <div class="flex items-center gap-3">
                <select class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500">
                    <option>All Departments</option>
                    <option>Engineering</option>
                    <option>Customer Success</option>
                    <option>Human Resources</option>
                </select>
                <a
                    href="{{ route('schedule.create') }}"
                    class="flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all"
                    style="background-color: #2a1ab9;"
                    onmouseover="this.style.backgroundColor='#1e118c'"
                    onmouseout="this.style.backgroundColor='#2a1ab9'"
                >
                    @include('Schedule.partials.icons', ['name' => 'plus', 'class' => 'h-4 w-4'])
                    Add New Shift
                </a>
            </div>
        </section>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-gray-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Role &amp; Dept</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Shift Time</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Hours</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($shifts as $shift)
                        @php $isConflict = $shift['status'] === 'conflict'; @endphp
                        <tr class="transition-colors {{ $isConflict ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 {{ $isConflict ? 'border-l-4 border-red-500' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-bold {{ $shift['avatar'] }}">
                                        {{ $shift['initials'] }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $shift['employee_name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $shift['employee_email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $shift['role'] }}</p>
                                <p class="text-xs text-slate-500">{{ $shift['department'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm {{ $isConflict ? 'font-bold text-red-600' : 'text-slate-700' }}">
                                {{ $shift['date'] }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block rounded-full border px-4 py-1.5 text-sm font-medium
                                    {{ $isConflict
                                        ? 'border-red-300 bg-red-100 font-bold text-red-600'
                                        : ($shift['status'] === 'completed'
                                            ? 'border-gray-200 bg-gray-100 text-slate-400'
                                            : 'border-gray-200 bg-gray-100 text-slate-600') }}">
                                    {{ $shift['start_time'] }} - {{ $shift['end_time'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">{{ $shift['hours'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold {{ $statusStyles[$shift['status']] }}">
                                    @if ($shift['status'] === 'conflict')
                                        @include('Schedule.partials.icons', ['name' => 'alert-triangle', 'class' => 'h-3 w-3 text-red-600'])
                                    @elseif ($shift['status'] === 'completed')
                                        @include('Schedule.partials.icons', ['name' => 'check-circle', 'class' => 'h-3 w-3 text-slate-400'])
                                    @else
                                        <span class="h-1.5 w-1.5 rounded-full {{ $shift['status'] === 'clocked_in' ? 'bg-emerald-500' : 'bg-indigo-500' }}"></span>
                                    @endif
                                    {{ $statusLabels[$shift['status']] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button" class="{{ $isConflict ? 'text-red-500 hover:text-red-700' : 'text-slate-400 hover:text-slate-600' }}" aria-label="More options">
                                    @include('Schedule.partials.icons', [
                                        'name' => $isConflict ? 'alert-circle' : 'more-vertical',
                                        'class' => 'h-5 w-5',
                                    ])
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <footer class="mt-8 flex items-center justify-between">
            <p class="text-xs font-medium text-slate-500">Showing 5 of 42 active shifts</p>
            <div class="flex items-center gap-2">
                <button type="button" class="flex h-9 w-9 items-center justify-center rounded border border-gray-200 text-sm font-medium hover:bg-gray-50">1</button>
                <button type="button" class="flex h-9 w-9 items-center justify-center rounded bg-indigo-800 text-sm font-medium text-white">2</button>
                <button type="button" class="flex h-9 w-9 items-center justify-center rounded border border-gray-200 text-sm font-medium hover:bg-gray-50">3</button>
                <span class="px-2 font-bold text-slate-400">...</span>
                <button type="button" class="flex h-9 w-9 items-center justify-center rounded border border-gray-200 text-sm font-medium hover:bg-gray-50">9</button>
            </div>
        </footer>
    </div>
@endsection
