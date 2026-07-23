@extends('layouts.hr')

@section('title', 'Create New Profile - Personal Information')

@section('content')

    <div class="p-8 flex-1 flex flex-col items-center">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="w-full max-w-4xl mb-6 p-4 rounded-lg bg-error-container border border-error text-on-error-container">
                <p class="font-bold text-label-md mb-2">Please correct the following errors:</p>
                <ul class="list-disc list-inside text-body-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Progress Indicator Section --}}
        <div class="w-full max-w-4xl mb-12">
            <div class="flex justify-between items-start">

                {{-- Step 1: Personal Information (active) --}}
                <div class="flex-1 flex flex-col items-center group">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary mb-3 shadow-md">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: &quot;FILL&quot; 1;">person</span>
                    </div>
                    <p class="font-label-md text-label-md text-primary font-bold">Step 1</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Personal Information</p>
                </div>

                <div class="mt-5 flex-1 h-[2px] bg-surface-container-highest relative mx-4">
                    <div class="absolute left-0 top-0 h-full bg-primary w-full"></div>
                </div>

                {{-- Step 2: Contact Details --}}
                <div class="flex-1 flex flex-col items-center group opacity-50">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined text-[20px]">contact_page</span>
                    </div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Step 2</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Contact Details</p>
                </div>

                <div class="mt-5 flex-1 h-[2px] bg-surface-container-highest relative mx-4"></div>

                {{-- Step 3: Job Assignment --}}
                <div class="flex-1 flex flex-col items-center group opacity-50">
                    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined text-[20px]">work</span>
                    </div>
                    <p class="font-label-md text-label-md text-on-surface-variant">Step 3</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Job Assignment</p>
                </div>
            </div>
        </div>

        {{-- Form Container Card --}}
        <div class="w-full max-w-3xl bg-surface-container-lowest border border-outline-variant/30 rounded-lg p-8">
            <div class="mb-8 pb-6 border-b border-surface-container-highest">
                <h1 class="font-headline-md text-headline-md text-on-surface">Personal Information</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Please provide your official identification details to complete your employee profile.</p>
            </div>

            <form action="{{ route('employee-profile.personal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Profile Picture Upload --}}
                <div class="flex items-center gap-8 group">
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full border-2 border-dashed border-outline-variant flex items-center justify-center bg-surface-container overflow-hidden group-hover:border-primary transition-colors">
                            @if (old('profile_picture_preview'))
                                <img src="{{ old('profile_picture_preview') }}" class="w-full h-full object-cover" alt="Profile picture preview">
                            @else
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:text-primary transition-colors">add_a_photo</span>
                            @endif
                        </div>
                        <label for="profile_picture" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-lg hover:bg-primary-container transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </label>
                        <input id="profile_picture" name="profile_picture" type="file" accept="image/png, image/jpeg, image/gif" class="sr-only">
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface">Profile Picture</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">PNG, JPG or GIF.</p>
                        @error('profile_picture')
                            <p class="text-label-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 flex gap-2">
                            <label for="profile_picture" class="px-4 py-1.5 font-label-md text-label-md border border-outline-variant rounded-lg hover:bg-surface-container transition-colors cursor-pointer">Upload New</label>
                            <button type="button" onclick="document.getElementById('profile_picture').value=''" class="px-4 py-1.5 font-label-md text-label-md text-error hover:bg-error-container/10 rounded-lg transition-colors">Remove</button>
                        </div>
                    </div>
                </div>

                {{-- Input Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Full Name --}}
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="full_name" class="block font-body-sm text-body-sm font-bold text-on-surface">Full Name (As per Passport/ID)</label>
                        <input
                            id="full_name"
                            name="full_name"
                            type="text"
                            value="{{ old('full_name') }}"
                            class="w-full px-4 py-2.5 border @error('full_name') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all"
                            placeholder="e.g. Jonathan Alexander Smith">
                        @error('full_name')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date of Birth --}}
                    <div class="space-y-1.5">
                        <label for="date_of_birth" class="block font-body-sm text-body-sm font-bold text-on-surface">Date of Birth</label>
                        <div class="relative">
                            <input
                                id="date_of_birth"
                                name="date_of_birth"
                                type="date"
                                value="{{ old('date_of_birth') }}"
                                class="w-full px-4 py-2.5 border @error('date_of_birth') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all">
                        </div>
                        @error('date_of_birth')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div class="space-y-1.5">
                        <label for="gender" class="block font-body-sm text-body-sm font-bold text-on-surface">Gender</label>
                        <select
                            id="gender"
                            name="gender"
                            class="w-full px-4 py-2.5 border @error('gender') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all bg-transparent">
                            <option value="" disabled @selected(old('gender') === null)>Select Gender</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="non-binary" @selected(old('gender') === 'non-binary')>Non-binary</option>
                            <option value="prefer-not-to-say" @selected(old('gender') === 'prefer-not-to-say')>Prefer not to say</option>
                        </select>
                        @error('gender')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nationality --}}
                    <div class="space-y-1.5">
                        <label for="nationality" class="block font-body-sm text-body-sm font-bold text-on-surface">Nationality</label>
                        <select
                            id="nationality"
                            name="nationality"
                            class="w-full px-4 py-2.5 border @error('nationality') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all bg-transparent">
                            <option value="" disabled @selected(old('nationality') === null)>Select Country</option>
                            @foreach (($countries ?? [
                                'AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AD' => 'Andorra', 'AO' => 'Angola',
                                'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AU' => 'Australia', 'AT' => 'Austria',
                                'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados',
                                'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BT' => 'Bhutan',
                                'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BR' => 'Brazil', 'BN' => 'Brunei',
                                'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon',
                                'CA' => 'Canada', 'CV' => 'Cape Verde', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile',
                                'CN' => 'China', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo (DRC)',
                                'CR' => 'Costa Rica', 'CI' => "Cote d'Ivoire", 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus',
                                'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic',
                                'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea',
                                'EE' => 'Estonia', 'SZ' => 'Eswatini', 'ET' => 'Ethiopia', 'FJ' => 'Fiji', 'FI' => 'Finland',
                                'FR' => 'France', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany',
                                'GH' => 'Ghana', 'GR' => 'Greece', 'GD' => 'Grenada', 'GT' => 'Guatemala', 'GN' => 'Guinea',
                                'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HN' => 'Honduras', 'HK' => 'Hong Kong',
                                'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran',
                                'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica',
                                'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati',
                                'KP' => 'Korea (North)', 'KR' => 'Korea (South)', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos',
                                'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya',
                                'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macau', 'MG' => 'Madagascar',
                                'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta',
                                'MH' => 'Marshall Islands', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'MX' => 'Mexico', 'FM' => 'Micronesia',
                                'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MA' => 'Morocco',
                                'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal',
                                'NL' => 'Netherlands', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria',
                                'MK' => 'North Macedonia', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau',
                                'PS' => 'Palestine', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru',
                                'PH' => 'Philippines', 'PL' => 'Poland', 'PT' => 'Portugal', 'QA' => 'Qatar', 'RO' => 'Romania',
                                'RU' => 'Russia', 'RW' => 'Rwanda', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'VC' => 'Saint Vincent and the Grenadines',
                                'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal',
                                'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia',
                                'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'SS' => 'South Sudan',
                                'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SE' => 'Sweden',
                                'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania',
                                'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago',
                                'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TV' => 'Tuvalu', 'UG' => 'Uganda',
                                'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay',
                                'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City', 'VE' => 'Venezuela', 'VN' => 'Vietnam',
                                'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
                            ]) as $code => $name)
                                <option value="{{ $name }}" @selected(old('nationality') === $name)>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('nationality')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Identification Type --}}
                    <div class="space-y-1.5">
                        <label for="id_type" class="block font-body-sm text-body-sm font-bold text-on-surface">Identification Type</label>
                        <select
                            id="id_type"
                            name="id_type"
                            class="w-full px-4 py-2.5 border @error('id_type') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all bg-transparent">
                            <option value="passport" @selected(old('id_type', 'passport') === 'passport')>Passport</option>
                            <option value="national_id" @selected(old('id_type') === 'national_id')>National ID Card</option>
                            <option value="driver_license" @selected(old('id_type') === 'driver_license')>Driver's License</option>
                        </select>
                        @error('id_type')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Marital Status --}}
                    <div class="space-y-1.5">
                        <label for="marital_status" class="block font-body-sm text-body-sm font-bold text-on-surface">Marital Status</label>
                        <select
                            id="marital_status"
                            name="marital_status"
                            class="w-full px-4 py-2.5 border @error('marital_status') border-error @else border-outline-variant @enderror rounded-lg font-body-md text-body-md input-focus-ring transition-all bg-transparent">
                            <option value="" disabled @selected(old('marital_status') === null)>Select Status</option>
                            <option value="Single" @selected(old('marital_status') === 'Single')>Single</option>
                            <option value="Married" @selected(old('marital_status') === 'Married')>Married</option>
                            <option value="Divorced" @selected(old('marital_status') === 'Divorced')>Divorced</option>
                            <option value="Widowed" @selected(old('marital_status') === 'Widowed')>Widowed</option>
                        </select>
                        @error('marital_status')
                            <p class="text-label-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Footer Actions --}}
                <div class="pt-8 mt-4 border-t border-surface-container-highest flex justify-between items-center">
                    <a href="{{ route('dashboard') }}"
                       class="px-6 py-2.5 font-label-md text-label-md font-bold text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors flex items-center gap-2">
                        Cancel Setup
                    </a>
                    <div class="flex gap-4">
                        <button type="submit"
                                class="px-8 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-label-md font-bold hover:bg-primary-container transition-all flex items-center gap-2 shadow-sm">
                            Save and Continue
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Footer Meta --}}
        <div class="mt-8 text-center">
            <p class="font-label-sm text-label-sm text-on-surface-variant opacity-60 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[14px]">lock</span>
                Your personal information is encrypted and stored according to GDPR compliance standards.
            </p>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        // Simple micro-interaction for form inputs
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', () => {
                const label = element.closest('div').querySelector('label');
                if (label) label.classList.add('text-primary');
            });
            element.addEventListener('blur', () => {
                const label = element.closest('div').querySelector('label');
                if (label) label.classList.remove('text-primary');
            });
        });

        // Live preview for profile picture upload
        const pictureInput = document.getElementById('profile_picture');
        if (pictureInput) {
            pictureInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (event) => {
                    const container = pictureInput.closest('.relative').querySelector('.rounded-full');
                    container.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover" alt="Profile picture preview">`;
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush