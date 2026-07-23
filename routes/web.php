<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file wires up every named route() call used across the
| resources/views/employee-profile/*.blade.php and partials files.
|
| It's written as a working starting point using closures + session
| storage so you can click through the whole "create profile" wizard
| immediately. Swap the closures for real Controller methods (and the
| session-array wizard storage for real Eloquent persistence) as you
| build out the backend — the route NAMES are what the views depend on,
| so keep those stable even as you replace the implementations.
|
*/

// ---------------------------------------------------------------------
// General workspace routes (placeholders — point these at your real
// Dashboard / Schedule / Leave controllers)
// ---------------------------------------------------------------------

Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::view('/schedule', 'schedule.index')->name('schedule.index');

Route::view('/leave-requests', 'leave.index')->name('leave.index');

Route::get('/employees/search', function (Request $request) {
    $query = $request->query('q');

    // TODO: replace with a real Employee search query
    $results = [];

    return view('employees.search-results', compact('query', 'results'));
})->name('employees.search');

// Optional: viewing a colleague's profile (used if you deploy
// employee-profile/employee.blade.php as an HR-admin-facing view of someone
// else's record, distinct from the self-service employee-profile.show page).
Route::get('/employees/{employee}', function ($employee) {
    // TODO: fetch the real employee record and pass it as $employee
    return view('employee-profile.employee', ['employee' => []]);
})->name('employees.show');

// ---------------------------------------------------------------------
// Profile — view / edit (existing employee)
// ---------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {

    Route::get('/employee-profile', function () {
        // TODO: once you have real persistence, replace this with the
        // authenticated user's Employee record. For now this reads back
        // whatever the "create profile" wizard most recently saved to
        // the session (see job.store below).
        $employee = session('created_employee_profile', []);

        return view('employee-profile.show', compact('employee'));
    })->name('employee-profile.show');

    Route::get('/employee-profile/edit', function () {
        // TODO: once you have real persistence, replace this with the
        // authenticated user's Employee record.
        $employee = session('created_employee_profile', []);

        return view('employee-profile.edit', compact('employee'));
    })->name('employee-profile.edit');

    Route::put('/employee-profile', function (Request $request) {
        $validated = $request->validate([
            'avatar'                    => ['nullable', 'image'],
            'remove_avatar'             => ['nullable', 'boolean'],
            'date_of_birth'             => ['required', 'date'],
            'gender'                    => ['required', 'string'],
            'nationality'               => ['required', 'string'],
            'marital_status'            => ['required', 'string'],
            'personal_email'            => ['required', 'email'],
            'mobile_phone'              => ['required', 'string'],
            'emergency_contact_name'    => ['nullable', 'string'],
            'emergency_contact_phone'   => ['nullable', 'string'],
            'address'                   => ['required', 'string'],
            'department'                => ['required', 'string'],
            'job_title'                 => ['required', 'string'],
            'employment_type'           => ['required', 'array', 'min:1'],
            'employment_type.*'         => ['string', 'in:Full-time,Part-time,Contract,Remote,Hybrid,On-site'],
            'start_date'                => ['required', 'date'],
        ]);

        // TODO: replace this session-merge with a real Eloquent update
        // against the authenticated user's Employee record.
        $employee = session('created_employee_profile', []);

        if ($request->boolean('remove_avatar')) {
            $employee['avatar_url'] = null;
        } elseif ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $employee['avatar_url'] = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        $employee['date_of_birth']   = $validated['date_of_birth'];
        $employee['gender']          = $validated['gender'];
        $employee['nationality']     = $validated['nationality'];
        $employee['marital_status']  = $validated['marital_status'];
        $employee['personal_email']  = $validated['personal_email'];
        $employee['mobile_phone']    = $validated['mobile_phone'];
        $employee['department']      = $validated['department'];
        $employee['title']           = $validated['job_title'];
        $employee['employment_type'] = $validated['employment_type'];
        $employee['start_date']      = $validated['start_date'];

        $employee['address_lines'] = array_values(array_filter(
            array_map('trim', explode("\n", str_replace("\r\n", "\n", $validated['address'])))
        ));

        $employee['emergency_contact'] = (empty($validated['emergency_contact_name']) && empty($validated['emergency_contact_phone']))
            ? null
            : [
                'name'  => $validated['emergency_contact_name'] ?? null,
                'phone' => $validated['emergency_contact_phone'] ?? null,
            ];

        session()->put('created_employee_profile', $employee);

        return redirect()->route('employee-profile.show')->with('success', 'Profile changes saved successfully.');
    })->name('employee-profile.update');

    Route::get('/employee-profile/request-email-change', function () {
        // TODO: build a real "request work email change" form/flow
        return view('employee-profile.request-email-change');
    })->name('employee-profile.request-email-change');

    Route::post('/employee-profile/request-email-change', function (Request $request) {
        $request->validate(['new_email' => ['required', 'email']]);

        // TODO: create a change request record / notify HR

        return redirect()->route('employee-profile.edit')->with('success', 'Email change request submitted.');
    })->name('employee-profile.request-email-change.store');
});

