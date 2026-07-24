@extends('employee.layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    #reason-editor .ql-editor {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        min-height: 160px;
    }
    #reason-editor .ql-toolbar {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid var(--outline-variant, #c8c4d5);
        background: #fafafa;
    }
    #reason-editor .ql-container {
        border: none;
        font-family: inherit;
    }
</style>
@endpush

@section('title', 'New Leave Request')
@section('page-title', 'New Leave Request')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('employee.leaves') }}" class="inline-flex items-center gap-1.5 text-secondary hover:text-primary transition-colors font-body-md mb-6 no-underline">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to my leaves
    </a>

    <div class="bg-white border border-outline-variant rounded-xl p-6">
        <form action="{{ route('employee.leaves.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                {{-- Leave Type --}}
                <div>
                    <label for="leave_type" class="block font-label-md text-label-md text-secondary mb-1.5">Leave Type</label>
                    <select
                        id="leave_type"
                        name="leave_type"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                        required
                    >
                        <option value="" disabled selected>Select leave type</option>
                        <option value="Annual Leave" @selected(old('leave_type') === 'Annual Leave')>Annual Leave</option>
                        <option value="Sick Leave" @selected(old('leave_type') === 'Sick Leave')>Sick Leave</option>
                        <option value="Personal Leave" @selected(old('leave_type') === 'Personal Leave')>Personal Leave</option>
                        <option value="Maternity Leave" @selected(old('leave_type') === 'Maternity Leave')>Maternity Leave</option>
                        <option value="Paternity Leave" @selected(old('leave_type') === 'Paternity Leave')>Paternity Leave</option>
                        <option value="Bereavement Leave" @selected(old('leave_type') === 'Bereavement Leave')>Bereavement Leave</option>
                        <option value="Other" @selected(old('leave_type') === 'Other')>Other</option>
                    </select>
                    @error('leave_type')
                        <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block font-label-md text-label-md text-secondary mb-1.5">Start Date</label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            required
                        >
                        @error('start_date')
                            <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block font-label-md text-label-md text-secondary mb-1.5">End Date</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            required
                        >
                        @error('end_date')
                            <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Duration Preview --}}
                <div class="p-3 bg-surface-container-low rounded-lg" id="duration-preview">
                    <p class="font-body-sm text-body-sm text-secondary">Select dates to see duration.</p>
                </div>

                {{-- Reason --}}
                <div>
                    <label class="block font-label-md text-label-md text-secondary mb-1.5">Reason <span class="text-secondary/50">(optional)</span></label>
                    <div class="bg-surface-container-low border border-outline-variant rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                        <div id="reason-editor" style="height: 200px;"></div>
                    </div>
                    <textarea name="reason" id="reason" class="hidden">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3 pt-3">
                    <a href="{{ route('employee.leaves') }}" class="px-5 py-2.5 border border-outline-variant rounded-xl text-secondary hover:bg-surface-container-low font-label-md transition-colors no-underline">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl font-label-md text-label-md hover:bg-primary/90 transition-colors">
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Duration preview ──
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const preview = document.getElementById('duration-preview');

    function updateDuration() {
        if (!startInput.value || !endInput.value) {
            preview.innerHTML = '<p class="font-body-sm text-body-sm text-secondary">Select dates to see duration.</p>';
            return;
        }
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        if (end < start) {
            preview.innerHTML = '<p class="font-body-sm text-body-sm text-red-600">End date must be after or equal to start date.</p>';
            return;
        }
        const diffTime = Math.abs(end - start);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const label = diffDays === 1 ? 'day' : 'days';
        preview.innerHTML = `
            <div class="flex items-center justify-between">
                <p class="font-body-md text-body-md text-on-surface">
                    <span class="font-semibold">${diffDays} ${label}</span> of leave
                </p>
                <p class="font-body-sm text-body-sm text-secondary">
                    ${startInput.value} → ${endInput.value}
                </p>
            </div>
        `;
    }

    startInput.addEventListener('change', updateDuration);
    endInput.addEventListener('change', updateDuration);

    // ── Quill Rich Text Editor ──
    const editorEl = document.getElementById('reason-editor');
    const hiddenTextarea = document.getElementById('reason');

    if (editorEl && hiddenTextarea) {
        const quill = new Quill(editorEl, {
            theme: 'snow',
            placeholder: 'Provide a detailed explanation for your leave request...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });

        // Restore previous value if coming back from validation error
        if (hiddenTextarea.value) {
            quill.root.innerHTML = hiddenTextarea.value;
        }

        // Sync Quill content to hidden textarea on form submit
        const form = editorEl.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                hiddenTextarea.value = quill.root.innerHTML;
            });
        }
    }
});
</script>
@endpush
@endsection
