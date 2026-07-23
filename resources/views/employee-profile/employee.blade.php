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

    {{-- 1. Profile Header Card --}}
    <section class="content-card rounded-xl p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 opacity-5 pointer-events-none translate-x-1/4 -translate-y-1/4">
            <span class="material-symbols-outlined text-[256px]">account_circle</span>
        </div>

        <div class="h-32 w-32 rounded-full border-4 border-surface-container overflow-hidden shrink-0 shadow-sm">
            <img class="h-full w-full object-cover"
                 src="{{ $employee['avatar_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAFLOGDUL9ljr3nJNMGTySf7W0afelKJkz2H8gZfpWDLwOsFxa9x__aj0q_NcA74HzBnW5EUUrH82uBYZNREM1nc2284KCkzIlL6r1m7Gqyy0uOPTaqXIwmc4lR8g897DYCCknlqa_ppBnEPLa9zZjBelleBsyjzkdpVWzXoSNPUHlhl75b_pVBA8eWjBynsvxcNXb6CNzQ51O0LpSKzDq2PrOChGBYnBVDFDdi6FndcTBIqKGJ6ioP35hFnB6SH0eNjuj3fN2AOfM' }}"
                 alt="{{ $employee['name'] ?? 'Jonathan Sterling' }}">
        </div>

        <div class="flex-grow text-center md:text-left">
            <h1 class="font-display-lg text-display-lg text-on-surface mb-1">{{ $employee['name'] ?? 'Jonathan Sterling' }}</h1>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                <span class="font-title-lg text-title-lg text-primary">{{ $employee['title'] ?? 'Senior Developer' }}</span>
                <span class="w-1 h-1 bg-outline-variant rounded-full hidden md:block"></span>
                <span class="flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary rounded-full font-label-md">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    {{ $employee['status'] ?? 'Active Employee' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-12 gap-y-2 border-l border-outline-variant pl-8 hidden lg:grid">
            <div>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Employee ID</p>
                <p class="text-body-lg font-bold">{{ $employee['employee_id'] ?? 'EMP-90241' }}</p>
            </div>
            <div>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Location</p>
                <p class="text-body-lg font-bold">{{ $employee['location'] ?? 'London, UK' }}</p>
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
                            {{ isset($employee['date_of_birth']) ? \Carbon\Carbon::parse($employee['date_of_birth'])->format('F j, Y') . ' (' . \Carbon\Carbon::parse($employee['date_of_birth'])->age . ' years)' : 'May 14, 1988 (35 years)' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Gender</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['gender'] ?? 'Male' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Nationality</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['nationality'] ?? 'British' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Marital Status</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $employee['marital_status'] ?? 'Married' }}</p>
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
                                <p class="text-body-md font-semibold text-primary" id="work-email">{{ $employee['work_email'] ?? 'j.sterling@enterprise.com' }}</p>
                                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('work-email').innerText)" class="p-1 hover:bg-surface-container rounded transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Personal Email</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['personal_email'] ?? 'jonathan.s.dev88@gmail.com' }}</p>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Mobile Phone</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['mobile_phone'] ?? '+44 (0) 7700 900 123' }}</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Residential Address</p>
                            <p class="text-body-md font-semibold text-on-surface leading-relaxed">
                                @if (!empty($employee['address_lines']))
                                    {!! implode('<br>', array_map('e', $employee['address_lines'])) !!}
                                @else
                                    42 Kensington Gardens<br>
                                    Westminster, London<br>
                                    W8 4PP, United Kingdom
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Emergency Contact</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $employee['emergency_contact']['name'] ?? 'Sarah Sterling (Spouse)' }}</p>
                            <p class="text-body-sm text-on-surface-variant">{{ $employee['emergency_contact']['phone'] ?? '+44 (0) 7700 900 456' }}</p>
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
                        <p class="text-body-lg font-bold text-on-surface">{{ $employee['department'] ?? 'Engineering / Platform' }}</p>
                    </div>
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Employment Type</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <p class="text-body-lg font-bold text-on-surface">{{ $employee['employment_type'] ?? 'Full-time' }}</p>
                        </div>
                    </div>
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Start Date</p>
                        <p class="text-body-lg font-bold text-on-surface">
                            {{ isset($employee['start_date']) ? \Carbon\Carbon::parse($employee['start_date'])->format('M j, Y') : 'Feb 12, 2019' }}
                        </p>
                        <p class="text-label-sm text-on-surface-variant">
                            {{ isset($employee['start_date']) ? \Carbon\Carbon::parse($employee['start_date'])->diffForHumans(null, true) : '4 years, 8 months' }}
                        </p>
                    </div>
                    <div class="border-l-2 border-primary-container pl-4">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">Reporting Manager</p>
                        <div class="flex items-center gap-3 mt-1">
                            <div class="h-8 w-8 rounded-full overflow-hidden shrink-0">
                                <img class="h-full w-full object-cover"
                                     src="{{ $employee['manager']['avatar_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAtY25sTHUqiIubbxg0vzK-b3J4ETyKF9ouDHjJHvjOqy4xaG6jDui04429BYF3w28V1gMbhrV644UAzYMxotvbAhUWGuzoMKngDu6gxeW9OGYonIMkfUl0vZU_fo8YV-X8ue0QfY1K5AHCDHv08MP9pJYaHjlzZPuLsLhe6uiV7c-bodQ6HBwXuyz34lIr-VAltlmPJQhaenZBrzUsJLmvib0xihF9m3YPnGdd6QbR4NiyV3Dq8ItPFH5FzsjVLp07A-Yy7sYmTe8' }}"
                                     alt="{{ $employee['manager']['name'] ?? 'Elena Vance' }}">
                            </div>
                            <div>
                                <p class="text-body-md font-bold text-on-surface leading-none">{{ $employee['manager']['name'] ?? 'Elena Vance' }}</p>
                                <p class="text-label-sm text-primary">{{ $employee['manager']['title'] ?? 'VP of Engineering' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider for secondary job info --}}
                <div class="my-8 h-px bg-outline-variant/30"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse (($employee['groups'] ?? ['Technical Steering Committee']) as $group)
                        <div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant/50">
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Internal Groups</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $group }}</p>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant">Not assigned to any internal groups.</p>
                    @endforelse
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