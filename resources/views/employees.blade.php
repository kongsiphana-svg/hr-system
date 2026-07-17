@extends('layouts.app')

@section('title', 'Employee Directory | HRMS Admin')

@section('content')

@include('partials.sidebar')
@include('partials.topnav')

{{-- Main Content Canvas --}}
<main class="ml-[260px] pt-16 min-h-screen">
    <div class="p-margin-desktop max-w-container-max mx-auto">

        {{-- Page Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 pt-8">
            <div>
                <nav class="flex items-center gap-2 text-label-sm text-secondary mb-2">
                    <span>Portal</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-primary font-bold">Employee Directory</span>
                </nav>
                <h2 class="font-display-lg text-display-lg text-on-surface">Employees</h2>
                <p class="font-body-md text-body-md text-secondary mt-1">Manage, filter, and track all members of the organization.</p>
            </div>
            <div class="flex items-center gap-3">

                <a href="{{ route('employees.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-label-md text-label-md hover:shadow-lg hover:shadow-primary/20 transition-all">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Add Employee
                </a>
            </div>
        </div>

        {{-- Bento Filter & Summary Bar --}}
        <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="md:col-span-3 bg-white border border-outline-variant rounded-xl p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-body-sm font-bold text-on-surface mb-1 block" for="department">Filter by Department</label>
                        <select name="department" id="department" class="w-full bg-surface-container-low border border-outline-variant rounded-lg py-2 px-3 text-body-sm focus:border-primary outline-none">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-body-sm font-bold text-on-surface mb-1 block" for="status">Filter by Status</label>
                        <select name="status" id="status" class="w-full bg-surface-container-low border border-outline-variant rounded-lg py-2 px-3 text-body-sm focus:border-primary outline-none">
                            <option value="">All Statuses</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="on_leave" @selected(request('status') === 'on_leave')>On Leave</option>
                            <option value="terminated" @selected(request('status') === 'terminated')>Terminated</option>
                            <option value="probation" @selected(request('status') === 'probation')>Probation</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-outline-variant">
                    <button type="submit" class="px-4 py-2 text-white bg-primary font-label-md rounded-lg hover:opacity-90 transition-opacity">Apply</button>
                    <a href="{{ route('employees.index') }}" class="px-4 py-2 text-primary font-label-md hover:bg-primary/5 rounded-lg transition-colors">Reset All</a>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="bg-primary text-white border border-primary-container rounded-xl p-4 flex flex-col justify-center relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-white/70 font-label-sm text-label-sm uppercase tracking-widest">Total Workforce</p>
                    <h3 class="text-display-lg font-display-lg leading-none mt-1">{{ number_format($totalWorkforce) }}</h3>
<<<<<<< HEAD
=======
                    <p class="text-primary-fixed font-body-sm mt-1">+{{ $newHiresThisMonth ?? 0 }} this month</p>
>>>>>>> origin/feat/fe-employee-visal
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[120px] text-white/10 rotate-12">groups</span>
            </div>
        </form>

        {{-- Data Table Section --}}
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-bottom border-outline-variant">
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Job Title</th>
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 font-label-md text-label-md text-secondary uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-high">
                        @forelse ($employees as $employee)
                            @php
                                $statusStyles = [
                                    'active'      => 'bg-emerald-50 text-emerald-700',
                                    'on_leave'    => 'bg-amber-50 text-amber-700',
                                    'probation'   => 'bg-secondary-container text-secondary',
                                    'terminated'  => 'bg-error-container text-error',
                                ];
                                $statusLabels = [
                                    'active'     => 'Active',
                                    'on_leave'   => 'On Leave',
                                    'probation'  => 'Probation',
                                    'terminated' => 'Terminated',
                                ];
                                $initials = collect(explode(' ', $employee->name))
                                    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                    ->take(2)
                                    ->implode('');
                            @endphp
                            <tr class="hover:bg-surface-bright transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($employee->avatar_url)
                                            <img class="w-10 h-10 rounded-full object-cover" src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center text-primary font-bold">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-title-lg text-[14px] text-on-surface">{{ $employee->name }}</p>
                                            <p class="text-body-sm text-secondary">{{ $employee->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-body-md text-body-md text-secondary">{{ $employee->department }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-body-md text-body-md text-secondary">{{ $employee->job_title }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-body-md text-body-md text-secondary">{{ optional($employee->start_date)->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 font-label-sm text-label-sm rounded-full {{ $statusStyles[$employee->status] ?? 'bg-surface-container text-secondary' }}">
                                        {{ $statusLabels[$employee->status] ?? ucfirst($employee->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('employees.show', $employee) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg" title="View Profile">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Remove {{ $employee->name }} from the directory?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-secondary font-body-md">
                                    No employees match the current filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="px-6 py-4 border-t border-outline-variant flex items-center justify-between bg-surface-container-low">
                <p class="font-body-sm text-body-sm text-secondary">
                    Showing <span class="font-bold text-on-surface">{{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }}</span>
                    of <span class="font-bold text-on-surface">{{ number_format($employees->total()) }}</span> employees
                </p>
                <div class="flex items-center gap-1">
                    {{ $employees->onEachSide(1)->links('partials.pagination') }}
                </div>
            </div>
        </div>

<<<<<<< HEAD
        
        
=======
        {{-- Footer Meta --}}
        <div class="mt-8 flex justify-between items-center text-label-sm text-secondary">
            <p>© {{ date('Y') }} HRMS Enterprise Admin Console</p>
            <div class="flex gap-4">
                <a class="hover:text-primary transition-colors" href="{{ url('/privacy') }}">Privacy Policy</a>
                <a class="hover:text-primary transition-colors" href="{{ url('/terms') }}">Terms of Service</a>
            </div>
        </div>
    </div>
>>>>>>> origin/feat/fe-employee-visal
</main>

@endsection