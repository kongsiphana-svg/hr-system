@extends('layouts.app')

@section('title')
    @yield('page-title', 'Payroll Management')
@endsection

@section('content')
    <div
        id="payroll-app"
        class="px-6 py-8 lg:px-8"
        data-csrf="{{ csrf_token() }}"
        data-process-url="{{ url('/api/payroll/process') }}"
        data-list-url="{{ url('/api/payroll') }}"
        data-payslip-url-template="{{ url('/api/payroll') }}/:id/payslip"
    >
        @yield('payroll')
    </div>
@endsection
