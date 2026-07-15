@extends('layouts.app')

@section('title', 'Add New Employee — Contact Details | HRMS Admin')

@section('content')

@include('partials.sidebar')

<main class="ml-[260px] pt-12 pb-12 px-margin-desktop min-h-screen">
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumbs & Title --}}
        <div class="mb-8">
            <nav class="flex text-secondary font-label-md mb-2">
                <a class="hover:text-primary transition-colors" href="{{ route('employees.index') }}">Employees</a>
                <span class="mx-2">/</span>
                <span class="text-on-surface">Add New Employee</span>
            </nav>
            <h1 class="font-display-lg text-display-lg text-on-background">Create Employee Profile</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">
                Adding <span class="font-bold text-on-surface">{{ $step1['first_name'] }} {{ $step1['last_name'] }}</span>
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
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">2</div>
                    <span class="font-label-md text-primary">Contact Details</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest text-secondary flex items-center justify-center font-bold">3</div>
                    <span class="font-label-md text-secondary">Job Details</span>
                </div>
            </div>
            <div class="absolute top-5 left-0 w-full max-w-2xl h-[2px] bg-surface-container-highest -z-0">
                <div class="h-full bg-primary transition-all duration-500" style="width: 50%;"></div>
            </div>
        </div>

        {{-- Form: Step 2 of 3 --}}
        <form action="{{ route('employees.create.contact.store') }}" method="POST" class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm overflow-hidden" id="contactForm">
            @csrf

            <div class="p-8 space-y-8">

                {{-- Contact Details --}}
                <div>
                    <div class="section-header border-b border-outline-variant pb-2 mb-6">
                        <h3 class="font-title-lg text-title-lg text-on-surface">Contact Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Work Email --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="email">Work Email</label>
                            <input
                                class="w-full border @error('email') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="email"
                                name="email"
                                placeholder="j.doe@company.com"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                            @error('email')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Personal Email --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="personal_email">Personal Email</label>
                            <input
                                class="w-full border @error('personal_email') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="personal_email"
                                name="personal_email"
                                placeholder="john.doe@email.com"
                                type="email"
                                value="{{ old('personal_email') }}"
                            >
                            @error('personal_email')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="phone">Phone Number</label>
                            <input
                                class="w-full border @error('phone') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="phone"
                                name="phone"
                                placeholder="012 3456789"
                                type="tel"
                                value="{{ old('phone') }}"
                            >
                            @error('phone')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address Line 1 --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="address_line_1">Address Line 1</label>
                            <input
                                class="w-full border @error('address_line_1') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="address_line_1"
                                name="address_line_1"
                                placeholder="Street address"
                                type="text"
                                value="{{ old('address_line_1') }}"
                            >
                            @error('address_line_1')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address Line 2 --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="address_line_2">Address Line 2</label>
                            <input
                                class="w-full border @error('address_line_2') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="address_line_2"
                                name="address_line_2"
                                placeholder="Apartment, suite, etc. (optional)"
                                type="text"
                                value="{{ old('address_line_2') }}"
                            >
                            @error('address_line_2')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="city">City/Province</label>
                            <input
                                class="w-full border @error('city') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="city"
                                name="city"
                                type="text"
                                value="{{ old('city') }}"
                            >
                            @error('city')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Country --}}
                        <div>
                            <label class="block font-body-sm font-bold text-on-surface mb-2" for="country">Country</label>
                            <input
                                class="w-full border @error('country') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                id="country"
                                name="country"
                                type="text"
                                value="{{ old('country') }}"
                            >
                            @error('country')
                                <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-surface-container-low px-8 py-6 flex justify-between items-center border-t border-outline-variant">
                <a href="{{ route('employees.create') }}" class="px-6 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-md hover:bg-surface-container-high transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back
                </a>
                <button class="px-8 py-2.5 rounded-lg bg-primary text-white font-label-md hover:bg-primary-container transition-all flex items-center gap-2" type="submit">
                    Next Step
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</main>

@endsection