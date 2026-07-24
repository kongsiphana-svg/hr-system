@extends('employee.layout')

@section('title', 'Settings')
@section('page-title', 'Profile Settings')

@section('content')
<form action="{{ route('employee.settings.profile') }}" method="POST" enctype="multipart/form-data">
    @csrf
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Profile Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            {{-- Clean Color Header --}}
            <div class="h-24 bg-gradient-to-r from-primary to-primary/70"></div>

            {{-- Avatar --}}
            <div class="px-6 -mt-12 text-center">
                <div class="relative group inline-block">
                    <label for="avatar" class="cursor-pointer block">
                        <div class="relative">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="Avatar"
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg"
                                     id="avatarPreview">
                            @else
                                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-primary text-headline-md font-bold border-4 border-white shadow-lg"
                                     id="avatarPlaceholder">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                            {{-- Camera Overlay --}}
                            <div class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 cursor-pointer border-4 border-white">
                                <span class="material-symbols-outlined text-white text-[28px]">photo_camera</span>
                            </div>
                        </div>
                    </label>
                </div>

                <h3 class="font-title-lg text-title-lg text-on-surface mt-4">{{ $user->name }}</h3>
                <p class="font-body-sm text-body-sm text-secondary">{{ $user->email }}</p>

                <div class="mt-3 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-label-sm font-label-sm">
                    <span class="material-symbols-outlined text-[14px]">badge</span>
                    Employee
                </div>
            </div>

            {{-- Profile Details --}}
            <div class="px-6 pb-6 mt-6">
                <div class="bg-surface-container-low rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span class="font-body-sm text-body-sm">Member since</span>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface font-medium">{{ $user->created_at?->format('M Y') ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined text-[16px]">shield</span>
                            <span class="font-body-sm text-body-sm">Role</span>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface font-medium capitalize">{{ $user->role }}</span>
                    </div>
                </div>
            </div>

            @error('avatar')
                <div class="px-6 pb-4">
                    <p class="font-body-sm text-red-600 text-center">{{ $message }}</p>
                </div>
            @enderror
        </div>
    </div>

    {{-- Hidden avatar input inside the form --}}
    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/png,image/jpeg,image/jpg">

    {{-- Account Form --}}
    <div class="lg:col-span-2">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-outline-variant bg-gradient-to-r from-surface-container-low/80 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[22px]">manage_accounts</span>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface">Account Settings</h3>
                        <p class="font-body-sm text-body-sm text-secondary">Update your profile, email, and password.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('employee.settings.profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block font-label-md text-label-md text-secondary mb-1.5">Full Name</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary/40 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all hover:border-primary/40"
                                required
                            >
                        </div>
                        @error('name')
                            <p class="mt-1.5 font-body-sm text-red-600 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block font-label-md text-label-md text-secondary mb-1.5">Email Address</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary/40 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all hover:border-primary/40"
                                required
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 font-body-sm text-red-600 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <hr class="border-outline-variant">

                    {{-- Change Password Section --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-amber-600 text-[20px]">lock</span>
                            <h4 class="font-title-md text-title-md text-on-surface">Change Password</h4>
                        </div>
                        <p class="font-body-sm text-body-sm text-secondary mb-4">Leave blank to keep your current password.</p>

                        {{-- Current Password --}}
                        <div class="mb-4">
                            <label for="current_password" class="block font-label-md text-label-md text-secondary mb-1.5">Current Password</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary/40 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">lock</span>
                                </span>
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all hover:border-primary/40"
                                    placeholder="Enter current password"
                                >
                            </div>
                            @error('current_password')
                                <p class="mt-1.5 font-body-sm text-red-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- New Password --}}
                            <div>
                                <label for="password" class="block font-label-md text-label-md text-secondary mb-1.5">New Password</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary/40 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">key</span>
                                    </span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all hover:border-primary/40"
                                        placeholder="••••••••"
                                    >
                                </div>
                                @error('password')
                                    <p class="mt-1.5 font-body-sm text-red-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block font-label-md text-label-md text-secondary mb-1.5">Confirm Password</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-secondary/40 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">verified</span>
                                    </span>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all hover:border-primary/40"
                                        placeholder="••••••••"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant flex items-center justify-between">
                    <p class="font-body-sm text-body-sm text-secondary/60">All changes are saved together.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-label-md text-label-md hover:bg-primary/90 active:scale-[0.97] transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('avatar')?.addEventListener('change', function() {
    const file = this.files && this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                const img = document.createElement('img');
                img.id = 'avatarPreview';
                img.src = e.target.result;
                img.className = 'w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg';
                placeholder.parentNode.replaceChild(img, placeholder);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
