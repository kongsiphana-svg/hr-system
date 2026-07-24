@extends('Schedule.layout')

@section('title', 'Add New Shift')
@section('page-title', 'HR Management System')

@section('schedule')
    <div class="flex h-full flex-col">
        <div class="flex-grow overflow-y-auto p-6 lg:p-8">
            <div class="mb-8">
                <nav class="mb-2 flex text-xs font-medium text-gray-500">
                    <a href="{{ route('admin.schedule.index') }}" class="hover:text-blue-600">Scheduling</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800">Add New Shift</span>
                </nav>
                <div class="flex items-baseline justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Add New Shift</h2>
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Shift Configuration</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($employees->isEmpty())
                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    No employees found. <a href="{{ route('admin.employees.create') }}" class="font-semibold underline">Add an employee</a> before creating shifts.
                </div>
            @endif

            <form
                id="create-shift-form"
                class="mx-auto max-w-6xl space-y-6 pb-20"
                action="{{ route('admin.schedules.store') }}"
                method="post"
                data-redirect="{{ route('admin.schedule.index') }}"
            >
                @csrf

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2">
                        @include('Schedule.partials.icons', ['name' => 'user-plus', 'class' => 'h-5 w-5 text-indigo-700'])
                        <h3 class="font-bold text-gray-800">Employee Assignment</h3>
                    </div>
                    <div class="max-w-4xl">
                        <label for="employee_id" class="mb-2 block text-sm font-semibold text-gray-700">Select Employee</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                @include('Schedule.partials.icons', ['name' => 'search', 'class' => 'h-4 w-4 text-gray-400'])
                            </span>
                            <select
                                id="employee_id"
                                name="employee_id"
                                class="block w-full appearance-none rounded-md border-gray-300 bg-white py-2.5 pr-10 pl-10 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="" disabled {{ old('employee_id') ? '' : 'selected' }}>Search employee by name or ID...</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                        {{ $employee->name }} (ID: {{ $employee->id }}){{ $employee->department ? ' — '.$employee->department : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                @include('Schedule.partials.icons', ['name' => 'chevron-down', 'class' => 'h-4 w-4 text-gray-400'])
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2">
                        @include('Schedule.partials.icons', ['name' => 'clock', 'class' => 'h-5 w-5 text-indigo-700'])
                        <h3 class="font-bold text-gray-800">Shift Timing</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                        <div>
                            <label for="date" class="mb-2 block text-sm font-semibold text-gray-700">Date</label>
                            <input
                                id="date"
                                name="date"
                                type="date"
                                value="{{ old('date', now()->toDateString()) }}"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                        </div>
                        <div>
                            <label for="shift_type" class="mb-2 block text-sm font-semibold text-gray-700">Shift Type</label>
                            <select
                                id="shift_type"
                                name="shift_type"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="standard" @selected(old('shift_type', 'standard') === 'standard')>Standard (9:00 - 17:00)</option>
                                <option value="night" @selected(old('shift_type') === 'night')>Night Shift (22:00 - 06:00)</option>
                                <option value="overtime" @selected(old('shift_type') === 'overtime')>Overtime</option>
                                <option value="custom" @selected(old('shift_type') === 'custom')>Custom</option>
                            </select>
                        </div>
                        <div>
                            <label for="start_time" class="mb-2 block text-sm font-semibold text-gray-700">Start Time</label>
                            <input
                                id="start_time"
                                name="start_time"
                                type="time"
                                value="{{ old('start_time', '09:00') }}"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                        </div>
                        <div>
                            <label for="end_time" class="mb-2 block text-sm font-semibold text-gray-700">End Time</label>
                            <input
                                id="end_time"
                                name="end_time"
                                type="time"
                                value="{{ old('end_time', '17:00') }}"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2">
                        @include('Schedule.partials.icons', ['name' => 'map-pin', 'class' => 'h-5 w-5 text-indigo-700'])
                        <h3 class="font-bold text-gray-800">Location &amp; Instructions</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label for="location" class="mb-2 block text-sm font-semibold text-gray-700">Department / Location</label>
                            <select
                                id="location"
                                name="location"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="Main Office - HQ" @selected(old('location') === 'Main Office - HQ')>Main Office - HQ</option>
                                <option value="Warehouse A" @selected(old('location') === 'Warehouse A')>Warehouse A</option>
                                <option value="Remote" @selected(old('location') === 'Remote')>Remote</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Additional Notes</label>
                            <div class="rounded-lg border border-gray-300 overflow-hidden focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all">
                                <div id="create-notes-editor" class="min-h-[150px]"></div>
                            </div>
                            <textarea name="notes" id="create-notes" class="hidden">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <footer class="flex justify-end border-t border-gray-200 bg-white px-8 py-4">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.schedule.index') }}"
                    class="rounded-md border border-gray-200 px-6 py-2.5 text-sm font-semibold text-slate-600 transition-colors hover:bg-gray-50"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    form="create-shift-form"
                    class="flex items-center gap-2 rounded-md px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-colors"
                    style="background-color: #1E1B4B;"
                    onmouseover="this.style.backgroundColor='#2D2A6B'"
                    onmouseout="this.style.backgroundColor='#1E1B4B'"
                    @disabled($employees->isEmpty())
                >
                    Add Shift
                    @include('Schedule.partials.icons', ['name' => 'check-circle', 'class' => 'h-4 w-4'])
                </button>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    document.getElementById('shift_type')?.addEventListener('change', function () {
        const start = document.getElementById('start_time');
        const end = document.getElementById('end_time');
        if (!start || !end) return;

        if (this.value === 'standard') {
            start.value = '09:00';
            end.value = '17:00';
        } else if (this.value === 'night') {
            start.value = '22:00';
            end.value = '06:00';
        }
    });

    // ── Quill Rich Text Editor for Notes ──
    document.addEventListener('DOMContentLoaded', function () {
        const editorEl = document.getElementById('create-notes-editor');
        const hiddenInput = document.getElementById('create-notes');
        const form = document.getElementById('create-shift-form');

        if (editorEl && hiddenInput && form) {
            const quill = new Quill(editorEl, {
                theme: 'snow',
                placeholder: 'Enter any specific instructions or handover notes for this shift...',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            // Restore previous value from validation
            if (hiddenInput.value) {
                quill.root.innerHTML = hiddenInput.value;
            }

            // Sync before submit
            form.addEventListener('submit', function () {
                hiddenInput.value = quill.root.innerHTML;
            });
        }
    });
</script>
@endpush
