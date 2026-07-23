@extends('layouts.hr')

@section('title', 'Create New Profile - Job Details')

@section('content')
<div class="max-w-[960px] w-full mx-auto px-margin-desktop py-10">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-8 p-4 rounded-lg bg-error-container border border-error text-on-error-container">
            <p class="font-bold text-label-md mb-2">Please correct the following errors:</p>
            <ul class="list-disc list-inside text-body-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Progress Indicator --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary-container text-on-primary-container font-bold text-label-md">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </div>
                <span class="font-label-md text-label-md text-on-surface">Personal Info</span>
            </div>
            <div class="flex-1 h-[2px] bg-primary-container mx-4"></div>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary-container text-on-primary-container font-bold text-label-md">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </div>
                <span class="font-label-md text-label-md text-on-surface">Experience</span>
            </div>
            <div class="flex-1 h-[2px] bg-primary-container mx-4"></div>
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-on-primary font-bold text-label-md border-4 border-primary-fixed">
                    3
                </div>
                <span class="font-label-md text-label-md text-primary font-bold">Job Details</span>
            </div>
            <div class="flex-1 h-[2px] bg-surface-container-highest mx-4"></div>
            <div class="flex items-center gap-3 opacity-40">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-surface-container-highest text-on-surface-variant font-bold text-label-md">
                    4
                </div>
                <span class="font-label-md text-label-md text-on-surface-variant">Review</span>
            </div>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-1">Job Details</h2>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Confirm your employment information to finalize your corporate profile.</p>
    </div>

    <form action="{{ route('employee-profile.job.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-12 gap-gutter">

            {{-- Primary Job Card --}}
            <section class="col-span-12 lg:col-span-8">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">

                        {{-- Department --}}
                        <div class="md:col-span-2">
                            <label for="department" class="block font-body-sm font-bold text-on-surface-variant mb-2">Department</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary pointer-events-none">corporate_fare</span>
                                <select id="department" name="department"
                                        class="w-full pl-10 pr-10 py-3 bg-surface-container rounded-lg border @error('department') border-error @else border-outline-variant/30 @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none font-body-lg text-body-lg font-semibold text-on-surface appearance-none truncate">
                                    @foreach (($departments ?? [
                                        'Engineering / Platform', 'Engineering / Infrastructure', 'Product', 'Design',
                                        'HR', 'Finance', 'Marketing', 'Sales', 'Customer Success', 'Legal', 'Operations',
                                    ]) as $option)
                                        <option value="{{ $option }}" @selected(old('department', $job['department'] ?? 'Engineering / Platform') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                            </div>
                            @error('department')
                                <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Job Title --}}
                        <div>
                            <label for="job_title" class="block font-body-sm font-bold text-on-surface-variant mb-2">Job Title</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary pointer-events-none">badge</span>
                                <input id="job_title" name="job_title" type="text"
                                       value="{{ old('job_title', $job['title'] ?? 'Senior Developer') }}"
                                       placeholder="e.g. Senior Developer"
                                       class="w-full pl-10 pr-4 py-3 bg-surface-container rounded-lg border @error('job_title') border-error @else border-outline-variant/30 @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none font-body-lg text-body-lg font-semibold text-on-surface">
                            </div>
                            @error('job_title')
                                <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block font-body-sm font-bold text-on-surface-variant mb-2">Start Date</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">calendar_today</span>
                                <input id="start_date" name="start_date" type="date"
                                       value="{{ old('start_date', $job['start_date'] ?? '2019-02-12') }}"
                                       class="w-full pl-10 pr-4 py-3 border @error('start_date') border-error @else border-outline-variant @enderror rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none font-body-lg text-body-lg text-on-surface">
                            </div>
                            @error('start_date')
                                <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Employment Type --}}
                        <div class="md:col-span-2">
                            <label class="block font-body-sm font-bold text-on-surface-variant mb-2">Employment Type</label>
                            <div class="flex flex-wrap items-center gap-2">
                                @php
                                    $selectedTypes = old('employment_type', $job['employment_type'] ?? ['Full-time', 'Hybrid']);
                                    if (is_string($selectedTypes)) {
                                        $selectedTypes = explode(',', $selectedTypes);
                                    }
                                @endphp
                                @foreach (['Full-time', 'Part-time', 'Contract', 'Remote', 'Hybrid', 'On-site'] as $option)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="employment_type[]" value="{{ $option }}"
                                               class="peer sr-only"
                                               @checked(in_array($option, $selectedTypes))>
                                        <span class="px-3 py-1 rounded-full text-label-md font-bold border border-outline-variant/50 bg-surface-container text-on-surface-variant peer-checked:bg-primary-fixed peer-checked:text-on-primary-fixed peer-checked:border-primary-fixed transition-colors">
                                            {{ $option }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('employment_type')
                                <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                 
                </div>
            </section>
                   </div>

        {{-- Footer Actions --}}
        <div class="mt-12 flex items-center justify-between border-t border-outline-variant pt-8">
            <a href="{{ route('employee-profile.create-contact') }}"
               class="flex items-center gap-2 px-6 py-3 border border-outline text-on-surface rounded-lg hover:bg-surface-container-low transition-colors font-label-md text-label-md uppercase tracking-widest">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Previous Step
            </a>
            <div class="flex gap-4">
                <button type="submit" id="complete-profile-btn"
                        class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-container shadow-md transition-all font-label-md text-label-md uppercase tracking-widest active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                    Complete Profile
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const completeBtn = document.getElementById('complete-profile-btn');
            if (!completeBtn) return;

            completeBtn.closest('form').addEventListener('submit', function () {
                completeBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Processing...';
                completeBtn.disabled = true;
            });
        });
    </script>
@endpush