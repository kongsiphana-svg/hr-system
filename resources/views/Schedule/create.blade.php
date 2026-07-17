@extends('Schedule.layout')

@section('title', 'Add New Shift')
@section('page-title', 'HR Management System')

@section('content')
    <div class="flex h-full flex-col">
        <div class="flex-grow overflow-y-auto p-6 lg:p-8">
            {{-- Breadcrumbs --}}
            <div class="mb-8">
                <nav class="mb-2 flex text-xs font-medium text-gray-500">
                    <a href="{{ route('schedule.index') }}" class="hover:text-blue-600">Scheduling</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800">Add New Shift</span>
                </nav>
                <div class="flex items-baseline justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Add New Shift</h2>
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Step 1 of 2: Shift Configuration</span>
                </div>
            </div>

            <form
                id="create-shift-form"
                class="mx-auto max-w-6xl space-y-6 pb-20"
                action="#"
                method="post"
                data-redirect="{{ route('schedule.index') }}"
            >
                @csrf

                {{-- Employee Assignment --}}
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
                                <option value="" disabled selected>Search employee by name or ID...</option>
                                <option value="1024">John Doe (ID: 1024)</option>
                                <option value="1025">Jane Smith (ID: 1025)</option>
                                <option value="1">Alex Rivera (ID: 1001)</option>
                                <option value="2">Sarah Chen (ID: 1002)</option>
                                <option value="3">Jordan Smith (ID: 1003)</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                @include('Schedule.partials.icons', ['name' => 'chevron-down', 'class' => 'h-4 w-4 text-gray-400'])
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">Start typing to filter employee list.</p>
                    </div>
                </div>

                {{-- Shift Timing --}}
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
                                <option value="standard">Standard (9:00 - 17:00)</option>
                                <option value="night">Night Shift (22:00 - 06:00)</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div>
                            <label for="start_time" class="mb-2 block text-sm font-semibold text-gray-700">Start Time</label>
                            <input
                                id="start_time"
                                name="start_time"
                                type="time"
                                value="09:00"
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
                                value="17:00"
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Location & Instructions --}}
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
                                <option>Main Office - HQ</option>
                                <option>Warehouse A</option>
                                <option>Remote</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="mb-2 block text-sm font-semibold text-gray-700">Additional Notes</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                placeholder="Enter any specific instructions or handover notes for this shift..."
                                class="block w-full rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <footer class="flex justify-end border-t border-gray-200 bg-white px-8 py-4">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('schedule.index') }}"
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
                >
                    Add Shift
                    @include('Schedule.partials.icons', ['name' => 'check-circle', 'class' => 'h-4 w-4'])
                </button>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
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
</script>
@endpush