// ---------------------------------------------------------------------
// Profile creation wizard (new employee onboarding)
//
// Step 1: Personal Info      -> employee-profile.create-personal / employee-profile.personal.store
// Step 2: Contact Details    -> employee-profile.create-contact   / employee-profile.contact.store
// Step 3: Experience         -> employee-profile.create.experience (placeholder — no
//                                blade file has been built for this step yet)
// Step 4: Job Details        -> employee-profile.create.job        / employee-profile.job.store
// Step 5: Success             -> employee-profile.create.success
//
// Wizard state is held in the session under the "profile_wizard" key
// until the final step, when it should be persisted to the database.
// ---------------------------------------------------------------------

Route::prefix('employee-profile/create')->name('employee-profile.')->group(function () {

    // Step 1 — Personal Information
    Route::get('/', function () {
        return view('employee-profile.create');
    })->name('create-personal');

    Route::post('/', function (Request $request) {
        $validated = $request->validate([
            'profile_picture' => ['nullable', 'image'],
            'full_name'       => ['required', 'string', 'max:255'],
            'date_of_birth'   => ['required', 'date'],
            'gender'          => ['required', 'string'],
            'nationality'     => ['required', 'string'],
            'id_type'         => ['required', 'string'],
            'marital_status'  => ['required', 'string', 'in:Single,Married,Divorced,Widowed'],
        ]);

        // The uploaded file itself can't be stored in the session, so
        // save it to disk now and keep only its public URL.
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $validated['avatar_url'] = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }
        unset($validated['profile_picture']);

        session()->put('profile_wizard.personal', $validated);

        return redirect()->route('employee-profile.create-contact');
    })->name('personal.store');

    // Step 2 — Contact Details
    Route::get('/contact', function () {
        if (! session()->has('profile_wizard.personal')) {
            return redirect()->route('employee-profile.create-personal');
        }

        return view('employee-profile.create-contact');
    })->name('create-contact');

    Route::post('/contact', function (Request $request) {
        $validated = $request->validate([
            'work_email'                => ['required', 'email'],
            'personal_email'            => ['nullable', 'email'],
            'mobile_phone'              => ['required', 'string'],
            'work_phone'                => ['nullable', 'string'],
            'work_phone_ext'            => ['nullable', 'string'],
            'emergency_contact_name'    => ['required', 'string'],
            'emergency_contact_phone'   => ['required', 'string'],
            'street_address'            => ['required', 'string'],
            'city'                      => ['required', 'string'],
            'country'                   => ['required', 'string'],
            'zip_code'                  => ['required', 'string'],
        ]);

        session()->put('profile_wizard.contact', $validated);

        // The "Continue to Review" action on this step leads into Job
        // Details in this wizard's flow.
        return redirect()->route('employee-profile.create.job');
    })->name('contact.store');

    // Step 3 — Experience (placeholder; build employee-profile.create.experience.blade.php
    // when you're ready and swap this closure for that view)
    Route::get('/experience', function () {
        return view('employee-profile.create-experience');
    })->name('create.experience');

    // Step 4 — Job Details
    Route::get('/job', function () {
        if (! session()->has('profile_wizard.contact')) {
            return redirect()->route('employee-profile.create-contact');
        }

        // TODO: replace with real department/manager/team lookups
        return view('employee-profile.create-job');
    })->name('create.job');

    Route::post('/job', function (Request $request) {
        $validated = $request->validate([
            'department'         => ['required', 'string'],
            'job_title'          => ['required', 'string'],
            'employment_type'    => ['required', 'array', 'min:1'],
            'employment_type.*'  => ['string', 'in:Full-time,Part-time,Contract,Remote,Hybrid,On-site'],
            'start_date'         => ['required', 'date'],
            'manager_id'         => ['nullable', 'string'],
            'team_ids'           => ['nullable', 'array'],
        ]);

        session()->put('profile_wizard.job', $validated);

        // TODO: replace this whole block with real Eloquent persistence —
        // create the Employee record here from session('profile_wizard'),
        // instead of just re-shaping it and stashing it in the session.
        $personal = session('profile_wizard.personal', []);
        $contact  = session('profile_wizard.contact', []);
        $job      = session('profile_wizard.job', []);

        $genderLabels = [
            'male'                => 'Male',
            'female'              => 'Female',
            'non-binary'          => 'Non-binary',
            'prefer-not-to-say'   => 'Prefer not to say',
        ];

        $addressLines = array_values(array_filter([
            $contact['street_address'] ?? null,
            trim(($contact['city'] ?? '') . (isset($contact['country']) ? ', ' . $contact['country'] : '')),
            $contact['zip_code'] ?? null,
        ]));

        $employeeProfile = [
            'name'             => $personal['full_name'] ?? null,
            'avatar_url'       => $personal['avatar_url'] ?? null,
            'title'            => $job['job_title'] ?? null,
            'status'           => 'Active Employee',
            'employee_id'      => 'EMP-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT),
            'location'         => trim(($contact['city'] ?? '') . (isset($contact['country']) ? ', ' . $contact['country'] : '')) ?: null,
            'date_of_birth'    => $personal['date_of_birth'] ?? null,
            'gender'           => $genderLabels[$personal['gender'] ?? ''] ?? ($personal['gender'] ?? null),
            'nationality'      => $personal['nationality'] ?? null,
            'marital_status'   => $personal['marital_status'] ?? null,
            'work_email'       => $contact['work_email'] ?? null,
            'personal_email'   => $contact['personal_email'] ?? null,
            'mobile_phone'     => $contact['mobile_phone'] ?? null,
            'address_lines'    => $addressLines,
            'emergency_contact' => (empty($contact['emergency_contact_name']) && empty($contact['emergency_contact_phone']))
                ? null
                : [
                    'name'  => $contact['emergency_contact_name'] ?? null,
                    'phone' => $contact['emergency_contact_phone'] ?? null,
                ],
            'department'       => $job['department'] ?? null,
            'employment_type'  => $job['employment_type'] ?? [],
            'start_date'       => $job['start_date'] ?? null,
            'manager'          => null, // not collected by this wizard (manager_id has no lookup yet)
            'groups'           => [],   // not collected by this wizard (team_ids has no lookup yet)
        ];

        session()->put('created_employee_profile', $employeeProfile);

        $employeeName = $personal['full_name'] ?? 'New Employee';
        session()->forget('profile_wizard');

        return redirect()->route('employee-profile.create.success')->with('employee_name', $employeeName);
    })->name('job.store');

    Route::get('/teams/edit', function () {
        // TODO: build a real team-assignment editor
        return view('employee-profile.job-teams-edit');
    })->name('job.edit-teams');

    // Step 5 — Success
    Route::get('/success', function () {
        $employee = ['name' => session('employee_name', 'Alex Johnson')];

        return view('employee-profile.success', compact('employee'));
    })->name('create.success');
});