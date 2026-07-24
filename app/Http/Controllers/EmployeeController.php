<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    /**
     * Number of rows shown per page in the directory table.
     */
    protected const PER_PAGE = 10;

    /**
     * Session key the create wizard uses to accumulate data across steps.
     */
    protected const WIZARD_SESSION_KEY = 'employee_wizard.data';

    /**
     * GET /employees
     */
    public function index(Request $request)
    {
        $employees = $this->filteredQuery($request)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('employees', [
            'employees' => $employees,
            'departments' => $this->departments(),
            'totalWorkforce' => Employee::count(),
            'newHiresThisMonth' => Employee::whereBetween('start_date', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count(),
        ]);
    }

    /**
     * GET /employees/create
     *
     * Step 1 of 3: Personal Information.
     */
    public function create()
    {
        return view('employees.create', [
            'old' => session(self::WIZARD_SESSION_KEY, []),
            'genders' => Employee::GENDERS,
        ]);
    }

    /**
     * POST /employees/create
     *
     * Validates step 1, stashes it in the session, and moves to step 2.
     */
    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:' . implode(',', Employee::GENDERS)],
            'nationality' => ['required', 'string', 'max:255'],
            'identification_id' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $this->storeAvatar($request->file('avatar'));
        }

        unset($data['avatar']);

        // Merge so going back to step 1 doesn't wipe contact/job session data.
        session([
            self::WIZARD_SESSION_KEY => array_merge(
                session(self::WIZARD_SESSION_KEY, []),
                $data
            ),
        ]);

        return redirect()->route('admin.employees.create.contact');
    }

    /**
     * GET /employees/create/contact
     *
     * Step 2 of 3: Contact Details.
     */
    public function createContact()
    {
        if (! session()->has(self::WIZARD_SESSION_KEY . '.first_name')) {
            return redirect()
                ->route('admin.employees.create')
                ->with('status', 'Please start with the Personal Information step first.');
        }

        return view('employees.create-contact', [
            'step1' => session(self::WIZARD_SESSION_KEY),
        ]);
    }

    /**
     * POST /employees/create/contact
     *
     * Validates step 2, merges it into the wizard session, and moves to step 3.
     */
    public function storeContact(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'personal_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:255'],
        ]);

        session([self::WIZARD_SESSION_KEY => array_merge(session(self::WIZARD_SESSION_KEY, []), $data)]);

        return redirect()->route('admin.employees.create.job');
    }

    /**
     * GET /employees/create/job
     *
     * Step 3 of 3: Job Details.
     */
    public function createJob()
    {
        if (! session()->has(self::WIZARD_SESSION_KEY . '.email')) {
            return redirect()
                ->route('admin.employees.create.contact')
                ->with('status', 'Please complete the Contact Details step first.');
        }

        return view('employees.create-job', [
            'wizard' => session(self::WIZARD_SESSION_KEY),
            'departments' => $this->departments(),
            'statuses' => Employee::STATUSES,
            'employmentTypes' => Employee::EMPLOYMENT_TYPES,
            'payTypes' => Employee::PAY_TYPES,
            'workLocations' => Employee::WORK_LOCATIONS,
            'probationPeriods' => Employee::PROBATION_PERIODS,
            'reportingManagers' => Employee::query()
                ->orderBy('first_name')
                ->get()
                ->map(fn (Employee $employee) => $employee->name),
        ]);
    }

    /**
     * POST /employees/create/job
     *
     * Validates step 3, merges it with the accumulated wizard session,
     * creates the employee, and clears the session.
     */
    public function store(Request $request)
    {
        if (! session()->has(self::WIZARD_SESSION_KEY . '.email')) {
            return redirect()
                ->route('admin.employees.create.contact')
                ->with('status', 'Please complete the Contact Details step first.');
        }

        $wizardData = session(self::WIZARD_SESSION_KEY);

        $jobData = $request->validate(array_merge([
            'department' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:' . implode(',', Employee::EMPLOYMENT_TYPES)],
            'start_date' => ['required', 'date'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'work_location' => ['required', 'in:' . implode(',', Employee::WORK_LOCATIONS)],
            'probation_period' => ['required', 'in:' . implode(',', Employee::PROBATION_PERIODS)],
            'status' => ['required', 'in:' . implode(',', Employee::STATUSES)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $this->payrollValidationRules()));

        $jobData = $this->normalizePayrollFields($jobData);
        $jobData['is_active'] = ($jobData['status'] ?? '') === 'active';

        // Drop non-column wizard leftovers before create.
        $payload = collect(array_merge($wizardData, $jobData))
            ->only((new Employee)->getFillable())
            ->all();

        $employee = Employee::create($payload);

        // ── Auto-create a User account with the admin-set password ───
        User::updateOrCreate(
            ['email' => $employee->email],
            [
                'name' => strtolower(str_replace(' ', '.', $employee->name)),
                'email' => $employee->email,
                'password' => Hash::make($jobData['password']),
                'role' => User::ROLE_EMPLOYEE,
            ]
        );

        // Save encrypted plain-text password for admin to view later
        $employee->update([
            'plain_password' => encrypt($jobData['password']),
        ]);

        session()->forget(self::WIZARD_SESSION_KEY);

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('status', "{$employee->name} was added to the directory.");
    }

    /**
     * GET /employees/{employee}
     */
    public function show(Employee $employee)
    {
        return view('employees.show', [
            'employee' => $employee,
            'decryptedPassword' => $employee->plain_password ? decrypt($employee->plain_password) : null,
        ]);
    }

    /**
     * GET /employees/{employee}/edit
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', [
            'employee' => $employee,
            'departments' => $this->departments(),
            'statuses' => Employee::STATUSES,
            'genders' => Employee::GENDERS,
            'employmentTypes' => Employee::EMPLOYMENT_TYPES,
            'payTypes' => Employee::PAY_TYPES,
            'workLocations' => Employee::WORK_LOCATIONS,
            'probationPeriods' => Employee::PROBATION_PERIODS,
            'reportingManagers' => Employee::query()
                ->where('id', '!=', $employee->id)
                ->orderBy('first_name')
                ->get()
                ->map(fn (Employee $e) => $e->name),
        ]);
    }

    /**
     * PUT/PATCH /employees/{employee}
     */
    public function update(Request $request, Employee $employee)
    {
        $data = $this->validated($request, $employee);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $this->storeAvatar($request->file('avatar'));
        }

        unset($data['avatar']);

        $data = $this->normalizePayrollFields($data);

        if (array_key_exists('status', $data)) {
            $data['is_active'] = $data['status'] === 'active';
        }

        $employee->update($data);

        // If a new password was provided, update the user account
        if ($request->filled('password')) {
            User::where('email', $employee->email)->update([
                'password' => Hash::make($request->password),
            ]);

            // Also update the stored plain password
            $employee->update([
                'plain_password' => encrypt($request->password),
            ]);
        }

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('status', "{$employee->name}'s record was updated.");
    }

    /**
     * PATCH /employees/{employee}/deactivate
     *
     * Quick status-only update, separate from the full edit form.
     */
    public function deactivate(Employee $employee)
    {
        $employee->update([
            'status' => 'terminated',
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('status', "{$employee->name}'s account was deactivated.");
    }

    /**
     * GET /employees/{employee}/create-account
     *
     * Show a form to create a User account for an Employee.
     */
    public function createAccountForm(Employee $employee): \Illuminate\View\View
    {
        // Check if this employee already has a user account via email match
        $existingUser = User::where('email', $employee->email)->first();

        return view('employees.create-account', [
            'employee' => $employee,
            'existingUser' => $existingUser,
        ]);
    }

    /**
     * POST /employees/{employee}/create-account
     *
     * Create a new User account for the Employee with role=employee.
     */
    public function createAccount(Request $request, Employee $employee): RedirectResponse
    {
        // Guard: ensure no duplicate user for this email
        if (User::where('email', $employee->email)->exists()) {
            return redirect()
                ->route('admin.employees.create-account', $employee)
                ->with('error', 'A user account already exists for this email address.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $employee->email,
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_EMPLOYEE,
        ]);

        // Save encrypted plain-text password for admin to view later
        $employee->update([
            'plain_password' => encrypt($validated['password']),
        ]);

        return redirect()
            ->route('admin.employees.show', $employee)
            ->with('success', "Employee account created for {$user->name}. They can now log in with their email and the password you set.");
    }

    /**
     * DELETE /employees/{employee}
     */
    public function destroy(Employee $employee)
    {
        $name = $employee->name;
        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('status', "{$name} was removed from the directory.");
    }

    /**
     * GET /employees/export
     *
     * Streams the (optionally filtered) employee directory as a CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $employees = $this->filteredQuery($request)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $filename = 'employees-' . now()->format('Y-m-d') . '.csv';

        $callback = function () use ($employees) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Department', 'Job Title', 'Start Date', 'Status']);

            foreach ($employees as $employee) {
                fputcsv($handle, [
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email,
                    $employee->department,
                    $employee->job_title,
                    optional($employee->start_date)->format('Y-m-d'),
                    $employee->status,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Apply the search/department/status filters shared by index() and export().
     */
    protected function filteredQuery(Request $request)
    {
        return Employee::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . $request->string('search') . '%';
                $query->where(function ($query) use ($term) {
                    $query->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('job_title', 'like', $term);
                });
            })
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department', $request->string('department'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            });
    }

    /**
     * Save an uploaded avatar into public/uploads/avatars and return its public URL.
     * Avoids storage:link so Nginx configs that block symlink follow still work.
     */
    protected function storeAvatar(\Illuminate\Http\UploadedFile $file): string
    {
        $directory = $this->ensureAvatarDirectory();

        $filename = uniqid('avatar_', true).'.'.$file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return asset('uploads/avatars/'.$filename);
    }

    /**
     * Ensure public/uploads/avatars exists, recovering from a broken uploads symlink.
     */
    protected function ensureAvatarDirectory(): string
    {
        $uploads = public_path('uploads');
        $directory = $uploads.DIRECTORY_SEPARATOR.'avatars';

        // Broken symlink leftover from another machine (mkdir would fail otherwise).
        if (is_link($uploads) && ! file_exists($uploads)) {
            unlink($uploads);
        }

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException("Unable to create avatar directory at [{$directory}].");
        }

        return $directory;
    }

    /**
     * Distinct department list used to populate the filter dropdown.
     * Seeded with sensible defaults so the list isn't empty before any
     * employees exist yet.
     */
    protected function departments(): array
    {
        $defaults = ['Engineering', 'Operations', 'Support', 'Sales', 'Marketing', 'HR'];

        $existing = Employee::query()
            ->select('department')
            ->distinct()
            ->pluck('department')
            ->all();

        return collect($defaults)
            ->merge($existing)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Shared validation rules for update().
     */
    protected function validated(Request $request, Employee $employee): array
    {
        return $request->validate(array_merge([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:' . implode(',', Employee::GENDERS)],
            'nationality' => ['required', 'string', 'max:255'],
            'identification_id' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,email,' . $employee->id,
            ],
            'personal_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:' . implode(',', Employee::EMPLOYMENT_TYPES)],
            'start_date' => ['required', 'date'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'work_location' => ['required', 'in:' . implode(',', Employee::WORK_LOCATIONS)],
            'probation_period' => ['required', 'in:' . implode(',', Employee::PROBATION_PERIODS)],
            'status' => ['required', 'in:' . implode(',', Employee::STATUSES)],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], $this->payrollValidationRules()));
    }

    /**
     * Compensation fields required by payroll generation.
     *
     * @return array<string, list<mixed>>
     */
    protected function payrollValidationRules(): array
    {
        return [
            'pay_type' => ['required', 'in:' . implode(',', Employee::PAY_TYPES)],
            'salary' => ['nullable', 'numeric', 'min:0', 'required_if:pay_type,salary'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'required_if:pay_type,hourly'],
            'standard_hours' => ['required', 'integer', 'min:1', 'max:744'],
            'fixed_start_time' => ['nullable', 'string', 'regex:/^\d{2}:\d{2}$/', 'max:5'],
            'fixed_end_time' => ['nullable', 'string', 'regex:/^\d{2}:\d{2}$/', 'max:5'],
            'fixed_work_days' => ['nullable', 'array'],
            'fixed_work_days.*' => ['string', 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'deduction_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'fixed_deductions' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Map form compensation inputs onto payroll DB columns.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizePayrollFields(array $data): array
    {
        $payType = $data['pay_type'] ?? 'salary';
        $standardHours = (int) ($data['standard_hours'] ?? 160);

        $data['standard_hours'] = max(1, $standardHours);
        $data['allowances'] = round((float) ($data['allowances'] ?? 0), 2);
        $data['fixed_deductions'] = round((float) ($data['fixed_deductions'] ?? 0), 2);

        if (array_key_exists('deduction_percent', $data)) {
            $data['deduction_rate'] = round(((float) ($data['deduction_percent'] ?? 0)) / 100, 4);
            unset($data['deduction_percent']);
        } elseif (! array_key_exists('deduction_rate', $data)) {
            $data['deduction_rate'] = 0.1000;
        }

        if ($payType === 'hourly') {
            $hourlyRate = round((float) ($data['hourly_rate'] ?? 0), 2);
            $data['hourly_rate'] = $hourlyRate;
            $data['base_salary'] = round($hourlyRate * $data['standard_hours'], 2);
            $data['salary'] = $data['base_salary'];
        } else {
            $salary = round((float) ($data['salary'] ?? $data['base_salary'] ?? 0), 2);
            $data['salary'] = $salary;
            $data['base_salary'] = $salary;
            $data['hourly_rate'] = null;
        }

        return $data;
    }
}
