@extends('Schedule.layout')

@section('title', 'Work Schedule')
@section('page-title', 'Work Schedule')

@section('schedule')
    @php
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

        $avatarPalette = [
            'bg-indigo-800 text-white',
            'bg-blue-200 text-blue-700',
            'bg-red-800 text-white',
            'bg-slate-200 text-slate-600',
            'bg-emerald-200 text-emerald-800',
        ];
    @endphp

    <div class="p-6 lg:p-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Card --}}
        <section class="relative mb-8 rounded-xl border border-gray-200 bg-white p-8">
            <div class="absolute top-8 right-8">
                <span class="rounded-full {{ $todayCount > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} px-3 py-1 text-xs font-bold uppercase tracking-wider">
                    {{ $todayCount > 0 ? 'Active Today' : 'No Shifts Today' }}
                </span>
            </div>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                @include('Schedule.partials.icons', ['name' => 'clock', 'class' => 'h-6 w-6'])
            </div>
            <h3 class="mb-1 text-xs font-bold uppercase tracking-widest text-slate-400">Today's Active Shifts</h3>
            <p class="text-5xl font-bold text-slate-900">{{ $todayCount }}</p>
        </section>

        {{-- Controls --}}
        <section class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <a
                        href="{{ route('schedule.index', array_filter(['week' => $weekStart->copy()->subWeek()->toDateString(), 'department' => $selectedDepartment])) }}"
                        class="border-r border-gray-200 p-2.5 hover:bg-gray-50"
                        aria-label="Previous week"
                    >
                        @include('Schedule.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4 text-slate-500'])
                    </a>
                    <span class="px-4 py-2 text-sm font-semibold text-slate-700">
                        {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}
                    </span>
                    <a
                        href="{{ route('schedule.index', array_filter(['week' => $weekStart->copy()->addWeek()->toDateString(), 'department' => $selectedDepartment])) }}"
                        class="border-l border-gray-200 p-2.5 hover:bg-gray-50"
                        aria-label="Next week"
                    >
                        @include('Schedule.partials.icons', ['name' => 'chevron-right', 'class' => 'h-4 w-4 text-slate-500'])
                    </a>
                </div>
                <a
                    href="{{ route('schedule.index', array_filter(['week' => now()->toDateString(), 'department' => $selectedDepartment])) }}"
                    class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-gray-200"
                >
                    Today
                </a>
                <a href="{{ route('schedule.calendar', ['week' => $weekStart->toDateString()]) }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition-colors hover:bg-gray-50">
                    Weekly View
                </a>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('schedule.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="week" value="{{ $weekStart->toDateString() }}">
                    <select
                        name="department"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department }}" @selected($selectedDepartment === $department)>{{ $department }}</option>
                        @endforeach
                    </select>
                </form>
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
                    @forelse ($schedules as $schedule)
                        @php
                            $status = $schedule->status ?: 'scheduled';
                            $isConflict = $status === 'conflict';
                            $employee = $schedule->employee;
                            $avatarClass = $avatarPalette[$schedule->id % count($avatarPalette)];
                        @endphp
                        <tr class="transition-colors {{ $isConflict ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}" data-schedule-id="{{ $schedule->id }}">
                            <td class="px-6 py-4 {{ $isConflict ? 'border-l-4 border-red-500' : '' }}">
                                <div class="flex items-center gap-3">
                                    {{-- Employee Avatar / Profile Picture --}}
                                    @if (!empty($employee?->avatar_url))
                                        <img 
                                            src="{{ $employee->avatar_url }}" 
                                            alt="{{ $employee->name }}" 
                                            class="h-10 w-10 shrink-0 rounded-full object-cover border border-slate-200 shadow-sm"
                                        >
                                    @else
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarClass }}">
                                            {{ $employee?->initials() ?? '?' }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $employee?->name ?? 'Unknown employee' }}</p>
                                        <p class="text-xs text-slate-500">{{ $employee?->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $employee?->job_title ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $employee?->department ?? ($schedule->location ?: '—') }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm {{ $isConflict ? 'font-bold text-red-600' : 'text-slate-700' }}">
                                {{ $schedule->date->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block rounded-full border px-4 py-1.5 text-sm font-medium
                                    {{ $isConflict
                                        ? 'border-red-300 bg-red-100 font-bold text-red-600'
                                        : ($status === 'completed'
                                            ? 'border-gray-200 bg-gray-100 text-slate-400'
                                            : 'border-gray-200 bg-gray-100 text-slate-600') }}">
                                    {{ $schedule->start_time_formatted }} - {{ $schedule->end_time_formatted }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">{{ $schedule->hours }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold {{ $statusStyles[$status] ?? $statusStyles['scheduled'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $status === 'clocked_in' ? 'bg-emerald-500' : 'bg-indigo-500' }}"></span>
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    type="button"
                                    class="text-slate-400 hover:text-red-600"
                                    data-delete-schedule="{{ $schedule->id }}"
                                    aria-label="Delete shift"
                                    title="Delete shift"
                                >
                                    @include('Schedule.partials.icons', ['name' => 'x', 'class' => 'h-5 w-5'])
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                No shifts scheduled for this week.
                                <a href="{{ route('schedule.create') }}" class="font-semibold text-indigo-700 hover:underline">Add a shift</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="mt-8 flex items-center justify-between">
            <p class="text-xs font-medium text-slate-500">
                Showing {{ $schedules->firstItem() ?? 0 }}–{{ $schedules->lastItem() ?? 0 }} of {{ $schedules->total() }} shifts
            </p>
            <div>
                {{ $schedules->links() }}
            </div>
        </footer>
    </div>
@endsection