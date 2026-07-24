@extends('Schedule.layout')

@section('title', 'Employee Schedule')
@section('page-title', 'Employee Schedule')

@section('schedule')
    @php
        $shiftColors = [
            'blue' => 'bg-blue-50 border-blue-500 text-blue-800',
            'green' => 'bg-green-50 border-green-500 text-green-800',
            'purple' => 'bg-purple-50 border-purple-500 text-purple-800',
            'orange' => 'bg-orange-50 border-orange-500 text-orange-800',
        ];

        $legend = [
            ['color' => 'bg-blue-500', 'label' => 'Standard / Engineering'],
            ['color' => 'bg-green-500', 'label' => 'Operations'],
            ['color' => 'bg-purple-500', 'label' => 'Night / Support'],
            ['color' => 'bg-orange-500', 'label' => 'Overtime / On-Call'],
        ];

        $dayAbbr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $formatAmPm = function ($time) {
            if (!$time) return '';
            $parts = explode(':', $time);
            $h = (int) $parts[0];
            $m = $parts[1] ?? '00';
            $ampm = $h >= 12 ? 'PM' : 'AM';
            $h12 = $h % 12 ?: 12;
            return $h12 . ':' . $m . ' ' . $ampm;
        };
    @endphp

    <div class="p-6 lg:p-8" style="background-color: #f3f5f9;">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Employee Schedule</h2>
                <p class="mt-1 text-slate-500">
                    {{ $weekStart->format('M j') }} – {{ $weekEnd->format('M j, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.schedule.calendar', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 shadow-sm hover:text-slate-700"
                >
                    ← Prev
                </a>
                <a
                    href="{{ route('admin.schedule.calendar', ['week' => now()->toDateString()]) }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 shadow-sm hover:text-slate-700"
                >
                    Today
                </a>
                <a
                    href="{{ route('admin.schedule.calendar', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 shadow-sm hover:text-slate-700"
                >
                    Next →
                </a>
                <a href="{{ route('admin.schedule.index', ['week' => $weekStart->toDateString()]) }}" class="rounded-lg border border-slate-200 bg-white px-4 py-1.5 text-xs font-semibold text-slate-500 shadow-sm hover:text-slate-700">
                    List View
                </a>
                <button
                    type="button"
                    data-open-shift-modal
                    class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-blue-200 transition-colors hover:bg-blue-700"
                >
                    + Add Shift
                </button>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="grid border-b border-slate-100 bg-slate-50" style="grid-template-columns: 180px repeat(8, 1fr);">
                <div class="border-r border-slate-100 p-4 text-xs font-bold text-slate-500">Employee</div>
                @foreach ($days as $day)
                    <div class="p-4 text-center">
                        <p class="text-[10px] font-bold uppercase text-slate-400">{{ $day['label'] }}</p>
                        <p class="text-xs font-bold text-slate-700">{{ $day['date'] }}</p>
                    </div>
                @endforeach
                <div class="p-4 text-center text-xs font-bold text-slate-400">Actions</div>
            </div>

            @forelse ($employees as $index => $employee)
                @php
                    $hasFixed = $employee['fixed_start_time'] && $employee['fixed_end_time'];
                    $fixedStart = $employee['fixed_start_time'];
                    $fixedEnd = $employee['fixed_end_time'];
                @endphp
                <div class="grid min-h-[90px] {{ $index < count($employees) - 1 ? 'border-b border-slate-100' : '' }}" style="grid-template-columns: 180px repeat(8, 1fr);" data-employee-id="{{ $employee['id'] }}">
                    {{-- Employee Info --}}
                    <a href="{{ route('admin.employees.show', $employee['id']) }}" class="flex items-center space-x-3 border-r border-slate-100 p-3 hover:bg-indigo-50/50 transition-colors group">
                        @if (!empty($employee['avatar']))
                            <img src="{{ $employee['avatar'] }}" alt="{{ $employee['name'] }}" class="h-9 w-9 rounded-full object-cover shrink-0 group-hover:ring-2 group-hover:ring-indigo-300 transition-all">
                        @else
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700 group-hover:ring-2 group-hover:ring-indigo-300 transition-all">
                                {{ $employee['initials'] }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-700 transition-colors">{{ $employee['name'] }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ $employee['department'] }}</p>
                        </div>
                    </a>

                    {{-- 7 Day Cells --}}
                    @for ($dayIndex = 0; $dayIndex < 7; $dayIndex++)
                        <div class="relative p-1.5 border-r border-slate-50 min-h-[85px]">
                            {{-- Assigned shifts --}}
                            @if (isset($employee['shifts'][$dayIndex]))
                                @php $shift = $employee['shifts'][$dayIndex]; @endphp
                                <div class="flex h-full flex-col justify-center rounded-r-md border-l-4 p-1.5 {{ $shiftColors[$shift['color']] ?? $shiftColors['blue'] }} shadow-sm">
                                    <span class="text-[9px] font-bold leading-tight">{{ $shift['start'] }} - {{ $shift['end'] }}</span>
                                    @if ($shift['note'])
                                        <span class="text-[7px] font-medium text-orange-600 leading-tight mt-0.5">{{ \Illuminate\Support\Str::limit($shift['note'], 20) }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endfor

                    {{-- Actions Column --}}
                    <div class="flex items-center justify-center p-2">
                        <button
                            type="button"
                            class="view-timeline-btn rounded-lg bg-indigo-50 px-2.5 py-1.5 text-[10px] font-bold text-indigo-700 border border-indigo-200 transition-all hover:bg-indigo-100 hover:shadow-sm whitespace-nowrap"
                            data-employee-name="{{ $employee['name'] }}"
                            data-employee-dept="{{ $employee['department'] }}"
                            data-employee-initials="{{ $employee['initials'] }}"
                            data-employee-avatar="{{ $employee['avatar'] ?? '' }}"
                            data-fixed-start="{{ $employee['fixed_start_time'] ?? '' }}"
                            data-fixed-end="{{ $employee['fixed_end_time'] ?? '' }}"
                            data-work-days="{{ json_encode($employee['fixed_work_days'] ?? []) }}"
                            data-shifts="{{ json_encode($employee['shifts']) }}"
                            data-week-start="{{ $weekStart->format('Y-m-d') }}"
                            data-day-labels="{{ json_encode($days->pluck('label')) }}"
                            data-day-dates="{{ json_encode($days->pluck('full')) }}"
                        >
                            <svg class="h-3 w-3 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </button>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-sm text-slate-500" style="grid-column: 1 / -1;">
                    No employees yet. <a href="{{ route('admin.employees.create') }}" class="font-semibold text-blue-600 hover:underline">Add employees</a> to build the calendar.
                </div>
            @endforelse
        </div>


    </div>

    {{-- Employee Timeline Modal --}}
    <div id="employeeTimelineModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl overflow-hidden transform transition-all">
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div id="modalAvatar" class="h-12 w-12 rounded-full border-2 border-white/60 bg-white/20 flex items-center justify-center text-lg font-bold text-white shrink-0">
                                EM
                            </div>
                            <div>
                                <h3 id="modalEmployeeName" class="text-lg font-bold text-white">Employee Name</h3>
                                <p id="modalEmployeeDept" class="text-sm text-indigo-100">Department</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeTimelineModal()" class="rounded-full p-1.5 text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div id="modalFixedBadge" class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-white/90 hidden">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="modalFixedText"></span>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto" id="modalTimelineBody">
                    <div id="modalTimelineContent"></div>
                </div>

                {{-- Modal Footer --}}
                <div class="border-t border-slate-100 px-6 py-4 flex justify-end">
                    <button type="button" onclick="closeTimelineModal()"
                        class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('Schedule.partials.add-shift-modal', ['employees' => $allEmployees])
@endpush

@push('scripts')
<script>
(function () {
    const formatAmPm = (time) => {
        if (!time) return '';
        const parts = time.split(':');
        const h = parseInt(parts[0]);
        const m = parts[1] || '00';
        const ampm = h >= 12 ? 'PM' : 'AM';
        const h12 = h % 12 || 12;
        return h12 + ':' + m + ' ' + ampm;
    };

    const dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const dayAbbr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    window.openTimelineModal = function (btn) {
        const name = btn.dataset.employeeName;
        const dept = btn.dataset.employeeDept;
        const initials = btn.dataset.employeeInitials;
        const avatar = btn.dataset.employeeAvatar;
        const fixedStart = btn.dataset.fixedStart;
        const fixedEnd = btn.dataset.fixedEnd;
        const workDays = JSON.parse(btn.dataset.workDays || '[]');
        const shifts = JSON.parse(btn.dataset.shifts || '{}');
        const dayLabelsArr = JSON.parse(btn.dataset.dayLabels || '[]');
        const dayDatesArr = JSON.parse(btn.dataset.dayDates || '[]');

        // Set header info
        document.getElementById('modalEmployeeName').textContent = name;
        document.getElementById('modalEmployeeDept').textContent = dept;
        const avatarEl = document.getElementById('modalAvatar');
        if (avatar) {
            avatarEl.innerHTML = '';
            const img = document.createElement('img');
            img.src = avatar;
            img.alt = name;
            img.className = 'h-12 w-12 rounded-full object-cover';
            avatarEl.appendChild(img);
        } else {
            avatarEl.textContent = initials;
        }

        const fixedBadge = document.getElementById('modalFixedBadge');
        const fixedText = document.getElementById('modalFixedText');
        if (fixedStart && fixedEnd) {
            fixedBadge.classList.remove('hidden');
            fixedText.textContent = 'Fixed Schedule: ' + formatAmPm(fixedStart) + ' — ' + formatAmPm(fixedEnd);
        } else {
            fixedBadge.classList.add('hidden');
        }

        // Build schedule cards layout
        const container = document.getElementById('modalTimelineContent');
        let html = '';

        // Fixed Hours Summary Card
        if (fixedStart && fixedEnd) {
            const workDaysStr = workDays.length > 0
                ? workDays.map(function (d) { return dayLabels[dayAbbr.indexOf(d)] || d; }).join(', ')
                : '—';
            html += '<div class="mb-6 rounded-xl border border-amber-200 bg-amber-50/70 p-5">';
            html += '<div class="flex items-center gap-2 mb-3">';
            html += '<svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">';
            html += '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            html += '</svg>';
            html += '<h4 class="text-sm font-bold text-amber-800">Fixed Working Hours</h4>';
            html += '</div>';
            html += '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">';
            html += '<div class="bg-white rounded-lg border border-amber-100 p-3">';
            html += '<p class="text-[10px] font-bold uppercase tracking-wider text-amber-500 mb-1">Time</p>';
            html += '<p class="text-sm font-bold text-amber-900">' + formatAmPm(fixedStart) + ' — ' + formatAmPm(fixedEnd) + '</p>';
            html += '</div>';
            html += '<div class="bg-white rounded-lg border border-amber-100 p-3">';
            html += '<p class="text-[10px] font-bold uppercase tracking-wider text-amber-500 mb-1">Work Days</p>';
            html += '<p class="text-sm font-bold text-amber-900">' + workDaysStr + '</p>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }

        // Day-by-day schedule cards
        html += '<div class="space-y-3">';
        for (let d = 0; d < 7; d++) {
            const dayAbbrLocal = dayLabelsArr[d] || dayAbbr[d];
            const isWorkDay = workDays.includes(dayAbbrLocal);
            const dayShift = shifts[d];
            const isToday = dayDatesArr[d] === new Date().toISOString().slice(0, 10);
            const dayDate = new Date(dayDatesArr[d]);
            const dateStr = dayDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });

            html += '<div class="rounded-lg border ' + (isToday ? 'border-indigo-200 bg-indigo-50/40' : 'border-slate-200 bg-white') + ' p-4">';

            // Day header
            html += '<div class="flex items-center justify-between mb-3">';
            html += '<div class="flex items-center gap-2">';
            html += '<span class="text-sm font-bold ' + (isToday ? 'text-indigo-700' : 'text-slate-800') + '">' + dayLabels[d] + '</span>';
            html += '<span class="text-xs text-slate-400">' + dateStr + '</span>';
            if (isToday) {
                html += '<span class="text-[9px] font-bold text-white bg-indigo-500 px-2 py-0.5 rounded-full">Today</span>';
            }
            html += '</div>';

            // Day type badge
            if (dayShift) {
                html += '<span class="text-[9px] font-bold px-2 py-0.5 rounded-full ' + (isWorkDay ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') + '">' + (isWorkDay ? 'Scheduled' : 'Extra Shift') + '</span>';
            } else if (isWorkDay) {
                html += '<span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Work Day</span>';
            } else {
                html += '<span class="text-[9px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">Day Off</span>';
            }
            html += '</div>';

            // Schedule cards
            html += '<div class="space-y-2">';

            // Fixed hours block
            if (isWorkDay && fixedStart && fixedEnd) {
                html += '<div class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50/50 px-3 py-2.5">';
                html += '<div class="flex h-7 w-7 items-center justify-center rounded-full bg-amber-100">';
                html += '<svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">';
                html += '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                html += '</svg>';
                html += '</div>';
                html += '<div class="flex-1">';
                html += '<p class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Fixed Hours</p>';
                html += '<p class="text-xs font-bold text-amber-800">' + formatAmPm(fixedStart) + ' — ' + formatAmPm(fixedEnd) + '</p>';
                html += '</div>';
                html += '</div>';
            }

            // Assigned shift block
            if (dayShift) {
                const colorMap = {
                    'blue': { bg: 'bg-blue-500', light: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-700' },
                    'green': { bg: 'bg-green-500', light: 'bg-green-50', border: 'border-green-200', text: 'text-green-700' },
                    'purple': { bg: 'bg-purple-500', light: 'bg-purple-50', border: 'border-purple-200', text: 'text-purple-700' },
                    'orange': { bg: 'bg-orange-500', light: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-700' },
                };
                const c = colorMap[dayShift.color] || colorMap.blue;

                html += '<div class="flex items-center gap-3 rounded-lg border ' + c.border + ' ' + c.light + ' px-3 py-2.5">';
                html += '<div class="flex h-7 w-7 items-center justify-center rounded-full ' + c.bg + '">';
                html += '<svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">';
                html += '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                html += '</svg>';
                html += '</div>';
                html += '<div class="flex-1">';
                html += '<p class="text-[10px] font-bold uppercase tracking-wider ' + c.text + '">Assigned Shift</p>';
                html += '<p class="text-xs font-bold text-slate-800">' + formatAmPm(dayShift.start) + ' — ' + formatAmPm(dayShift.end) + '</p>';
                if (dayShift.note) {
                    html += '<p class="text-[10px] text-slate-500 mt-0.5">' + dayShift.note + '</p>';
                }
                html += '</div>';
                html += '</div>';
            }

            // Empty day
            if (!isWorkDay && !dayShift) {
                html += '<div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2.5">';
                html += '<div class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-200">';
                html += '<svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">';
                html += '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>';
                html += '</svg>';
                html += '</div>';
                html += '<p class="text-xs font-medium text-slate-400">Day off — no fixed hours or shifts</p>';
                html += '</div>';
            }

            html += '</div>'; // end schedule cards
            html += '</div>'; // end day card
        }
        html += '</div>'; // end space-y-3

        container.innerHTML = html;

        // Show modal
        document.getElementById('employeeTimelineModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeTimelineModal = function () {
        document.getElementById('employeeTimelineModal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Click backdrop to close
    document.addEventListener('click', function (e) {
        const modal = document.getElementById('employeeTimelineModal');
        if (e.target === modal || e.target.closest('.fixed.inset-0.bg-black\\/40')) {
            closeTimelineModal();
        }
    });

    // Attach click handlers to View buttons
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.view-timeline-btn');
        if (btn) {
            e.preventDefault();
            openTimelineModal(btn);
        }
    });
})();
</script>
@endpush
