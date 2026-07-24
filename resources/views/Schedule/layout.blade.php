@extends('layouts.app')

@section('title')
    @yield('page-title', 'Work Schedule')
@endsection

@push('scripts')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/schedule.js'])
    @endif
@endpush

@section('content')
    <div
        id="schedule-app"
        class="min-h-[calc(100vh-4rem)]"
        data-csrf="{{ csrf_token() }}"
        data-create-url="{{ url('/schedules') }}"
        data-list-url="{{ url('/schedules') }}"
    >
        {{-- Sub-nav for schedule pages --}}
        <div class="border-b border-outline-variant bg-surface px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto py-2">
                @php
                    $scheduleTabs = [
                        ['label' => 'Work Schedule', 'route' => 'admin.schedule.index'],
                        ['label' => 'Calendar', 'route' => 'admin.schedule.calendar'],
                        ['label' => 'Add Shift', 'route' => 'admin.schedule.create'],
                    ];
                @endphp
                @foreach ($scheduleTabs as $tab)
                    <a
                        href="{{ route($tab['route']) }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium whitespace-nowrap transition-colors
                            {{ request()->routeIs($tab['route'])
                                ? 'bg-primary text-white'
                                : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}"
                    >
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="px-6 py-8 lg:px-8">
            @yield('schedule')
        </div>
    </div>
@endsection
