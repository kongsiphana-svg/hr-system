@extends('layouts.app')

@section('title')
    @yield('page-title', 'Payroll Management')
@endsection

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #payslip-modal,
        #payslip-modal * { visibility: visible; }
        #payslip-modal { position: static; display: block !important; }
        #payslip-print-area { padding: 0; }
    }
</style>
@endpush

@section('content')
    <div
        id="payroll-app"
        class="px-6 py-8 lg:px-8"
        data-csrf="{{ csrf_token() }}"
        data-process-url="{{ url('/api/payroll/process') }}"
        data-list-url="{{ url('/api/payroll') }}"
        data-payslip-url-template="{{ url('/api/payroll') }}/:id/payslip"
        data-review-url-template="{{ url('/api/payroll') }}/:id/review"
        data-approve-url-template="{{ url('/api/payroll') }}/:id/approve"
        data-mark-paid-url-template="{{ url('/api/payroll') }}/:id/mark-paid"
    >
        @yield('payroll')
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/payroll.js'])
@endpush
