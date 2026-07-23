@extends('layouts.hr')

@section('title', ($employee['name'] ?? 'Employee') . ' | Profile')

@section('content')
<div class="max-w-[1200px] w-full mx-auto p-margin-desktop space-y-8">

    {{-- Breadcrumbs & Edit Action --}}
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-label-md">
            <a href="{{ route('dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors">Dashboard</a>
            <span class="material-symbols-outlined text-outline-variant scale-75">chevron_right</span>
            <span class="text-on-surface font-bold">Employee Profile</span>
        </nav>
        <a href="{{ route('employee-profile.edit') }}"
           class="flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded font-label-md uppercase tracking-wide hover:opacity-90 active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Edit Profile
        </a>
    </div>

    {{-- Empty state: no profile created yet --}}
    @if (empty($employee))
        <div class="content-card rounded-xl p-8 flex items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-secondary-container">info</span>
                </div>
                <div>
                    <h3 class="font-title-lg text-title-lg text-on-surface">No profile on file yet</h3>
                    <p class="text-body-sm text-on-surface-variant mt-1">Complete the profile creation wizard to fill in your details below.</p>
                </div>
            </div>
            <a href="{{ route('employee-profile.create-personal') }}"
               class="flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md uppercase tracking-wide hover:opacity-90 transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Create Profile
            </a>
        </div>
    @endif

    {{-- 1. Profile Header Card --}}
    <section class="content-card rounded-xl p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 opacity-5 pointer-events-none translate-x-1/4 -translate-y-1/4">
            <span class="material-symbols-outlined text-[256px]">account_circle</span>
        </div>

        <div class="h-32 w-32 rounded-full border-4 border-surface-container overflow-hidden shrink-0 shadow-sm bg-surface-container flex items-center justify-center">
            @if (!empty($employee['avatar_url']))
                <img class="h-full w-full object-cover" src="{{ $employee['avatar_url'] }}" alt="{{ $employee['name'] ?? 'Employee' }}">
            @else
                <span class="material-symbols-outlined text-[64px] text-on-surface-variant">account_circle</span>
            @endif
        </div>

        <div class="flex-grow text-center md:text-left">
            <h1 class="font-display-lg text-display-lg text-on-surface mb-1">{{ $employee['name'] ?? 'Not provided' }}</h1>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                <span class="font-title-lg text-title-lg text-primary">{{ $employee['title'] ?? 'Not provided' }}</span>
                <span class="w-1 h-1 bg-outline-variant rounded-full hidden md:block"></span>
                <span class="flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary rounded-full font-label-md">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    {{ $employee['status'] ?? 'Not provided' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-12 gap-y-2 border-l border-outline-variant pl-8 hidden lg:grid">
            <div>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Employee ID</p>
                <p class="text-body-lg font-bold">{{ $employee['employee_id'] ?? 'Not assigned' }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Location</p>
                <p class="text-body-lg font-bold">{{ $employee['location'] ?? 'Not provided' }}</p>
            </div>
        </div>
    </section>

    {{-- Bento Grid Layout for Information Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- 2. Personal Information --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="content-card rounded-xl p-6 h-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded bg-secondary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-secondary-container">person</span>
                    </div>
                    <h3 class="font-title-lg text-title-lg">Personal Information</h3>
                </div>
                <div class="space-y-5">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Date of Birth</p>
                        <p class="text-body-md font-semibold text-on-surface">
                            @if (!empty($employee['date_of_birth']))
                                {{ \Carbon\Carbon::parse($employee['date_of_birth'])->format('F j, Y') }}
                                ({{ \Carbon\Carbon::parse($employee['date_of_birth'])->age }} years)
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Gender</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['gender'] ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Nationality</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['nationality'] ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Marital Status</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['marital_status'] ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Contact Details --}}
        <div class="lg:col-span-8">
            <div class="content-card rounded-xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded bg-secondary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-secondary-container">contact_mail</span>
                    </div>
                    <h3 class="font-title-lg text-title-lg">Contact Details</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Work Email</p>
                            <div class="flex items-center gap-2">
                                <p class="text-body-md font-semibold text-primary" id="work-email">{{ $employee['work_email'] ?? 'Not provided' }}</p>
                                @if (!empty($employee['work_email']))
                                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('work-email').innerText)" class="p-1 hover:bg-surface-container rounded transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Personal Email</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['personal_email'] ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Mobile Phone</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['mobile_phone'] ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Residential Address</p>
                            <p class="text-body-md font-semibold text-on-surface leading-relaxed">
                                @if (!empty($employee['address_lines']))
                                    {!! implode('<br>', array_map('e', $employee['address_lines'])) !!}
                                @else
                                    Not provided
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Emergency Contact</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['emergency_contact']['name'] ?? 'Not provided' }}</p>
                            @if (!empty($employee['emergency_contact']['phone']))
                                <p class="text-body-sm text-on-surface-variant">{{ $employee['emergency_contact']['phone'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Job Details --}}
        <div class="lg:col-span-12">
            <div class="content-card rounded-xl p-6 overflow-hidden relative">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 rounded bg-secondary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-secondary-container">work</span>
                    </div>
                    <h3 class="font-title-lg text-title-lg">Job Details</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-12">
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Department</p>
                        <p class="text-body-lg font-bold text-on-surface">{{ $employee['department'] ?? 'Not provided' }}</p>
                    </div>
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Employment Type</p>
                        @php
                            $employmentTypes = $employee['employment_type'] ?? [];
                            if (is_string($employmentTypes)) {
                                $employmentTypes = array_filter(explode(',', $employmentTypes));
                            }
                        @endphp
                        @if (!empty($employmentTypes))
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                @foreach ($employmentTypes as $type)
                                    <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-label-sm font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $type }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-body-lg font-bold text-on-surface">Not provided</p>
                        @endif
                    </div>
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Start Date</p>
                        @if (!empty($employee['start_date']))
                            <p class="text-body-lg font-bold text-on-surface">
                                {{ \Carbon\Carbon::parse($employee['start_date'])->format('M j, Y') }}
                            </p>
                            <p class="text-label-sm text-on-surface-variant">
                                {{ \Carbon\Carbon::parse($employee['start_date'])->diffForHumans(null, true) }}
                            </p>
                        @else
                            <p class="text-body-lg font-bold text-on-surface">Not provided</p>
                        @endif
                    </div>
            
                </div>

                
            </div>
        </div>
    </div>

    {{-- Subtle background decoration --}}
    <div class="fixed bottom-0 right-0 p-margin-desktop opacity-5 select-none pointer-events-none">
        <h1 class="text-[120px] font-black leading-none text-outline-variant">PROFILE</h1>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .content-card {
            background-color: theme('colors.surface-container-lowest');
            border: 1px solid theme('colors.outline-variant');
            transition: border-color 0.2s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .content-card:hover {
            border-color: theme('colors.outline');
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Simple micro-interaction for cards
        document.querySelectorAll('.content-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
@endpush