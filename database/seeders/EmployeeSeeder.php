<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['name' => 'Jane Doe', 'title' => 'Product Designer', 'department_id' => 'design', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 8500.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 450.00, 'fixed_deductions' => 1200.00],
            ['name' => 'Mark Smith', 'title' => 'Frontend Engineer', 'department_id' => 'engineering', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 7200.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 300.00, 'fixed_deductions' => 980.00],
            ['name' => 'Amelia Lewis', 'title' => 'Marketing Lead', 'department_id' => 'marketing', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 9100.00, 'hourly_rate' => null, 'standard_hours' => 152, 'allowances' => 600.00, 'fixed_deductions' => 1450.00],
            ['name' => 'Robert Wilson', 'title' => 'Backend Engineer', 'department_id' => 'engineering', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 8800.00, 'hourly_rate' => null, 'standard_hours' => 168, 'allowances' => 250.00, 'fixed_deductions' => 1100.00],
            ['name' => 'Elena Cruz', 'title' => 'UX Researcher', 'department_id' => 'design', 'employment_type' => 'contract', 'pay_type' => 'salary', 'base_salary' => 6950.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 400.00, 'fixed_deductions' => 850.00],
            ['name' => 'David Park', 'title' => 'DevOps Engineer', 'department_id' => 'engineering', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 8300.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 350.00, 'fixed_deductions' => 1050.00],
            ['name' => 'Sofia Martinez', 'title' => 'HR Specialist', 'department_id' => 'hr', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 6400.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 280.00, 'fixed_deductions' => 920.00],
            // Part-time, paid hourly — demonstrates the hours-worked pay path.
            ['name' => 'Liam Chen', 'title' => 'Data Analyst', 'department_id' => 'engineering', 'employment_type' => 'part_time', 'pay_type' => 'hourly', 'base_salary' => 0, 'hourly_rate' => 45.81, 'standard_hours' => 155, 'allowances' => 320.00, 'fixed_deductions' => 980.00],
            ['name' => 'Nora Abdullah', 'title' => 'Finance Manager', 'department_id' => 'finance', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 9800.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 500.00, 'fixed_deductions' => 1600.00],
            ['name' => 'Owen Blake', 'title' => 'QA Engineer', 'department_id' => 'engineering', 'employment_type' => 'full_time', 'pay_type' => 'salary', 'base_salary' => 6800.00, 'hourly_rate' => null, 'standard_hours' => 160, 'allowances' => 220.00, 'fixed_deductions' => 860.00],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['name' => $employee['name']],
                [...$employee, 'deduction_rate' => 0, 'is_active' => true]
            );
        }
    }
}