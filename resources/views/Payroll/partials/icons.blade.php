@php($class = $class ?? 'h-5 w-5')

@switch($name)
    @case('dashboard')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z"/>
        </svg>
        @break

    @case('employees')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        @break

    @case('schedule')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
        @break

    @case('payroll')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <rect x="2" y="6" width="20" height="12" rx="2"/>
            <circle cx="12" cy="12" r="2.5"/>
            <path stroke-linecap="round" d="M6 12h.01M18 12h.01"/>
        </svg>
        @break

    @case('leave')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M9 15l2 2 4-4"/>
        </svg>
        @break

    @case('settings')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <circle cx="12" cy="12" r="3"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h0a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v0a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>
        </svg>
        @break

    @case('logout')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
        </svg>
        @break

    @case('search')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="7"/>
            <path stroke-linecap="round" d="m20 20-3.5-3.5"/>
        </svg>
        @break

    @case('bell')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.3 21a1.7 1.7 0 0 0 3.4 0"/>
        </svg>
        @break

    @case('help')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <circle cx="12" cy="12" r="9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 2.5-3 4.5"/>
            <path stroke-linecap="round" d="M12 17.5h.01"/>
        </svg>
        @break

    @case('chevron-down')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
        </svg>
        @break

    @case('chevron-left')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
        </svg>
        @break

    @case('chevron-right')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
        </svg>
        @break

    @case('filter')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M10 18h4"/>
        </svg>
        @break
@endswitch
