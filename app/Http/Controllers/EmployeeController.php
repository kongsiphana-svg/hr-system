<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
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
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $this->storeAvatar($request->file('avatar'));
        }

        unset($data['avatar']);

        session([self::WIZARD_SESSION_KEY => $data]);

        return redirect()->route('employees.create.contact');
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
                ->route('employees.create')
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

        return redirect()->route('employees.create.job');
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
                ->route('employees.create.contact')
                ->with('status', 'Please complete the Contact Details step first.');
        }

        return view('employees.create-job', [
            'wizard' => session(self::WIZARD_SESSION_KEY),
            'departments' => $this->departments(),
            'statuses' => Employee::STATUSES,
            'employmentTypes' => Employee::EMPLOYMENT_TYPES,
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
                ->route('employees.create.contact')
                ->with('status', 'Please complete the Contact Details step first.');
        }

        $wizardData = session(self::WIZARD_SESSION_KEY);

        $jobData = $request->validate([
            'department' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:' . implode(',', Employee::EMPLOYMENT_TYPES)],
            'start_date' => ['required', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'work_location' => ['required', 'in:' . implode(',', Employee::WORK_LOCATIONS)],
            'probation_period' => ['required', 'in:' . implode(',', Employee::PROBATION_PERIODS)],
            'status' => ['required', 'in:' . implode(',', Employee::STATUSES)],
        ]);

        if (isset($jobData['salary'])) {
            $jobData['base_salary'] = $jobData['salary'];
        }
        $jobData['is_active'] = ($jobData['status'] ?? '') === 'active';

        $employee = Employee::create(array_merge($wizardData, $jobData));

        session()->forget(self::WIZARD_SESSION_KEY);

        return redirect()
            ->route('employees.show', $employee)
            ->with('status', "{$employee->name} was added to the directory.");
    }

    /**
     * GET /employees/{employee}
     */
    public function show(Employee $employee)
    {
        return view('employees.show', [
            'employee' => $employee,
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

        if (array_key_exists('salary', $data)) {
            $data['base_salary'] = $data['salary'];
        }
        if (array_key_exists('status', $data)) {
            $data['is_active'] = $data['status'] === 'active';
        }

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee)
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
            ->route('employees.show', $employee)
            ->with('status', "{$employee->name}'s account was deactivated.");
    }

    /**
     * DELETE /employees/{employee}
     */
    public function destroy(Employee $employee)
    {
        $name = $employee->name;
        $employee->delete();

        return redirect()
            ->route('employees.index')
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
     * Save an uploaded avatar directly into public/uploads/avatars and
     * return its public URL. Deliberately avoids the storage:link symlink
     * approach, since some Nginx configs refuse to follow it (500 error).
     */
    protected function storeAvatar(\Illuminate\Http\UploadedFile $file): string
    {
        $directory = public_path('uploads/avatars');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return asset('uploads/avatars/' . $filename);
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
        return $request->validate([
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
            'salary' => ['nullable', 'numeric', 'min:0'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'work_location' => ['required', 'in:' . implode(',', Employee::WORK_LOCATIONS)],
            'probation_period' => ['required', 'in:' . implode(',', Employee::PROBATION_PERIODS)],
            'status' => ['required', 'in:' . implode(',', Employee::STATUSES)],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
        ]);
    }
}
