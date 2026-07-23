@extends('layouts.hr')

@section('title', 'Edit Profile | Employee Workspace')

@section('content')
<div class="max-w-container-max mx-auto p-margin-desktop">

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

    <form action="{{ route('employee-profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-8" id="editProfileForm">
        @csrf
        @method('PUT')

        {{-- Left Column: Profile Picture & Core Meta --}}
        <div class="col-span-12 lg:col-span-4 space-y-6">

            {{-- Profile Picture Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant p-8 flex flex-col items-center text-center">
                <div class="relative group cursor-pointer" id="photoTrigger">
                    <div class="w-40 h-40 rounded-full border-4 border-surface-container overflow-hidden bg-surface-container flex items-center justify-center">
                        @if (!empty($employee['avatar_url']))
                            <img class="w-full h-full object-cover group-hover:opacity-75 transition-opacity"
                                 id="photoPreview"
                                 src="{{ $employee['avatar_url'] }}"
                                 alt="{{ $employee['name'] ?? 'Employee' }}">
                        @else
                            <span class="material-symbols-outlined text-6xl text-on-surface-variant group-hover:opacity-75 transition-opacity" id="photoPreview">account_circle</span>
                        @endif
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="bg-primary/80 text-white p-3 rounded-full">
                            <span class="material-symbols-outlined text-2xl">photo_camera</span>
                        </div>
                    </div>
                </div>

                <h3 class="mt-6 font-title-lg text-title-lg text-on-surface">{{ $employee['name'] ?? 'Not provided' }}</h3>
                <p class="text-on-surface-variant font-body-md text-body-md mb-6">{{ $employee['title'] ?? 'Not provided' }} &bull; {{ $employee['department'] ?? 'Not provided' }}</p>

                <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/gif" class="sr-only">

                <div class="w-full space-y-2">
                    <label for="avatarInput"
                           class="w-full block py-2 bg-primary text-on-primary font-label-md text-label-md rounded hover:bg-primary/90 transition-colors cursor-pointer">
                        Upload New Photo
                    </label>
                    <button type="button" id="removePhotoBtn"
                            class="w-full py-2 bg-white border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded hover:bg-surface-container-low transition-colors">
                        Remove Photo
                    </button>
                </div>
                @error('avatar')
                    <p class="mt-2 text-label-sm text-error">{{ $message }}</p>
                @enderror
                <p class="mt-4 text-label-sm text-label-sm text-outline">JPG, GIF or PNG.</p>
            </div>

            {{-- Security/Quick Links Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant p-6">
                <h4 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-4 border-b border-outline-variant pb-2">Account Status</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-body-md text-on-surface-variant">Employee ID</span>
                        <span class="text-body-md font-medium text-on-surface">{{ $employee['employee_id'] ?? 'Not assigned' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-body-md text-on-surface-variant">Status</span>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-label-sm font-bold rounded">
                            {{ $employee['status'] ?? 'Active Employee' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Information Forms --}}
        <div class="col-span-12 lg:col-span-8 space-y-8">

            {{-- Section 1: Personal Information --}}
            <section class="bg-surface-container-lowest border border-outline-variant p-8">
                <div class="flex items-center gap-3 mb-8 border-b border-outline-variant pb-4">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Personal Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date_of_birth" class="block font-body-sm text-body-sm font-bold text-on-surface">Date of Birth</label>
                        <input id="date_of_birth" name="date_of_birth" type="date"
                               value="{{ old('date_of_birth', $employee['date_of_birth'] ?? '') }}"
                               class="w-full px-4 py-2 border @error('date_of_birth') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                        @error('date_of_birth')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="gender" class="block font-body-sm text-body-sm font-bold text-on-surface">Gender</label>
                        <select id="gender" name="gender"
                                class="w-full px-4 py-2 border @error('gender') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            <option value="" disabled @selected(empty(old('gender', $employee['gender'] ?? null)))>Select Gender</option>
                            @foreach (['Male', 'Female', 'Non-binary', 'Prefer not to say'] as $option)
                                <option value="{{ $option }}" @selected(old('gender', $employee['gender'] ?? null) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="nationality" class="block font-body-sm text-body-sm font-bold text-on-surface">Nationality</label>
                        <select id="nationality" name="nationality"
                                class="w-full px-4 py-2 border @error('nationality') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            <option value="" disabled @selected(empty(old('nationality', $employee['nationality'] ?? null)))>Select Nationality</option>
                            @foreach (($countries ?? [
                                'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Argentina', 'Armenia', 'Australia', 'Austria',
                                'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin',
                                'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso',
                                'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile',
                                'China', 'Colombia', 'Comoros', 'Congo', 'Congo (DRC)', 'Costa Rica', "Cote d'Ivoire", 'Croatia', 'Cuba',
                                'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt',
                                'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France',
                                'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau',
                                'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq',
                                'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea (North)',
                                'Korea (South)', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya',
                                'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali',
                                'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco',
                                'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands',
                                'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau',
                                'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar',
                                'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines',
                                'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
                                'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa',
                                'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan',
                                'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia',
                                'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom',
                                'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen',
                                'Zambia', 'Zimbabwe',
                            ]) as $option)
                                <option value="{{ $option }}" @selected(old('nationality', $employee['nationality'] ?? null) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('nationality')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="marital_status" class="block font-body-sm text-body-sm font-bold text-on-surface">Marital Status</label>
                        <select id="marital_status" name="marital_status"
                                class="w-full px-4 py-2 border @error('marital_status') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            <option value="" disabled @selected(empty(old('marital_status', $employee['marital_status'] ?? null)))>Select Status</option>
                            @foreach (['Single', 'Married', 'Divorced', 'Widowed'] as $option)
                                <option value="{{ $option }}" @selected(old('marital_status', $employee['marital_status'] ?? null) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('marital_status')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Section 2: Contact Details --}}
            <section class="bg-surface-container-lowest border border-outline-variant p-8">
                <div class="flex items-center gap-3 mb-8 border-b border-outline-variant pb-4">
                    <span class="material-symbols-outlined text-primary">contact_mail</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Contact Details</h3>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="work_email" class="block font-body-sm text-body-sm font-bold text-on-surface">Work Email</label>
                            <div class="relative">
                                <input id="work_email" type="email" disabled
                                       value="{{ $employee['work_email'] ?? 'Not provided' }}"
                                       class="w-full px-4 py-2 bg-surface-container-low border border-outline rounded text-on-surface-variant italic outline-none cursor-not-allowed">
                                <a href="{{ route('employee-profile.request-email-change') }}"
                                   class="mt-2 text-primary font-label-md text-label-md hover:underline flex items-center gap-1">
                                    Request Change
                                </a>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="personal_email" class="block font-body-sm text-body-sm font-bold text-on-surface">Personal Email</label>
                            <input id="personal_email" name="personal_email" type="email"
                                   value="{{ old('personal_email', $employee['personal_email'] ?? '') }}"
                                   class="w-full px-4 py-2 border @error('personal_email') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            @error('personal_email')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="mobile_phone" class="block font-body-sm text-body-sm font-bold text-on-surface">Mobile Phone</label>
                            <input id="mobile_phone" name="mobile_phone" type="tel"
                                   value="{{ old('mobile_phone', $employee['mobile_phone'] ?? '') }}"
                                   class="w-full px-4 py-2 border @error('mobile_phone') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            @error('mobile_phone')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block font-body-sm text-body-sm font-bold text-on-surface">Emergency Contact</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input name="emergency_contact_name" type="text" placeholder="Name"
                                       value="{{ old('emergency_contact_name', $employee['emergency_contact']['name'] ?? '') }}"
                                       class="w-full px-4 py-2 border @error('emergency_contact_name') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                                <input name="emergency_contact_phone" type="tel" placeholder="Phone"
                                       value="{{ old('emergency_contact_phone', $employee['emergency_contact']['phone'] ?? '') }}"
                                       class="w-full px-4 py-2 border @error('emergency_contact_phone') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            </div>
                            @error('emergency_contact_name')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                            @error('emergency_contact_phone')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="address" class="block font-body-sm text-body-sm font-bold text-on-surface">Residential Address</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-4 py-2 border @error('address') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none resize-none">{{ old('address', !empty($employee['address_lines']) ? implode("\n", $employee['address_lines']) : '') }}</textarea>
                        @error('address')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Section 3: Job Details --}}
            <section class="bg-surface-container-lowest border border-outline-variant p-8">
                <div class="flex items-center gap-3 mb-8 border-b border-outline-variant pb-4">
                    <span class="material-symbols-outlined text-primary">work</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Job Details</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="department" class="block font-body-sm text-body-sm font-bold text-on-surface">Department</label>
                        <select id="department" name="department"
                                class="w-full px-4 py-2 border @error('department') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                            @foreach (($departments ?? [
                                'Engineering / Platform', 'Engineering / Infrastructure', 'Product', 'Design',
                                'HR', 'Finance', 'Marketing', 'Sales', 'Customer Success', 'Legal', 'Operations',
                            ]) as $option)
                                <option value="{{ $option }}" @selected(old('department', $employee['department'] ?? null) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('department')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="job_title" class="block font-body-sm text-body-sm font-bold text-on-surface">Job Title</label>
                        <input id="job_title" name="job_title" type="text" placeholder="e.g. Senior Developer"
                               value="{{ old('job_title', $employee['title'] ?? '') }}"
                               class="w-full px-4 py-2 border @error('job_title') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                        @error('job_title')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block font-body-sm text-body-sm font-bold text-on-surface">Employment Type</label>
                        @php
                            $editSelectedTypes = old('employment_type', $employee['employment_type'] ?? []);
                            if (is_string($editSelectedTypes)) {
                                $editSelectedTypes = array_filter(explode(',', $editSelectedTypes));
                            }
                        @endphp
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach (['Full-time', 'Part-time', 'Contract', 'Remote', 'Hybrid', 'On-site'] as $option)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="employment_type[]" value="{{ $option }}"
                                           class="peer sr-only"
                                           @checked(in_array($option, $editSelectedTypes))>
                                    <span class="px-3 py-1 rounded-full text-label-md font-bold border border-outline bg-white text-on-surface-variant peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-colors">
                                        {{ $option }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('employment_type')
                            <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="start_date" class="block font-body-sm text-body-sm font-bold text-on-surface">Start Date</label>
                        <input id="start_date" name="start_date" type="date"
                               value="{{ old('start_date', $employee['start_date'] ?? '') }}"
                               class="w-full px-4 py-2 border @error('start_date') border-error @else border-outline @enderror rounded focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all outline-none">
                        @error('start_date')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-4 mt-12 pt-8 border-t border-outline-variant">
                <a href="{{ route('employee-profile.show') }}"
                   class="px-8 py-3 bg-white border border-outline-variant text-on-surface-variant font-label-md text-label-md hover:bg-surface-container-low transition-colors rounded">
                    Cancel
                </a>
                <button type="submit"
                        class="px-10 py-3 bg-primary text-on-primary font-label-md text-label-md shadow-sm hover:bg-primary/90 transition-all rounded">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Floating Toast Notification (Hidden by default) --}}
<div class="fixed bottom-8 right-8 bg-tertiary text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 translate-y-20 transition-transform duration-300 opacity-0 pointer-events-none" id="saveToast">
    <span class="material-symbols-outlined text-emerald-400">check_circle</span>
    <p class="font-body-md text-body-md">Profile changes saved successfully.</p>
    <button type="button" class="text-on-tertiary-container hover:text-white transition-colors" id="closeToastBtn">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
@endsection

@push('styles')
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            const toast = document.getElementById('saveToast');

            function showToast() {
                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-y-0', 'opacity-100');
                setTimeout(hideToast, 4000);
            }

            function hideToast() {
                toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }

            document.getElementById('closeToastBtn').addEventListener('click', hideToast);

            @if (session('success'))
                showToast();
            @endif

            // Live preview when a new photo is selected
            const avatarInput = document.getElementById('avatarInput');
            const photoPreview = document.getElementById('photoPreview');
            const removeAvatarField = document.getElementById('removeAvatarField');

            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (event) => {
                    if (photoPreview.tagName === 'IMG') {
                        photoPreview.src = event.target.result;
                    } else {
                        // Currently showing the fallback icon; swap it for a real <img>
                        const img = document.createElement('img');
                        img.id = 'photoPreview';
                        img.className = 'w-full h-full object-cover group-hover:opacity-75 transition-opacity';
                        img.src = event.target.result;
                        photoPreview.replaceWith(img);
                    }
                };
                reader.readAsDataURL(file);
            });

            // Remove photo: clear the file input and flag removal on submit
            document.getElementById('removePhotoBtn').addEventListener('click', () => {
                avatarInput.value = '';
                if (!document.getElementById('removeAvatarField')) {
                    const marker = document.createElement('input');
                    marker.type = 'hidden';
                    marker.id = 'removeAvatarField';
                    marker.name = 'remove_avatar';
                    marker.value = '1';
                    document.getElementById('editProfileForm').appendChild(marker);
                }
            });
        })();
    </script>
@endpush