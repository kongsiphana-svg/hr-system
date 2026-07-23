@extends('layouts.hr')

@section('title', 'Create New Profile - Contact Details')

@section('content')
<div class="max-w-[960px] w-full mx-auto px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-10">
        <nav class="flex items-center gap-2 text-label-sm text-on-surface-variant mb-3 uppercase tracking-wider">
            <a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Employee Center</a>
            <span class="material-symbols-outlined text-[14px]" data-icon="chevron_right">chevron_right</span>
            <span class="text-on-surface font-bold">Create Profile</span>
        </nav>
        <h2 class="font-display-lg text-display-lg text-on-surface tracking-tight">Create New Profile</h2>
        <p class="text-body-lg text-on-surface-variant mt-2">Enter the professional and residential contact information for the new team member.</p>
    </div>

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
    <div class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <span class="text-label-md font-bold text-primary uppercase tracking-widest">Step 2: Contact Details</span>
            <span class="text-label-md text-on-surface-variant font-medium">66% Complete</span>
        </div>
        <div class="h-1.5 w-full bg-surface-container-highest rounded-full overflow-hidden">
            <div class="h-full bg-surface-tint transition-all duration-700 ease-out" style="width: 66%;"></div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]" data-icon="check">check</span>
                </div>
                <span class="text-label-md text-on-surface font-semibold">Personal Info</span>
            </div>
            <div class="flex items-center gap-3 border-b-2 border-surface-tint pb-2">
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-label-md">2</div>
                <span class="text-label-md text-primary font-bold">Contact Details</span>
            </div>
            <div class="flex items-center gap-3 opacity-50">
                <div class="w-8 h-8 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center font-bold text-label-md">3</div>
                <span class="text-label-md text-on-surface-variant">Review</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
        <div class="p-8">
            <form action="{{ route('employee-profile.contact.store') }}" class="space-y-10" method="POST">
                @csrf

                {{-- Communication Channels Section --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-primary-container text-on-primary-container rounded-lg">
                            <span class="material-symbols-outlined" data-icon="alternate_email">alternate_email</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Communication Channels</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        {{-- Work Email --}}
                        <div class="space-y-2">
                            <label for="work_email" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Work Email</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="mail">mail</span>
                                <input
                                    id="work_email"
                                    name="work_email"
                                    type="email"
                                    value="{{ old('work_email') }}"
                                    class="w-full pl-10 pr-4 py-3 border @error('work_email') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="m.sterling@corporate.com">
                            </div>
                            <p class="text-label-sm text-on-surface-variant italic">Primary internal communication channel.</p>
                            @error('work_email')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Personal Email --}}
                        <div class="space-y-2">
                            <label for="personal_email" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Personal Email</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="forward_to_inbox">forward_to_inbox</span>
                                <input
                                    id="personal_email"
                                    name="personal_email"
                                    type="email"
                                    value="{{ old('personal_email') }}"
                                    class="w-full pl-10 pr-4 py-3 border @error('personal_email') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="m.sterling@personal.me">
                            </div>
                            @error('personal_email')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mobile Phone --}}
                        <div class="space-y-2">
                            <label for="mobile_phone" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Mobile Phone</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="smartphone">smartphone</span>
                                <input
                                    id="mobile_phone"
                                    name="mobile_phone"
                                    type="tel"
                                    value="{{ old('mobile_phone') }}"
                                    class="w-full pl-10 pr-4 py-3 border @error('mobile_phone') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="+1 (555) 000-0000">
                            </div>
                            @error('mobile_phone')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Work Phone & Extension --}}
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-2 space-y-2">
                                <label for="work_phone" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Work Phone</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="phone_in_talk">phone_in_talk</span>
                                    <input
                                        id="work_phone"
                                        name="work_phone"
                                        type="tel"
                                        value="{{ old('work_phone') }}"
                                        class="w-full pl-10 pr-4 py-3 border @error('work_phone') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                        placeholder="+1 (555) 123-4567">
                                </div>
                                @error('work_phone')
                                    <p class="text-label-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1 space-y-2">
                                <label for="work_phone_ext" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Ext.</label>
                                <input
                                    id="work_phone_ext"
                                    name="work_phone_ext"
                                    type="text"
                                    value="{{ old('work_phone_ext') }}"
                                    class="w-full px-4 py-3 border border-outline-variant focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="102">
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-surface-container-highest">

                {{-- Emergency Contact Section --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-primary-container text-on-primary-container rounded-lg">
                            <span class="material-symbols-outlined" data-icon="emergency">emergency</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Emergency Contact</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        {{-- Emergency Contact Name --}}
                        <div class="space-y-2">
                            <label for="emergency_contact_name" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Contact Name</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="person">person</span>
                                <input
                                    id="emergency_contact_name"
                                    name="emergency_contact_name"
                                    type="text"
                                    value="{{ old('emergency_contact_name') }}"
                                    class="w-full pl-10 pr-4 py-3 border @error('emergency_contact_name') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="e.g. Sarah Sterling (Spouse)">
                            </div>
                            @error('emergency_contact_name')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Emergency Contact Phone --}}
                        <div class="space-y-2">
                            <label for="emergency_contact_phone" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Contact Phone</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="call">call</span>
                                <input
                                    id="emergency_contact_phone"
                                    name="emergency_contact_phone"
                                    type="tel"
                                    value="{{ old('emergency_contact_phone') }}"
                                    class="w-full pl-10 pr-4 py-3 border @error('emergency_contact_phone') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="+1 (555) 000-0000">
                            </div>
                            @error('emergency_contact_phone')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="border-surface-container-highest">

                {{-- Residential Address Section --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-primary-container text-on-primary-container rounded-lg">
                            <span class="material-symbols-outlined" data-icon="home_pin">home_pin</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Residential Address</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="street_address" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Street Address</label>
                            <input
                                id="street_address"
                                name="street_address"
                                type="text"
                                value="{{ old('street_address') }}"
                                class="w-full px-4 py-3 border @error('street_address') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                placeholder="4582 Corporate Way, Suite 400">
                            @error('street_address')
                                <p class="text-label-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="city" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">City</label>
                                <input
                                    id="city"
                                    name="city"
                                    type="text"
                                    value="{{ old('city') }}"
                                    class="w-full px-4 py-3 border @error('city') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="San Francisco">
                                @error('city')
                                    <p class="text-label-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="country" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Country</label>
                                <select
                                    id="country"
                                    name="country"
                                    class="w-full px-4 py-3 border @error('country') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md appearance-none bg-surface-container-lowest">
                                    <option value="">Select Country</option>
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
                                        <option value="{{ $name }}" @selected(old('country') == $name)>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <p class="text-label-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="zip_code" class="block font-body-sm font-bold text-on-surface-variant uppercase tracking-wide">Zip / Postal Code</label>
                                <input
                                    id="zip_code"
                                    name="zip_code"
                                    type="text"
                                    value="{{ old('zip_code') }}"
                                    class="w-full px-4 py-3 border @error('zip_code') border-error @else border-outline-variant @enderror focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none rounded-lg text-body-md"
                                    placeholder="94105">
                                @error('zip_code')
                                    <p class="text-label-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Form Footer --}}
                <div class="-mx-8 -mb-8 mt-2 bg-surface-container-low px-8 py-6 flex items-center justify-between border-t border-outline-variant">
                    <a href="{{ route('employee-profile.create-personal') }}"
                       class="flex items-center gap-2 px-6 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-on-surface-variant font-bold text-label-md hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[18px]" data-icon="arrow_back">arrow_back</span>
                        Previous Step
                    </a>

                    <div class="flex items-center gap-4">
                        <button type="submit" name="action" value="draft"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-on-surface-variant font-bold text-label-md hover:bg-surface-container-high transition-colors">
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="continue"
                                class="flex items-center gap-2 px-8 py-2.5 rounded-lg bg-surface-tint text-on-primary font-bold text-label-md hover:bg-primary transition-all shadow-md active:scale-95">
                            Continue to Review
                            <span class="material-symbols-outlined text-[18px]" data-icon="arrow_forward">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Contextual Help --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 bg-surface-container-high rounded-lg border border-outline-variant flex gap-4">
            <div class="text-primary">
                <span class="material-symbols-outlined text-[32px]" data-icon="privacy_tip">privacy_tip</span>
            </div>
            <div>
                <h4 class="font-title-lg text-title-lg text-on-tertiary-fixed mb-1">Privacy &amp; Security</h4>
                <p class="text-body-sm text-on-tertiary-fixed-variant">All contact details are encrypted and visible only to HR administrators and direct managers. Personal data follows GDPR compliance standards.</p>
            </div>
        </div>
        <div class="p-6 bg-surface-container rounded-lg border border-outline-variant flex gap-4">
            <div class="text-secondary">
                <span class="material-symbols-outlined text-[32px]" data-icon="help">help</span>
            </div>
            <div>
                <h4 class="font-title-lg text-title-lg text-on-surface mb-1">Need Assistance?</h4>
                <p class="text-body-sm text-on-surface-variant">If the employee is working remotely from a different country, please ensure the international dialing codes are included in phone fields.</p>
            </div>
        </div>
    </div>

</div>
<div class="h-12"></div>
@endsection