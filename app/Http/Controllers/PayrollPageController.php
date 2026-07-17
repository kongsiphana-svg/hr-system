<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('employment_type')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('employment_type', $request->employment_type);
            });
        }

        $summaryQuery = clone $query;
        $totalGross = $summaryQuery->sum('gross_pay');
        $totalNet = $summaryQuery->sum('net_pay');
        $totalDeductions = $summaryQuery->sum('deductions');
        $totalEmployees = $summaryQuery->count();

        $money = function ($amount) {
            return '$' . number_format((float) $amount, 2);
        };

        $summary = [
            [
                'key' => 'total_payroll',
                'label' => 'Total Payroll',
                'value' => $money($totalGross),
                'hint' => 'Gross compensation',
            ],
            [
                'key' => 'net_disbursed',
                'label' => 'Net Disbursed',
                'value' => $money($totalNet),
                'hint' => 'Total take-home pay',
            ],
            [
                'key' => 'taxes_deductions',
                'label' => 'Taxes & Deductions',
                'value' => $money($totalDeductions),
                'hint' => 'Withheld from gross',
            ],
            [
                'key' => 'employees_processed',
                'label' => 'Employees Processed',
                'value' => $totalEmployees,
                'hint' => 'In current view',
            ],
        ];

        $payrolls = $query->paginate(10)->withQueryString();

        $employees = collect($payrolls->items())->map(function ($payroll) {
            $emp = $payroll->employee;
            
            $nameParts = explode(' ', $emp->name);
            $initials = substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '');

            return [
                'employee_id' => $emp->id,
                'department_id' => $emp->department_id,
                'employment_type' => $emp->employment_type,
                'base_salary' => $payroll->base_salary,
                'hours' => $payroll->hours_worked,
                'allowances' => $payroll->allowances,
                'deductions' => $payroll->deductions,
                'net_pay' => $payroll->net_pay,
                'avatar' => 'bg-indigo-600', // Tailwind color for the circle avatar
                'initials' => strtoupper($initials),
                'name' => $emp->name,
                'title' => $emp->title,
            ];
        });

        $departments = [
            '' => 'All Departments',
            'engineering' => 'Engineering',
            'marketing' => 'Marketing',
            'sales' => 'Sales',
            'hr' => 'Human Resources',
        ];

        $employmentTypes = [
            '' => 'All Types',
            'salary' => 'Salary',
            'hourly' => 'Hourly',
        ];

        return view('Payroll.index', compact(
            'summary', 
            'departments', 
            'employmentTypes', 
            'payrolls', 
            'employees', 
            'money'
        ));
    }
}