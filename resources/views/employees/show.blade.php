@extends('layouts.app')

@section('title', $employee->name . ' | HRMS Admin')

@section('content')


<div class="pb-12">
    <div class="max-w-7xl mx-auto p-margin-desktop">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-label-md text-secondary mb-6">
            <a class="hover:text-primary transition-colors" href="{{ route('admin.employees.index') }}">Employees</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-on-surface font-bold">{{ $employee->name }}</span>
        </nav>

        @if (session('status'))
            <div class="mb-6 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-body-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-6 mb-8">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-surface-container-high shadow-sm">
                    @if ($employee->avatar_url)
                        <img class="w-full h-full object-cover" src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}">
                    @else
                        <div class="w-full h-full bg-secondary-fixed flex items-center justify-center text-primary font-bold text-headline-md">
                            {{ collect([$employee->first_name, $employee->last_name])->map(fn ($p) => strtoupper(substr($p, 0, 1)))->implode('') }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="font-headline-md text-headline-md text-on-surface">{{ $employee->name }}</h2>
                    <div class="flex flex-wrap items-center gap-3 mt-1">
                        @php
                            $statusStyles = [
                                'active'      => 'bg-emerald-50 text-emerald-700',
                                'on_leave'    => 'bg-amber-50 text-amber-700',
                                'probation'   => 'bg-secondary-container text-secondary',
                                'terminated'  => 'bg-error-container text-error',
                            ];
                            $statusDotStyles = [
                                'active'      => 'bg-emerald-500',
                                'on_leave'    => 'bg-amber-500',
                                'probation'   => 'bg-secondary',
                                'terminated'  => 'bg-error',
                            ];
                        @endphp
                        <span class="{{ $statusStyles[$employee->status] ?? 'bg-surface-container text-secondary' }} px-2 py-0.5 rounded text-label-sm font-bold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDotStyles[$employee->status] ?? 'bg-secondary' }}"></span>
                            {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                        </span>
                        <span class="text-secondary font-body-sm">{{ $employee->job_title }}</span>
                        <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                        <span class="text-secondary font-body-sm">Employee ID: #HR-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.employees.create-account', $employee) }}" class="px-4 py-2 bg-white border border-outline-variant text-secondary rounded-lg font-label-md flex items-center gap-2 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Create Account
                </a>
                <a href="{{ route('admin.employees.edit', $employee) }}" class="px-4 py-2 bg-white border border-outline-variant text-secondary rounded-lg font-label-md flex items-center gap-2 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit Details
                </a>
                @if ($employee->status !== 'terminated')
                    <form action="{{ route('admin.employees.deactivate', $employee) }}" method="POST" onsubmit="return confirm('Deactivate {{ $employee->name }}\'s account?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-white border border-error/30 text-error rounded-lg font-label-md flex items-center gap-2 hover:bg-error-container/20 transition-all">
                            <span class="material-symbols-outlined text-[18px]">person_off</span>
                            Deactivate Account
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Personal Info --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <h3 class="font-title-lg text-title-lg text-on-surface">Personal Info</h3>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Full Name</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Date of Birth</dt>
                        <dd class="font-body-md text-on-surface">
                            @if ($employee->date_of_birth)
                                {{ $employee->date_of_birth->format('F j, Y') }} ({{ $employee->date_of_birth->age }} years)
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Gender</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->gender ? ucwords(str_replace('_', ' ', $employee->gender)) : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Nationality</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->nationality ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Identification ID</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->identification_id ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Contact Info --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">contact_mail</span>
                    <h3 class="font-title-lg text-title-lg text-on-surface">Contact Info</h3>
                </div>

                <dl class="space-y-4">
                    {{-- Password Row — always visible --}}
                    <div class="flex items-center justify-between gap-3 p-3 rounded-lg {{ $decryptedPassword ? 'bg-amber-50/80 border-amber-200' : 'bg-surface-container-low border-outline-variant' }} border -mx-1">
                        <div class="flex-1 min-w-0">
                            <dt class="font-label-sm text-secondary uppercase tracking-wider mb-0.5">Password</dt>
                            <dd class="font-body-md flex items-center gap-2">
                                @if ($decryptedPassword)
                                    <span class="font-mono text-amber-800" id="passwordDisplay">••••••••</span>
                                @else
                                    <span class="text-secondary">Not set</span>
                                @endif
                            </dd>
                        </div>
                        @if ($decryptedPassword)
                            <button
                                type="button"
                                id="togglePasswordBtn"
                                class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-amber-300 bg-white text-amber-700 font-label-sm hover:bg-amber-50 transition-all active:scale-95"
                                data-password="{{ $decryptedPassword }}"
                            >
                                <span class="material-symbols-outlined text-[18px]" id="togglePasswordIcon">visibility</span>
                                <span id="togglePasswordLabel">View</span>
                            </button>
                        @else
                            <a href="{{ route('admin.employees.edit', $employee) }}" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant bg-white text-secondary font-label-sm hover:bg-surface-container-low transition-all">
                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                Set Password
                            </a>
                        @endif
                    </div>

                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Work Email</dt>
                        <dd class="font-body-md">
                            <a href="mailto:{{ $employee->email }}" class="text-primary hover:underline">{{ $employee->email }}</a>
                        </dd>
                    </div>
                    @if ($employee->personal_email)
                        <div>
                            <dt class="font-label-sm text-secondary uppercase tracking-wider">Personal Email</dt>
                            <dd class="font-body-md text-on-surface">{{ $employee->personal_email }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Phone Number</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->phone ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider">Home Address</dt>
                        <dd class="font-body-md text-on-surface">
                            @php
                                $addressParts = array_filter([
                                    $employee->address_line_1,
                                    $employee->city,
                                    $employee->state,
                                    $employee->country,
                                ]);
                            @endphp
                            {{ $addressParts ? implode(', ', $addressParts) : '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Job Details --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 lg:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-primary">work</span>
                    <h3 class="font-title-lg text-title-lg text-on-surface">Job Details</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Department</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->department }}</dd>
                    </div>
            
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Hiring Date</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->start_date->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Work Location</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->work_location ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Employment Type</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->employment_type ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Probation Period</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->probation_period ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Pay Type</dt>
                        <dd class="font-body-md text-on-surface">Salary</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Monthly Base Salary</dt>
                        <dd class="font-body-md text-on-surface">${{ number_format((float) ($employee->base_salary ?: $employee->salary), 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Standard Hours</dt>
                        <dd class="font-body-md text-on-surface">{{ $employee->standard_hours ?? 160 }}h / month</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Allowances</dt>
                        <dd class="font-body-md text-on-surface">${{ number_format((float) $employee->allowances, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Deduction Rate</dt>
                        <dd class="font-body-md text-on-surface">{{ number_format(((float) $employee->deduction_rate) * 100, 2) }}%</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-secondary uppercase tracking-wider mb-1">Fixed Deductions</dt>
                        <dd class="font-body-md text-on-surface">${{ number_format((float) $employee->fixed_deductions, 2) }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('togglePasswordBtn');
    const display = document.getElementById('passwordDisplay');
    const icon = document.getElementById('togglePasswordIcon');
    const label = document.getElementById('togglePasswordLabel');

    if (!btn || !display) return;

    const realPassword = btn.dataset.password;
    let visible = false;

    btn.addEventListener('click', function () {
        visible = !visible;

        if (visible) {
            display.textContent = realPassword;
            icon.textContent = 'visibility_off';
            label.textContent = 'Hide';
            btn.classList.remove('border-amber-300', 'bg-white', 'text-amber-700');
            btn.classList.add('border-amber-500', 'bg-amber-100', 'text-amber-800');
        } else {
            display.textContent = '••••••••';
            icon.textContent = 'visibility';
            label.textContent = 'View';
            btn.classList.remove('border-amber-500', 'bg-amber-100', 'text-amber-800');
            btn.classList.add('border-amber-300', 'bg-white', 'text-amber-700');
        }
    });
})();
</script>
@endpush