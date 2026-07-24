@extends('layouts.app')

@section('title', 'Add New Employee — Job Details | HRMS Admin')

@section('content')


<div class="px-margin-desktop pb-12 pt-8">
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumbs & Title --}}
        <div class="mb-8">
            <nav class="flex text-secondary font-label-md mb-2">
                <a class="hover:text-primary transition-colors" href="{{ route('admin.employees.index') }}">Employees</a>
                <span class="mx-2">/</span>
                <span class="text-on-surface">Add New Employee</span>
            </nav>
            <h1 class="font-display-lg text-display-lg text-on-background">Create Employee Profile</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">
                Adding <span class="font-bold text-on-surface">{{ $wizard['first_name'] }} {{ $wizard['last_name'] }}</span>
                (<span class="text-on-surface">{{ $wizard['email'] }}</span>)
            </p>
        </div>

        {{-- Progress Stepper --}}
        <div class="mb-10 relative">
            <div class="flex justify-between relative z-10 max-w-2xl">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">check</span>
                    </div>
                    <span class="font-label-md text-primary">Personal Information</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">check</span>
                    </div>
                    <span class="font-label-md text-primary">Contact Details</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">3</div>
                    <span class="font-label-md text-primary">Job Details</span>
                </div>
            </div>
            <div class="absolute top-5 left-0 w-full max-w-2xl h-[2px] bg-surface-container-highest -z-0">
                <div class="h-full bg-primary transition-all duration-500" style="width: 100%;"></div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-error-container border border-error/20 text-error font-body-sm">
                <p class="font-bold mb-1">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form: Step 3 of 3 --}}
        <form action="{{ route('admin.employees.store') }}" method="POST" class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm overflow-hidden" id="jobForm">
            @csrf

            <div class="p-8">
                <div class="space-y-6">
                    <div class="section-header border-b border-outline-variant pb-2">
                        <h3 class="font-title-lg text-title-lg text-on-surface">Job Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Department --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="department">Department</label>
                            <select
                                class="w-full border @error('department') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="department"
                                name="department"
                                required
                            >
                                <option value="" disabled @selected(old('department') === null)>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" @selected(old('department') === $department)>{{ $department }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Job Title --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="job_title">Job Title</label>
                            <input
                                class="w-full border @error('job_title') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="job_title"
                                name="job_title"
                                placeholder="e.g. Senior Software Engineer"
                                type="text"
                                value="{{ old('job_title') }}"
                                required
                            >
                            @error('job_title')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Employment Type --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="employment_type">Employment Type</label>
                            <select
                                class="w-full border @error('employment_type') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="employment_type"
                                name="employment_type"
                                required
                            >
                                <option value="" disabled @selected(old('employment_type') === null)>Select Type</option>
                                @foreach ($employmentTypes as $type)
                                    <option value="{{ $type }}" @selected(old('employment_type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('employment_type')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="start_date">Start Date</label>
                            <input
                                class="w-full border @error('start_date') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="start_date"
                                name="start_date"
                                type="date"
                                value="{{ old('start_date') }}"
                                required
                            >
                            @error('start_date')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Work Location --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="work_location">Work Location</label>
                            <select
                                class="w-full border @error('work_location') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="work_location"
                                name="work_location"
                                required
                            >
                                <option value="" disabled @selected(old('work_location') === null)>Select Location</option>
                                @foreach ($workLocations as $location)
                                    <option value="{{ $location }}" @selected(old('work_location') === $location)>{{ $location }}</option>
                                @endforeach
                            </select>
                            @error('work_location')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Probation Period --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="probation_period">Probation Period</label>
                            <select
                                class="w-full border @error('probation_period') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="probation_period"
                                name="probation_period"
                                required
                            >
                                <option value="" disabled @selected(old('probation_period') === null)>Select Period</option>
                                @foreach ($probationPeriods as $period)
                                    <option value="{{ $period }}" @selected(old('probation_period') === $period)>{{ $period }}</option>
                                @endforeach
                            </select>
                            @error('probation_period')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="status">Status</label>
                            <select
                                class="w-full border @error('status') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="status"
                                name="status"
                                required
                            >
                                <option value="" disabled @selected(old('status') === null)>Select Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status') === $status)>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Fixed Working Hours --}}
                    <div class="section-header border-b border-outline-variant pb-2 pt-4">
                        <h3 class="font-title-lg text-title-lg text-on-surface">Fixed Working Hours</h3>
                        <p class="font-body-sm text-secondary mt-1">Set the employee's standard daily work schedule. Displayed on the schedule page.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="fixed_start_time">Start Time</label>
                            <input
                                class="w-full border @error('fixed_start_time') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="fixed_start_time"
                                name="fixed_start_time"
                                type="time"
                                value="{{ old('fixed_start_time', '09:00') }}"
                            >
                            @error('fixed_start_time')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="fixed_end_time">End Time</label>
                            <input
                                class="w-full border @error('fixed_end_time') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="fixed_end_time"
                                name="fixed_end_time"
                                type="time"
                                value="{{ old('fixed_end_time', '18:00') }}"
                            >
                            @error('fixed_end_time')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block font-body-sm font-bold text-on-surface mb-3">Work Days</label>
                        @php
                            $defaultDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                            $dayLabels = ['Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday', 'Sun' => 'Sunday'];
                            $oldDays = old('fixed_work_days', $defaultDays);
                        @endphp
                        <div class="flex flex-wrap gap-3">
                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <label class="relative flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition-all
                                    {{ in_array($day, (array) $oldDays) ? 'border-primary bg-primary/5 text-primary font-semibold' : 'border-outline-variant bg-white text-secondary hover:border-primary/40 hover:bg-primary/5' }}">
                                    <input
                                        type="checkbox"
                                        name="fixed_work_days[]"
                                        value="{{ $day }}"
                                        {{ in_array($day, (array) $oldDays) ? 'checked' : '' }}
                                        class="sr-only"
                                        onchange="this.parentElement.classList.toggle('border-primary'); this.parentElement.classList.toggle('bg-primary/5'); this.parentElement.classList.toggle('text-primary'); this.parentElement.classList.toggle('font-semibold'); this.parentElement.classList.toggle('border-outline-variant'); this.parentElement.classList.toggle('bg-white'); this.parentElement.classList.toggle('text-secondary'); this.parentElement.classList.toggle('font-normal');"
                                    >
                                    <span class="text-sm">{{ $dayLabels[$day] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('fixed_work_days')
                            <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Login Credentials --}}
                    <div class="section-header border-b border-outline-variant pb-2 pt-4">
                        <h3 class="font-title-lg text-title-lg text-on-surface">Login Credentials</h3>
                        <p class="font-body-sm text-secondary mt-1">Set the employee's initial login password. They can change it later.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="password">Password</label>
                            <input
                                class="w-full border @error('password') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Min. 8 characters"
                                required
                            >
                            @error('password')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="password_confirmation">Confirm Password</label>
                            <input
                                class="w-full border @error('password_confirmation') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Repeat the password"
                                required
                            >
                            @error('password_confirmation')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Payroll Compensation --}}
                    <div class="section-header border-b border-outline-variant pb-2 pt-4">
                        <h3 class="font-title-lg text-title-lg text-on-surface">Payroll Compensation</h3>
                        <p class="font-body-sm text-secondary mt-1">Used when generating payroll from attendance and approved leave.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="pay_type">Pay Type</label>
                            <select
                                class="w-full border @error('pay_type') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                id="pay_type"
                                name="pay_type"
                                required
                            >
                                @foreach ($payTypes as $type)
                                    <option value="{{ $type }}" @selected(old('pay_type', 'salary') === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            @error('pay_type')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="standard_hours">Standard Hours / Month</label>
                            <input
                                class="w-full border @error('standard_hours') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="standard_hours"
                                name="standard_hours"
                                type="number"
                                min="1"
                                max="744"
                                value="{{ old('standard_hours', 160) }}"
                                required
                            >
                            <p class="font-body-sm text-secondary mt-1">Fallback attendance hours and overtime threshold.</p>
                            @error('standard_hours')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div data-pay-field="salary">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="salary">Monthly Base Salary ($)</label>
                            <input
                                class="w-full border @error('salary') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="salary"
                                name="salary"
                                placeholder="e.g. 4500"
                                type="number"
                                min="0"
                                step="0.01"
                                value="{{ old('salary') }}"
                            >
                            <p class="font-body-sm text-secondary mt-1">Monthly amount used as payroll base salary.</p>
                            @error('salary')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div data-pay-field="hourly" class="hidden">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="hourly_rate">Hourly Rate ($)</label>
                            <input
                                class="w-full border @error('hourly_rate') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="hourly_rate"
                                name="hourly_rate"
                                placeholder="e.g. 28.50"
                                type="number"
                                min="0"
                                step="0.01"
                                value="{{ old('hourly_rate') }}"
                            >
                            <p class="font-body-sm text-secondary mt-1">Gross = hourly rate × hours worked.</p>
                            @error('hourly_rate')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="allowances">Monthly Allowances ($)</label>
                            <input
                                class="w-full border @error('allowances') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="allowances"
                                name="allowances"
                                type="number"
                                min="0"
                                step="0.01"
                                value="{{ old('allowances', 0) }}"
                            >
                            @error('allowances')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="deduction_percent">Deduction Rate (%)</label>
                            <input
                                class="w-full border @error('deduction_percent') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="deduction_percent"
                                name="deduction_percent"
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                value="{{ old('deduction_percent', 10) }}"
                            >
                            <p class="font-body-sm text-secondary mt-1">Percent of gross withheld (e.g. 10 = 10%).</p>
                            @error('deduction_percent')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="fixed_deductions">Fixed Deductions ($)</label>
                            <input
                                class="w-full border @error('fixed_deductions') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="fixed_deductions"
                                name="fixed_deductions"
                                type="number"
                                min="0"
                                step="0.01"
                                value="{{ old('fixed_deductions', 0) }}"
                            >
                            @error('fixed_deductions')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-surface-container-low px-8 py-6 flex justify-between items-center border-t border-outline-variant">
                <a href="{{ route('admin.employees.index') }}" class="px-6 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-md hover:bg-surface-container-high transition-colors">
                    Cancel
                </a>
                <div class="flex gap-4">
                    <a href="{{ route('admin.employees.create.contact') }}" class="px-6 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-md hover:bg-surface-container-high transition-colors">
                        Back
                    </a>
                    <button class="px-8 py-2.5 rounded-lg bg-primary text-white font-label-md hover:bg-primary-container transition-all flex items-center gap-2" type="submit">
                        Complete Onboarding
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const payType = document.getElementById('pay_type');
    if (!payType) return;

    const syncPayFields = () => {
        const isHourly = payType.value === 'hourly';
        document.querySelectorAll('[data-pay-field="salary"]').forEach((el) => {
            el.classList.toggle('hidden', isHourly);
            el.querySelector('input')?.toggleAttribute('required', !isHourly);
        });
        document.querySelectorAll('[data-pay-field="hourly"]').forEach((el) => {
            el.classList.toggle('hidden', !isHourly);
            el.querySelector('input')?.toggleAttribute('required', isHourly);
        });
    };

    payType.addEventListener('change', syncPayFields);
    syncPayFields();
})();
</script>
@endpush