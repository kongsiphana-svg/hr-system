@extends('Payroll.layout')

@section('page-title', 'Payroll Management')

@section('payroll')

    @php
        $isHistoryView = ($selectedMonth ?? request('month')) === 'all';
    @endphp

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Payroll Management</h2>
            <p class="mt-1 text-sm text-slate-500">
                @if($isHistoryView)
                    Viewing <span class="font-semibold text-slate-800">All Historical Processed Cycles</span>
                @else
                    Pay Cycle: <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($selectedMonth ?? now()->format('Y-m'))->format('F Y') }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($summary as $card)
            <div class="rounded-xl border border-slate-200 bg-white px-5 py-4">
                <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">{{ $card['label'] }}</p>
                <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">{{ $card['value'] }}</p>
                <p class="mt-1 text-xs font-medium text-emerald-600">{{ $card['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        {{-- Filter Bar: Calendar Picker + View All Button --}}
        <form id="payroll-filters" method="GET" action="{{ route('payroll.index') }}" class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                {{-- 1. Native Calendar / Month Picker --}}
                <div class="flex items-center gap-2">
                    <label for="month" class="text-xs font-semibold text-slate-600">Select Month:</label>
                    <input 
                        type="month" 
                        id="month" 
                        name="month" 
                        value="{{ $isHistoryView ? '' : ($selectedMonth ?? now()->format('Y-m')) }}"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
                    >
                </div>

                {{-- 2. "View All History" Toggle Button --}}
                <a 
                    href="{{ route('payroll.index', ['month' => 'all']) }}" 
                    class="rounded-lg px-3.5 py-1.5 text-xs font-semibold transition-colors {{ $isHistoryView ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                >
                    📋 View All
                </a>

                {{-- Department Filter --}}
                <select name="department_id" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 focus:outline-none">
                    @foreach ($departments as $value => $label)
                        <option value="{{ $value }}" @selected(request('department_id') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Employment Type Filter --}}
                <select name="employment_type" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 focus:outline-none">
                    @foreach ($employmentTypes as $value => $label)
                        <option value="{{ $value }}" @selected(request('employment_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- TABLE SECTION --}}
        <div class="overflow-x-auto">
            @if($isHistoryView && isset($historyPayrolls))
                {{-- HISTORY MODE TABLE --}}
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Employee</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Pay Period</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Base Salary</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Allowances</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Deductions</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Net Disbursed</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Processed On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($historyPayrolls as $record)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if(!empty($record->employee?->avatar_url))
                                            <img 
                                                src="{{ $record->employee->avatar_url }}" 
                                                alt="{{ $record->employee->first_name ?? 'Employee' }}" 
                                                class="h-9 w-9 shrink-0 rounded-full object-cover border border-slate-200 shadow-sm"
                                            >
                                        @else
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold bg-slate-200 text-slate-700">
                                                {{ strtoupper(substr($record->employee?->first_name ?? 'E', 0, 1)) }}
                                            </span>
                                        @endif
                                        <span class="font-semibold text-slate-900">
                                            {{ $record->employee->first_name ?? 'Unknown' }} {{ $record->employee->last_name ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4"><span class="rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ $record->pay_period }}</span></td>
                                <td class="px-5 py-4 text-slate-700">{{ $money($record->base_salary) }}</td>
                                <td class="px-5 py-4 text-emerald-600">+{{ $money($record->allowances) }}</td>
                                <td class="px-5 py-4 text-red-500">-{{ $money($record->deductions) }}</td>
                                <td class="px-5 py-4 font-bold text-slate-900">{{ $money($record->net_pay) }}</td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $record->processed_at ? \Carbon\Carbon::parse($record->processed_at)->format('M d, Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500">No historical payroll records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                {{-- MONTHLY PROCESSING TABLE --}}
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80">
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Employee</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Base Salary</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Allowances</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Deductions</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Net Pay</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($employees as $employee)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Employee Avatar / Profile Picture --}}
                                        @if (!empty($employee['avatar_url']))
                                            <img 
                                                src="{{ $employee['avatar_url'] }}" 
                                                alt="{{ $employee['name'] }}" 
                                                class="h-9 w-9 shrink-0 rounded-full object-cover border border-slate-200 shadow-sm"
                                            >
                                        @else
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white {{ $employee['avatar'] }}">
                                                {{ $employee['initials'] }}
                                            </span>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $employee['name'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $employee['title'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-700">{{ $money($employee['base_salary']) }}</td>
                                <td class="px-5 py-4 font-medium text-emerald-600">+{{ $money($employee['allowances']) }}</td>
                                <td class="px-5 py-4 font-medium text-red-500">-{{ $money($employee['deductions']) }}</td>
                                <td class="px-5 py-4 font-bold text-slate-900">{{ $money($employee['net_pay']) }}</td>
                                <td class="px-5 py-4">
                                    @if($employee['status'] === 'Processed')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20 ring-inset">
                                            Processed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-600/10 ring-inset">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button" 
                                            onclick="document.getElementById('dialog-{{ $employee['employee_id'] }}').showModal()"
                                            class="rounded-lg {{ $employee['status'] === 'Processed' ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-slate-900 text-white hover:bg-slate-800' }} px-3 py-1.5 text-xs font-semibold"
                                        >
                                            {{ $employee['status'] === 'Processed' ? 'Edit' : 'Process' }}
                                        </button>

                                        @if($employee['status'] === 'Processed')
                                            <form action="{{ route('payroll.reset', $employee['employee_id']) }}" method="POST" onsubmit="return confirm('Reset this payroll to Pending?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="pay_period" value="{{ $selectedMonth }}">
                                                <button type="submit" class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">
                                                    Reset
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Native Dialog Modal Popup --}}
                                    <dialog id="dialog-{{ $employee['employee_id'] }}" class="rounded-2xl p-0 backdrop:bg-slate-900/50 shadow-2xl border-0 w-full max-w-md">
                                        <div class="p-6 text-left">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <h3 class="text-lg font-bold text-slate-900">
                                                    {{ $employee['status'] === 'Processed' ? 'Edit Payroll' : 'Process Payroll' }}
                                                </h3>
                                                <button onclick="document.getElementById('dialog-{{ $employee['employee_id'] }}').close()" type="button" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                                            </div>

                                            <form action="{{ route('payroll.process.store', $employee['employee_id']) }}" method="POST" class="mt-4 space-y-4">
                                                @csrf
                                                <input type="hidden" name="pay_period" value="{{ $selectedMonth }}">

                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500">Employee</label>
                                                    <p class="text-sm font-semibold text-slate-800">{{ $employee['name'] }} (Cycle: {{ $selectedMonth }})</p>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500">Base Salary</label>
                                                    <input type="text" class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 p-2 text-sm text-slate-600" value="{{ $money($employee['base_salary']) }}" readonly>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500">Allowances ($)</label>
                                                    <input type="number" step="0.01" min="0" name="allowances" class="mt-1 w-full rounded-lg border border-slate-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" value="{{ $employee['allowances'] }}" required>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500">Deductions ($)</label>
                                                    <input type="number" step="0.01" min="0" name="deductions" class="mt-1 w-full rounded-lg border border-slate-300 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" value="{{ $employee['deductions'] }}" required>
                                                </div>

                                                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                                    <button type="button" onclick="document.getElementById('dialog-{{ $employee['employee_id'] }}').close()" class="rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                                                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">Confirm & Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    
@endsection