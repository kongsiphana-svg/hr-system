<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeDashboardController extends Controller
{
    /**
     * Employee personal dashboard.
     */
    public function dashboard(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        $pendingLeaves = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'pending'])
            ->count();

        $recentLeaves = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $latestPayroll = null;
        $payPeriods = collect();

        if ($employee) {
            $latestPayroll = Payroll::where('employee_id', $employee->id)
                ->whereIn('status', ['approved', 'paid'])
                ->latest('processed_at')
                ->first();

            $payPeriods = Payroll::where('employee_id', $employee->id)
                ->whereIn('status', ['approved', 'paid'])
                ->latest('processed_at')
                ->take(6)
                ->get();
        }

        return view('employee.dashboard', [
            'user' => $user,
            'employee' => $employee,
            'pendingLeaves' => $pendingLeaves,
            'recentLeaves' => $recentLeaves,
            'latestPayroll' => $latestPayroll,
            'payPeriods' => $payPeriods,
        ]);
    }

    /**
     * Employee payslip index.
     */
    public function payroll(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        $employeeId = $employee?->id;

        $payrolls = Payroll::where('employee_id', $employeeId)
            ->whereIn('status', ['approved', 'paid'])
            ->latest('processed_at')
            ->paginate(10)
            ->withQueryString();

        $allPayrolls = Payroll::where('employee_id', $employeeId)
            ->whereIn('status', ['approved', 'paid'])
            ->get(['net_pay', 'gross_pay', 'deductions']);

        $totals = [
            'net' => (float) $allPayrolls->sum('net_pay'),
            'gross' => (float) $allPayrolls->sum('gross_pay'),
            'deductions' => (float) $allPayrolls->sum('deductions'),
        ];

        return view('employee.payroll', [
            'user' => $user,
            'employee' => $employee,
            'payrolls' => $payrolls,
            'totals' => $totals,
        ]);
    }

    /**
     * Single payslip detail view.
     */
    public function payrollShow(Payroll $payroll): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure employee can only view their own payslips
        if (! $user->isAdmin() && (! $employee || $payroll->employee_id !== $employee->id)) {
            abort(403, 'You can only view your own payslips.');
        }

        return view('employee.payroll-show', [
            'payroll' => $payroll->load('employee'),
            'user' => $user,
        ]);
    }

    /**
     * Download payslip (placeholder for PDF generation).
     */
    public function payrollDownload(Payroll $payroll): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        if (! $user->isAdmin() && (! $employee || $payroll->employee_id !== $employee->id)) {
            abort(403);
        }

        // TODO: Generate PDF payslip
        return redirect()->back()->with('info', 'Payslip download will be available soon.');
    }

    /**
     * Employee leave requests list.
     */
    public function leaves(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $leaves = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.leaves', [
            'user' => $user,
            'leaves' => $leaves,
        ]);
    }

    /**
     * Show form to create a new leave request.
     */
    public function leavesCreate(): View
    {
        return view('employee.leaves-create');
    }

    /**
     * Store a new leave request.
     */
    public function leavesStore(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'leave_type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $user->leaveRequests()->create([
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('employee.leaves')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Employee schedule — admin-calendar-style week view.
     */
    public function schedule(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);

        if ($request->query('week')) {
            try {
                $weekStart = Carbon::parse($request->query('week'))->startOfWeek(Carbon::MONDAY);
            } catch (\Exception $e) {
                $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
            }
        }
        $weekEnd = $weekStart->copy()->addDays(6);

        $shifts = collect();
        $employeeShifts = []; // keyed by day index, same format as admin calendar

        if ($employee) {
            $shifts = Schedule::where('employee_id', $employee->id)
                ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            foreach ($shifts as $schedule) {
                $dayIndex = (int) $weekStart->diffInDays(Carbon::parse($schedule->date)->startOfDay());
                $employeeShifts[$dayIndex] = [
                    'id' => $schedule->id,
                    'start' => $schedule->start_time_formatted,
                    'end' => $schedule->end_time_formatted,
                    'color' => $this->shiftColor($schedule->shift_type, $employee->department),
                    'note' => $schedule->notes,
                    'status' => $schedule->status,
                ];
            }
        }

        $days = collect(range(0, 6))->map(fn (int $i) => [
            'label' => $weekStart->copy()->addDays($i)->format('D'),
            'date' => $weekStart->copy()->addDays($i)->format('M j'),
            'full' => $weekStart->copy()->addDays($i)->toDateString(),
        ]);

        return view('employee.schedule', [
            'user' => $user,
            'employee' => $employee,
            'shifts' => $shifts,
            'employeeShifts' => $employeeShifts,
            'days' => $days,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);
    }

    /**
     * Map a shift type / department to a color key (mirrors ScheduleController logic).
     */
    protected function shiftColor(?string $shiftType, ?string $department): string
    {
        $key = strtolower((string) ($shiftType ?: $department));

        return match (true) {
            str_contains($key, 'night') => 'purple',
            str_contains($key, 'over') => 'orange',
            str_contains($key, 'ops') || str_contains($key, 'operation') => 'green',
            str_contains($key, 'support') || str_contains($key, 'success') => 'purple',
            default => 'blue',
        };
    }

    /**
     * Employee settings / profile page.
     */
    public function settings(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('employee.settings', ['user' => $user]);
    }

    /**
     * Update employee profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['required_with:password', 'string', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $this->storeAvatar($request->file('avatar'));
        }

        // ── Get employee BEFORE changing the email so the relation still resolves ──
        $employee = $user->employee;

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // ── Sync changes to the linked Employee record ──
        if ($employee) {
            $nameParts = preg_split('/\s+/', trim($request->name), 2);
            $firstName = $nameParts[0] ?? $request->name;
            $lastName = $nameParts[1] ?? '';

            $employee->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
            ]);
        }

        return redirect()
            ->route('employee.settings')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Store an uploaded avatar and return its public URL.
     */
    protected function storeAvatar(\Illuminate\Http\UploadedFile $file): string
    {
        $directory = public_path('uploads/avatars');

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException("Unable to create avatar directory.");
        }

        $filename = uniqid('avatar_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return asset('uploads/avatars/'.$filename);
    }
}
