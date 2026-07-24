@extends('layouts.app')

@section('title', 'Edit ' . $employee->name . ' | HRMS Admin')

@section('content')


<div class="pb-12">
    <div class="max-w-[1200px] mx-auto p-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 mb-4 text-secondary font-label-md text-label-md">
            <a class="hover:text-primary" href="{{ route('admin.employees.index') }}">Employees</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-primary" href="{{ route('admin.employees.show', $employee) }}">{{ $employee->name }}</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-on-surface">Edit Profile</span>
        </nav>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded bg-error-container border border-error/20 text-error font-body-sm">
                <p class="font-bold mb-1">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="bg-primary/10 text-primary px-2 py-0.5 rounded font-label-sm text-label-sm font-bold">RECORD ID: HR-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-secondary font-body-sm text-body-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit_note</span>
                            Editing existing record
                        </span>
                    </div>
                    <h2 class="font-headline-md text-headline-md text-on-surface">{{ $employee->name }}</h2>
                    <p class="text-secondary font-body-md text-body-md">{{ $employee->job_title }} • {{ $employee->department }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.employees.show', $employee) }}" class="px-6 py-2 border border-outline-variant rounded font-label-md text-label-md text-secondary hover:bg-surface-container transition-colors">
                        Discard Changes
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded font-label-md text-label-md hover:bg-primary/90 shadow-sm transition-all transform active:scale-95">
                        Update Record
                    </button>
                </div>
            </div>

            {{-- Edit Dashboard Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Left Column: Profile Summary --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Profile Overview Card --}}
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-6">
                        <div class="flex flex-col items-center text-center">
                            <label for="avatar" class="relative group mb-4 w-32 h-32 cursor-pointer block">
                                @if ($employee->avatar_url)
                                    <img class="w-32 h-32 rounded-full border-4 border-surface-container-low object-cover" id="avatarPreview" src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}">
                                @else
                                    <div class="w-32 h-32 rounded-full border-4 border-surface-container-low bg-secondary-fixed flex items-center justify-center text-primary font-bold text-headline-md" id="avatarPlaceholder">
                                        {{ collect([$employee->first_name, $employee->last_name])->map(fn ($p) => strtoupper(substr($p, 0, 1)))->implode('') }}
                                    </div>
                                    <img class="w-32 h-32 rounded-full border-4 border-surface-container-low object-cover hidden" id="avatarPreview" src="" alt="{{ $employee->name }}">
                                @endif
                                <span class="absolute bottom-1 right-1 bg-primary text-white p-2 rounded-full shadow-lg group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                                </span>
                            </label>
                            <input
                                class="hidden"
                                id="avatar"
                                name="avatar"
                                type="file"
                                accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                            >
                            <h3 class="font-title-lg text-title-lg">{{ $employee->name }}</h3>
                            <p class="text-secondary font-body-sm text-body-sm mb-3">{{ $employee->email }}</p>

                            @error('avatar')
                                <p class="font-body-sm text-error mb-3">{{ $message }}</p>
                            @enderror
                            <p class="font-body-sm text-secondary mb-3">PNG, JPG, or JPEG only.</p>

                            <span class="px-3 py-1 {{ $employee->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-surface-container text-secondary' }} rounded-full font-label-md text-label-md">
                                {{ ucfirst(str_replace('_', ' ', $employee->status)) }} Employee
                            </span>
                        </div>
                        <div class="mt-8 pt-6 border-t border-outline-variant space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-secondary font-label-sm text-label-sm uppercase tracking-wider">Hired On</span>
                                <span class="font-body-md text-body-md font-semibold text-on-surface">{{ $employee->start_date->format('M j, Y') }}</span>
                            </div>
                
                            <div class="flex justify-between items-center">
                                <span class="text-secondary font-label-sm text-label-sm uppercase tracking-wider">Location</span>
                                <span class="font-body-md text-body-md font-semibold text-on-surface">{{ $employee->work_location ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Detailed Information Sections --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Personal Information --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Personal Information</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="first_name">First Name</label>
                                <input class="w-full px-3 py-2 border @error('first_name') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="first_name" name="first_name" type="text" value="{{ old('first_name', $employee->first_name) }}" required>
                                @error('first_name')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="last_name">Last Name</label>
                                <input class="w-full px-3 py-2 border @error('last_name') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="last_name" name="last_name" type="text" value="{{ old('last_name', $employee->last_name) }}" required>
                                @error('last_name')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="date_of_birth">Date of Birth</label>
                                <input class="w-full px-3 py-2 border @error('date_of_birth') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}" required>
                                @error('date_of_birth')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="gender">Gender</label>
                                @php $selectedGender = old('gender', $employee->gender); @endphp
                                <select class="w-full px-3 py-2 border @error('gender') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="gender" name="gender" required>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender }}" @selected($selectedGender === $gender)>{{ ucwords(str_replace('_', ' ', $gender)) }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="nationality">Nationality</label>
                                <input class="w-full px-3 py-2 border @error('nationality') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="nationality" name="nationality" type="text" value="{{ old('nationality', $employee->nationality) }}" required>
                                @error('nationality')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="identification_id">ID / Passport Number</label>
                                <input class="w-full px-3 py-2 border @error('identification_id') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="identification_id" name="identification_id" type="text" value="{{ old('identification_id', $employee->identification_id) }}" required>
                                @error('identification_id')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- Contact Details --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Contact Details</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="email">Work Email</label>
                                    <input class="w-full px-3 py-2 border @error('email') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="email" name="email" type="email" value="{{ old('email', $employee->email) }}" required>
                                    @error('email')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="personal_email">Personal Email</label>
                                    <input class="w-full px-3 py-2 border @error('personal_email') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="personal_email" name="personal_email" type="email" value="{{ old('personal_email', $employee->personal_email) }}">
                                    @error('personal_email')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="phone">Phone Number</label>
                                    <input class="w-full px-3 py-2 border @error('phone') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="phone" name="phone" type="tel" value="{{ old('phone', $employee->phone) }}">
                                    @error('phone')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="city">City/Province</label>
                                    <input class="w-full px-3 py-2 border @error('city') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="city" name="city" type="text" value="{{ old('city', $employee->city) }}">
                                    @error('city')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="address_line_1">Address Line 1</label>
                                    <input class="w-full px-3 py-2 border @error('address_line_1') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="address_line_1" name="address_line_1" type="text" value="{{ old('address_line_1', $employee->address_line_1) }}">
                                    @error('address_line_1')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="address_line_2">Address Line 2</label>
                                    <input class="w-full px-3 py-2 border @error('address_line_2') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="address_line_2" name="address_line_2" type="text" placeholder="Optional" value="{{ old('address_line_2', $employee->address_line_2) }}">
                                    @error('address_line_2')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="country">Country</label>
                                <input class="w-full px-3 py-2 border @error('country') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="country" name="country" type="text" value="{{ old('country', $employee->country) }}">
                                @error('country')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- Professional / Job Details --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Job Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="job_title">Job Title</label>
                                <input class="w-full px-3 py-2 border @error('job_title') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="job_title" name="job_title" type="text" value="{{ old('job_title', $employee->job_title) }}" required>
                                @error('job_title')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="department">Department</label>
                                @php $selectedDepartment = old('department', $employee->department); @endphp
                                <select class="w-full px-3 py-2 border @error('department') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="department" name="department" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department }}" @selected($selectedDepartment === $department)>{{ $department }}</option>
                                    @endforeach
                                </select>
                                @error('department')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="employment_type">Employment Type</label>
                                @php $selectedEmploymentType = old('employment_type', $employee->employment_type); @endphp
                                <select class="w-full px-3 py-2 border @error('employment_type') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="employment_type" name="employment_type" required>
                                    @foreach ($employmentTypes as $type)
                                        <option value="{{ $type }}" @selected($selectedEmploymentType === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('employment_type')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="start_date">Start Date</label>
                                <input class="w-full px-3 py-2 border @error('start_date') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="start_date" name="start_date" type="date" value="{{ old('start_date', optional($employee->start_date)->format('Y-m-d')) }}" required>
                                @error('start_date')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="reporting_manager">Reporting Manager</label>
                                @php $selectedManager = old('reporting_manager', $employee->reporting_manager); @endphp
                                <select class="w-full px-3 py-2 border @error('reporting_manager') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="reporting_manager" name="reporting_manager">
                                    <option value="" @selected($selectedManager === null)>None</option>
                                    @foreach ($reportingManagers as $manager)
                                        @continue($manager === $employee->name)
                                        <option value="{{ $manager }}" @selected($selectedManager === $manager)>{{ $manager }}</option>
                                    @endforeach
                                </select>
                                @error('reporting_manager')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="work_location">Work Location</label>
                                @php $selectedLocation = old('work_location', $employee->work_location); @endphp
                                <select class="w-full px-3 py-2 border @error('work_location') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="work_location" name="work_location" required>
                                    @foreach ($workLocations as $location)
                                        <option value="{{ $location }}" @selected($selectedLocation === $location)>{{ $location }}</option>
                                    @endforeach
                                </select>
                                @error('work_location')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="probation_period">Probation Period</label>
                                @php $selectedProbation = old('probation_period', $employee->probation_period); @endphp
                                <select class="w-full px-3 py-2 border @error('probation_period') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="probation_period" name="probation_period" required>
                                    @foreach ($probationPeriods as $period)
                                        <option value="{{ $period }}" @selected($selectedProbation === $period)>{{ $period }}</option>
                                    @endforeach
                                </select>
                                @error('probation_period')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="status">Status</label>
                                @php $selectedStatus = old('status', $employee->status); @endphp
                                <select class="w-full px-3 py-2 border @error('status') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="status" name="status" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                                @error('status')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- Fixed Working Hours --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Fixed Working Hours &amp; Days</h3>
                            <p class="font-body-sm text-secondary mt-1">Standard daily work schedule — displayed on the schedule timeline.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="fixed_start_time">Start Time</label>
                                <input
                                    class="w-full px-3 py-2 border @error('fixed_start_time') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus"
                                    id="fixed_start_time"
                                    name="fixed_start_time"
                                    type="time"
                                    value="{{ old('fixed_start_time', $employee->fixed_start_time ?? '09:00') }}"
                                >
                                @error('fixed_start_time')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="fixed_end_time">End Time</label>
                                <input
                                    class="w-full px-3 py-2 border @error('fixed_end_time') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus"
                                    id="fixed_end_time"
                                    name="fixed_end_time"
                                    type="time"
                                    value="{{ old('fixed_end_time', $employee->fixed_end_time ?? '18:00') }}"
                                >
                                @error('fixed_end_time')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase block mb-3">Work Days</label>
                            @php
                                $dayLabels = ['Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat', 'Sun' => 'Sun'];
                                $savedDays = is_array($employee->fixed_work_days) ? $employee->fixed_work_days : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                                $oldDays = old('fixed_work_days', $savedDays);
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <label class="relative flex cursor-pointer items-center justify-center rounded-lg border px-3 py-2 transition-all text-sm font-medium
                                        {{ in_array($day, (array) $oldDays) ? 'border-primary bg-primary/5 text-primary' : 'border-outline-variant bg-white text-secondary hover:border-primary/40' }}">
                                        <input
                                            type="checkbox"
                                            name="fixed_work_days[]"
                                            value="{{ $day }}"
                                            {{ in_array($day, (array) $oldDays) ? 'checked' : '' }}
                                            class="sr-only"
                                            onchange="this.parentElement.classList.toggle('border-primary'); this.parentElement.classList.toggle('bg-primary/5'); this.parentElement.classList.toggle('text-primary'); this.parentElement.classList.toggle('border-outline-variant'); this.parentElement.classList.toggle('bg-white'); this.parentElement.classList.toggle('text-secondary');"
                                        >
                                        {{ $dayLabels[$day] }}
                                    </label>
                                @endforeach
                            </div>
                            @error('fixed_work_days')<p class="font-body-sm text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                    </section>

                    {{-- Password Reset --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Reset Password</h3>
                            <p class="font-body-sm text-secondary mt-1">Leave blank to keep the current password.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="password">New Password</label>
                                <input class="w-full px-3 py-2 border @error('password') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="password" name="password" type="password" placeholder="Min. 8 characters">
                                @error('password')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="password_confirmation">Confirm Password</label>
                                <input class="w-full px-3 py-2 border @error('password_confirmation') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="password_confirmation" name="password_confirmation" type="password" placeholder="Repeat the password">
                            </div>
                        </div>
                    </section>

                    {{-- Payroll Compensation --}}
                    <section class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
                        <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                            <h3 class="font-title-lg text-title-lg">Payroll Compensation</h3>
                            <p class="font-body-sm text-secondary mt-1">Used when generating payroll from attendance and approved leave.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            @php
                                $selectedPayType = old('pay_type', $employee->pay_type ?? 'salary');
                                $deductionPercent = old('deduction_percent', round(((float) ($employee->deduction_rate ?? 0.1)) * 100, 2));
                            @endphp

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="pay_type">Pay Type</label>
                                <select class="w-full px-3 py-2 border @error('pay_type') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="pay_type" name="pay_type" required>
                                    @foreach ($payTypes as $type)
                                        <option value="{{ $type }}" @selected($selectedPayType === $type)>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                @error('pay_type')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="standard_hours">Standard Hours / Month</label>
                                <input class="w-full px-3 py-2 border @error('standard_hours') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="standard_hours" name="standard_hours" type="number" min="1" max="744" value="{{ old('standard_hours', $employee->standard_hours ?? 160) }}" required>
                                @error('standard_hours')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="salary">Monthly Base Salary ($)</label>
                                <input class="w-full px-3 py-2 border @error('salary') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="salary" name="salary" type="number" min="0" step="0.01" value="{{ old('salary', $employee->salary ?? $employee->base_salary) }}" required>
                                @error('salary')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="allowances">Monthly Allowances ($)</label>
                                <input class="w-full px-3 py-2 border @error('allowances') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="allowances" name="allowances" type="number" min="0" step="0.01" value="{{ old('allowances', $employee->allowances ?? 0) }}">
                                @error('allowances')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="deduction_percent">Deduction Rate (%)</label>
                                <input class="w-full px-3 py-2 border @error('deduction_percent') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="deduction_percent" name="deduction_percent" type="number" min="0" max="100" step="0.01" value="{{ $deductionPercent }}">
                                @error('deduction_percent')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase" for="fixed_deductions">Fixed Deductions ($)</label>
                                <input class="w-full px-3 py-2 border @error('fixed_deductions') border-error @else border-outline-variant @enderror rounded font-body-md text-body-md input-focus" id="fixed_deductions" name="fixed_deductions" type="number" min="0" step="0.01" value="{{ old('fixed_deductions', $employee->fixed_deductions ?? 0) }}">
                                @error('fixed_deductions')<p class="font-body-sm text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            {{-- Sticky Footer Action Bar --}}
            <div class="mt-12 mb-8 flex justify-end gap-4 border-t border-outline-variant pt-8 items-center">
                <a href="{{ route('admin.employees.show', $employee) }}" class="px-8 py-2.5 border border-outline-variant rounded font-label-md text-label-md text-secondary hover:bg-surface-container transition-colors">
                    Discard Changes
                </a>
                <button type="submit" class="px-10 py-2.5 bg-primary text-white rounded font-label-md text-label-md hover:bg-primary/90 shadow-lg transition-all transform active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live photo preview from the selected file
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');

    avatarInput?.addEventListener('change', () => {
        const file = avatarInput.files && avatarInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush