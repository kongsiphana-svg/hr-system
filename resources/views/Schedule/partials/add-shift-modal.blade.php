{{-- Add New Shift Modal (from design message (2).txt) --}}
<div
    id="add-shift-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(2px);"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="add-shift-modal-title"
>
    <div class="w-full max-w-md overflow-hidden rounded-xl bg-white shadow-2xl" data-modal-panel>
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 id="add-shift-modal-title" class="text-lg font-bold text-slate-800">Add New Shift</h3>
            <button type="button" data-close-shift-modal class="text-slate-400 hover:text-slate-600" aria-label="Close">
                @include('Schedule.partials.icons', ['name' => 'x', 'class' => 'h-5 w-5'])
            </button>
        </div>

        <form id="add-shift-form" class="space-y-5 p-6" action="#" method="post">
            @csrf
            <div>
                <label for="modal-employee" class="mb-2 block text-xs font-bold text-slate-700">Select Employee</label>
                <div class="relative">
                    <select
                        id="modal-employee"
                        name="employee_id"
                        class="w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2.5 pr-10 pl-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="1">Alex Rivera</option>
                        <option value="2">Sarah Chen</option>
                        <option value="3">Jordan Smith</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                        @include('Schedule.partials.icons', ['name' => 'chevron-down', 'class' => 'h-4 w-4 text-slate-400'])
                    </div>
                </div>
            </div>

            <div>
                <label for="modal-date" class="mb-2 block text-xs font-bold text-slate-700">Choose Date</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        @include('Schedule.partials.icons', ['name' => 'schedule', 'class' => 'h-4 w-4 text-slate-400'])
                    </span>
                    <input
                        id="modal-date"
                        name="date"
                        type="date"
                        value="2023-10-25"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pr-3 pl-10 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="modal-start" class="mb-2 block text-xs font-bold text-slate-700">Set Start Time</label>
                    <input
                        id="modal-start"
                        name="start_time"
                        type="time"
                        value="09:00"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="modal-end" class="mb-2 block text-xs font-bold text-slate-700">Set End Time</label>
                    <input
                        id="modal-end"
                        name="end_time"
                        type="time"
                        value="17:00"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div>
                <label for="modal-shift-type" class="mb-2 block text-xs font-bold text-slate-700">Shift Type</label>
                <div class="relative">
                    <select
                        id="modal-shift-type"
                        name="shift_type"
                        class="w-full appearance-none rounded-lg border border-slate-200 bg-slate-50 py-2.5 pr-10 pl-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option>Standard</option>
                        <option>Overtime</option>
                        <option>Night Shift</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                        @include('Schedule.partials.icons', ['name' => 'chevron-down', 'class' => 'h-4 w-4 text-slate-400'])
                    </div>
                </div>
            </div>
        </form>

        <div class="flex space-x-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
            <button
                type="button"
                data-close-shift-modal
                class="flex-1 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50"
            >
                Cancel
            </button>
            <button
                type="submit"
                form="add-shift-form"
                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-md shadow-blue-200 transition-shadow hover:bg-blue-700"
            >
                Create Shift
            </button>
        </div>
    </div>
</div>
