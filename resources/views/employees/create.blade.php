@extends('layouts.app')

@section('title', 'Add New Employee | HRMS Admin')

@section('content')


<div class="px-margin-desktop pb-12 pt-8">
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumbs & Title --}}
        <div class="mb-8">
            <nav class="flex text-secondary font-label-md mb-2">
                <a class="hover:text-primary transition-colors" href="{{ route('employees.index') }}">Employees</a>
                <span class="mx-2">/</span>
                <span class="text-on-surface">Add New Employee</span>
            </nav>
            <h1 class="font-display-lg text-display-lg text-on-background">Create Employee Profile</h1>
        </div>

        {{-- Progress Stepper --}}
        <div class="mb-10 relative">
            <div class="flex justify-between relative z-10 max-w-2xl">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">1</div>
                    <span class="font-label-md text-primary">Personal Information</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest text-secondary flex items-center justify-center font-bold">2</div>
                    <span class="font-label-md text-secondary">Contact Details</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest text-secondary flex items-center justify-center font-bold">3</div>
                    <span class="font-label-md text-secondary">Job Details</span>
                </div>
            </div>
            <div class="absolute top-5 left-0 w-full max-w-2xl h-[2px] bg-surface-container-highest -z-0">
                <div class="h-full bg-primary transition-all duration-500" style="width: 0%;"></div>
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

        {{-- Form: Step 1 of 2 --}}
        <form action="{{ route('employees.create.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm overflow-hidden" id="employeeForm">
            @csrf

            <div class="p-8">
                <div class="grid grid-cols-12 gap-8">

                    {{-- Left Column: Profile Photo --}}
                    <div class="col-span-12 md:col-span-4 flex flex-col items-center">
                        <label for="avatar" class="group relative w-40 h-40 rounded-lg bg-surface-container border-2 border-dashed border-outline-variant flex flex-col items-center justify-center overflow-hidden cursor-pointer hover:bg-surface-container-high transition-colors">
                            <div class="text-center p-4" id="imagePlaceholder">
                                <span class="material-symbols-outlined text-outline text-4xl mb-2">add_a_photo</span>
                                <p class="font-label-sm text-outline">Upload Photo</p>
                            </div>
                            <img class="absolute inset-0 w-full h-full object-cover hidden" id="imagePreview" alt="Profile preview" src="">
                        </label>
                        <div class="w-full mt-4">
                            <input
                                class="hidden"
                                id="avatar"
                                name="avatar"
                                type="file"
                                accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                            >
                            @error('avatar')
                                <p class="font-body-sm text-error mt-1 text-center">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 font-body-sm text-secondary text-center">
                                PNG, JPG, or JPEG only. Optional — leave blank to show initials instead.
                            </p>
                        </div>
                    </div>

                    {{-- Right Column: Fields --}}
                    <div class="col-span-12 md:col-span-8 space-y-6">
                        <div class="section-header border-b border-outline-variant pb-2">
                            <h3 class="font-title-lg text-title-lg text-on-surface">Basic Details</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- First Name --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="first_name">First Name</label>
                                <input
                                    class="w-full border @error('first_name') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="e.g. Tevy"
                                    type="text"
                                    value="{{ old('first_name', $old['first_name'] ?? '') }}"
                                    required
                                    autofocus
                                >
                                @error('first_name')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="last_name">Last Name</label>
                                <input
                                    class="w-full border @error('last_name') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="e.g. Sok"
                                    type="text"
                                    value="{{ old('last_name', $old['last_name'] ?? '') }}"
                                    required
                                >
                                @error('last_name')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="date_of_birth">Date of Birth</label>
                                <input
                                    class="w-full border @error('date_of_birth') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                    id="date_of_birth"
                                    name="date_of_birth"
                                    type="date"
                                    value="{{ old('date_of_birth', $old['date_of_birth'] ?? '') }}"
                                    required
                                >
                                @error('date_of_birth')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="gender">Gender</label>
                                <select
                                    class="w-full border @error('gender') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all appearance-none bg-white"
                                    id="gender"
                                    name="gender"
                                    required
                                >
                                    @php $selectedGender = old('gender', $old['gender'] ?? null); @endphp
                                    <option value="" disabled @selected($selectedGender === null)>Select Gender</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender }}" @selected($selectedGender === $gender)>
                                            {{ ucwords(str_replace('_', ' ', $gender)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nationality --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="nationality">Nationality</label>
                                <input
                                    class="w-full border @error('nationality') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                    id="nationality"
                                    name="nationality"
                                    placeholder="e.g. Cambodian"
                                    type="text"
                                    value="{{ old('nationality', $old['nationality'] ?? '') }}"
                                    required
                                >
                                @error('nationality')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Identification ID --}}
                            <div>
                                <label class="block font-body-sm font-bold text-on-surface mb-2" for="identification_id">Identification ID (SSN/Passport)</label>
                                <input
                                    class="w-full border @error('identification_id') border-error @else border-outline-variant @enderror rounded-lg p-3 text-body-md focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all"
                                    id="identification_id"
                                    name="identification_id"
                                    placeholder="000-00-0000"
                                    type="text"
                                    value="{{ old('identification_id', $old['identification_id'] ?? '') }}"
                                    required
                                >
                                @error('identification_id')
                                    <p class="font-body-sm text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="bg-surface-container-low px-8 py-6 flex justify-between items-center border-t border-outline-variant">
                <a href="{{ route('employees.index') }}" class="px-6 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-md hover:bg-surface-container-high transition-colors">
                    Cancel
                </a>
                <button class="px-8 py-2.5 rounded-lg bg-primary text-white font-label-md hover:bg-primary-container transition-all flex items-center gap-2" type="submit">
                    Next Step
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
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
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');

    avatarInput.addEventListener('change', () => {
        const file = avatarInput.files && avatarInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
</script>
@endpush