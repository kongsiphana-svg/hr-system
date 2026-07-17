@extends('Schedule.layout')

@section('title', 'Employee Schedule')
@section('page-title', 'Employee Schedule')

@section('content')
    @php
        /**
         * Demo calendar data — replace with API payload later.
         * Expected fields: employee_name, department, avatar_url, shifts[{day, start, end, color, label}]
         */
        $days = [
            ['label' => 'Mon', 'date' => 'Oct 23'],
            ['label' => 'Tue', 'date' => 'Oct 24'],
            ['label' => 'Wed', 'date' => 'Oct 25'],
            ['label' => 'Thu', 'date' => 'Oct 26'],
            ['label' => 'Fri', 'date' => 'Oct 27'],
            ['label' => 'Sat', 'date' => 'Oct 28'],
            ['label' => 'Sun', 'date' => 'Oct 29'],
        ];

        $employees = [
            [
                'name' => 'Alex Rivera',
                'department' => 'Engineering',
                'avatar' => 'https://i.pravatar.cc/80?img=33',
                'shifts' => [
                    2 => ['start' => '09:00', 'end' => '17:00', 'color' => 'blue', 'note' => null],
                ],
            ],
            [
                'name' => 'Sarah Chen',
                'department' => 'Operations',
                'avatar' => 'https://i.pravatar.cc/80?img=5',
                'shifts' => [
                    2 => ['start' => '08:00', 'end' => '16:00', 'color' => 'green', 'note' => null],
                ],
            ],
            [
                'name' => 'Jordan Smith',
                'department' => 'Support',
                'avatar' => 'https://i.pravatar.cc/80?img=15',
                'shifts' => [
                    2 => ['start' => '14:00', 'end' => '22:00', 'color' => 'purple', 'note' => null],
                    3 => ['start' => '10:00', 'end' => '18:00', 'color' => 'orange', 'note' => 'Weekend OT'],
                ],
            ],
        ];

        $shiftColors = [
            'blue' => 'bg-blue-50 border-blue-500 text-blue-800',
            'green' => 'bg-green-50 border-green-500 text-green-800',
            'purple' => 'bg-purple-50 border-purple-500 text-purple-800',
            'orange' => 'bg-orange-50 border-orange-500 text-orange-800',
        ];

        $coverage = [
            ['label' => 'Engineering', 'percent' => 85, 'color' => 'bg-blue-500'],
            ['label' => 'Operations', 'percent' => 70, 'color' => 'bg-green-500'],
            ['label' => 'Support', 'percent' => 92, 'color' => 'bg-purple-500'],
        ];

        $legend = [
            ['color' => 'bg-blue-500', 'label' => 'Engineering (Standard)'],
            ['color' => 'bg-green-500', 'label' => 'Operations (Day)'],
            ['color' => 'bg-purple-500', 'label' => 'Support (Late)'],
            ['color' => 'bg-orange-500', 'label' => 'On-Call / Weekend'],
        ];
    @endphp

    <div class="p-6 lg:p-8" style="background-color: #f3f5f9;">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Employee Schedule</h2>
                <p class="mt-1 text-slate-500">Manage weekly shifts and department coverage</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('schedule.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-1.5 text-xs font-semibold text-slate-500 shadow-sm hover:text-slate-700">
                    List View
                </a>
                <div class="flex rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
                    <button type="button" class="rounded-md bg-blue-50 px-4 py-1.5 text-xs font-semibold text-blue-600">Weekly</button>
                    <button type="button" class="rounded-md px-4 py-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700">Monthly</button>
                    <button type="button" class="rounded-md px-4 py-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700">Daily</button>
                </div>
                <button
                    type="button"
                    data-open-shift-modal
                    class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-blue-200 transition-colors hover:bg-blue-700"
                >
                    + Add Shift
                </button>
            </div>
        </div>

        {{-- Schedule Grid --}}
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="grid grid-cols-8 border-b border-slate-100 bg-slate-50">
                <div class="border-r border-slate-100 p-4 text-xs font-bold text-slate-500">Employee</div>
                @foreach ($days as $day)
                    <div class="p-4 text-center">
                        <p class="text-[10px] font-bold uppercase text-slate-400">{{ $day['label'] }}</p>
                        <p class="text-xs font-bold text-slate-700">{{ $day['date'] }}</p>
                    </div>
                @endforeach
            </div>

            @foreach ($employees as $index => $employee)
                <div class="grid min-h-[90px] grid-cols-8 {{ $index < count($employees) - 1 ? 'border-b border-slate-100' : '' }}">
                    <div class="flex items-center space-x-3 border-r border-slate-100 p-4">
                        <img src="{{ $employee['avatar'] }}" alt="{{ $employee['name'] }}" class="h-10 w-10 rounded-full object-cover">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $employee['name'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $employee['department'] }}</p>
                        </div>
                    </div>
                    @for ($dayIndex = 0; $dayIndex < 7; $dayIndex++)
                        <div class="p-2">
                            @if (isset($employee['shifts'][$dayIndex]))
                                @php $shift = $employee['shifts'][$dayIndex]; @endphp
                                <div class="flex h-full flex-col justify-center rounded-r-md border-l-4 p-2 {{ $shiftColors[$shift['color']] }}">
                                    <span class="text-[10px] font-bold">{{ $shift['start'] }} - {{ $shift['end'] }}</span>
                                    @if ($shift['note'])
                                        <span class="text-[8px] font-medium text-orange-600">{{ $shift['note'] }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>

        {{-- Widgets --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-sm font-bold text-slate-800">Department Coverage</h3>
                <div class="space-y-4">
                    @foreach ($coverage as $dept)
                        <div>
                            <div class="mb-1 flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500">{{ $dept['label'] }}</span>
                                <span class="text-slate-800">{{ $dept['percent'] }}%</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full {{ $dept['color'] }}" style="width: {{ $dept['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-sm font-bold text-slate-800">Shift Color Legend</h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach ($legend as $item)
                        <div class="flex items-center text-xs font-medium text-slate-500">
                            <span class="mr-3 h-3 w-3 rounded-sm {{ $item['color'] }}"></span>
                            {{ $item['label'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('Schedule.partials.add-shift-modal')
@endpush
