<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Create the single admin user ──────────────────────────────────
        User::updateOrCreate(
            ['email' => 'malong@gmail.com'],
            [
                'name' => 'Malong',
                'email' => 'malong@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $this->command->info('Admin user created: malong@gmail.com / 12345678');

        // ── Seed employee records ─────────────────────────────────────────
        $this->call(EmployeeSeeder::class);

        // ── Create a user account for each employee (role: employee) ──────
        $employees = Employee::all();

        foreach ($employees as $employee) {
            if (! $employee->email) {
                continue; // skip employees without an email
            }

            // Derive a username from the employee's name (lowercase, no spaces)
            $username = strtolower(str_replace(' ', '.', $employee->name));

            User::updateOrCreate(
                ['email' => $employee->email],
                [
                    'name' => $username,
                    'email' => $employee->email,
                    'password' => Hash::make('password123'),
                    'role' => User::ROLE_EMPLOYEE,
                ]
            );
        }

        $this->command->info(count($employees) . ' employee user accounts created (password: password123)');
    }
}
