<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
   public function pageIndex(Request $request)
{
    $weekStart = $this->resolveWeekStart($request->query('week'));
    $weekEnd = $weekStart->copy()->addDays(6);

    $query = Schedule::with('employee')
        ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
        ->orderBy('date')
        ->orderBy('start_time');

    if ($department = $request->query('department')) {
        $query->whereHas('employee', fn ($q) => $q->where('department', $department));
    }

    $schedules = $query->paginate(10)->withQueryString();

    $todayDate = Carbon::now(config('app.timezone', 'Asia/Phnom_Penh'))->toDateString();

    $todayCount = Schedule::where(function ($q) use ($todayDate) {
            $q->whereDate('date', $todayDate)
              ->orWhere('date', 'like', $todayDate . '%');
        })
        ->count();

    $departments = Employee::query()
        ->whereNotNull('department')
        ->where('department', '!=', '')
        ->distinct()
        ->orderBy('department')
        ->pluck('department');

    $employees = Employee::orderBy('first_name')->orderBy('last_name')->get();

    return view('Schedule.index', [
        'schedules' => $schedules,
        'weekStart' => $weekStart,
        'weekEnd' => $weekEnd,
        'todayCount' => $todayCount,
        'departments' => $departments,
        'employees' => $employees,
        'selectedDepartment' => $department,
    ]);
}
    public function pageCalendar(Request $request)
    {
        $weekStart = $this->resolveWeekStart($request->query('week'));
        $weekEnd = $weekStart->copy()->addDays(6);

        $days = collect(range(0, 6))->map(fn (int $i) => [
            'label' => $weekStart->copy()->addDays($i)->format('D'),
            'date' => $weekStart->copy()->addDays($i)->format('M j'),
            'full' => $weekStart->copy()->addDays($i)->toDateString(),
        ]);

        $schedules = Schedule::with('employee')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('start_time')
            ->get();

        $employees = Employee::orderBy('first_name')->orderBy('last_name')->get()->map(function (Employee $employee) use ($schedules, $weekStart) {
            $shifts = [];

            foreach ($schedules->where('employee_id', $employee->id) as $schedule) {
                $dayIndex = $weekStart->diffInDays(Carbon::parse($schedule->date)->startOfDay());
                $shifts[$dayIndex] = [
                    'id' => $schedule->id,
                    'start' => $schedule->start_time_formatted,
                    'end' => $schedule->end_time_formatted,
                    'color' => $this->shiftColor($schedule->shift_type, $employee->department),
                    'note' => $schedule->notes,
                    'status' => $schedule->status,
                ];
            }

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'department' => $employee->department ?: 'Unassigned',
                'avatar' => $employee->avatar_url,
                'initials' => $employee->initials(),
                'shifts' => $shifts,
            ];
        })->values();

        $coverage = $employees
            ->groupBy('department')
            ->map(function ($group, $label) use ($weekStart) {
                $daysWithShifts = collect($group)->flatMap(fn ($e) => array_keys($e['shifts']))->unique()->count();
                $percent = (int) min(100, round(($daysWithShifts / max(1, $group->count() * 7)) * 100 * 3));

                return [
                    'label' => $label,
                    'percent' => $percent,
                    'color' => $this->deptBarColor($label),
                ];
            })
            ->values();

        return view('Schedule.calendar', [
            'days' => $days,
            'employees' => $employees,
            'coverage' => $coverage,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'allEmployees' => Employee::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function pageCreate()
    {
        return view('Schedule.create', [
            'employees' => Employee::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $query = Schedule::with('employee')->orderBy('date')->orderBy('start_time');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->query('from'), $request->query('to')]);
        }

        return $query->paginate(50);
    }

    public function show(Schedule $schedule)
    {
        return $schedule->load('employee');
    }

    public function store(Request $request)
    {
        $data = $this->validatedShift($request);
        $data['status'] = $data['status'] ?? 'scheduled';

        $schedule = Schedule::create($data)->load('employee');

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json($schedule, 201);
        }

        return redirect()
            ->route('schedule.index', ['week' => Carbon::parse($schedule->date)->toDateString()])
            ->with('success', 'Shift created successfully.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $this->validatedShift($request, partial: true);
        $schedule->update($data);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json($schedule->fresh()->load('employee'));
        }

        return redirect()->back()->with('success', 'Shift updated successfully.');
    }

    public function destroy(Request $request, Schedule $schedule)
    {
        $schedule->delete();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->noContent();
        }

        return redirect()->back()->with('success', 'Shift deleted.');
    }

    protected function validatedShift(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return $request->validate([
            'employee_id' => [$required, 'integer', Rule::exists('employees', 'id')],
            'date' => [$required, 'date'],
            'start_time' => [$required, 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'end_time' => [$required, 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'shift_type' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);
    }

    protected function resolveWeekStart(?string $week): Carbon
    {
        $date = $week ? Carbon::parse($week) : now();

        return $date->copy()->startOfWeek(Carbon::MONDAY);
    }

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

    protected function deptBarColor(string $label): string
    {
        return match (true) {
            str_contains(strtolower($label), 'eng') => 'bg-blue-500',
            str_contains(strtolower($label), 'ops') || str_contains(strtolower($label), 'operation') => 'bg-green-500',
            str_contains(strtolower($label), 'support') => 'bg-purple-500',
            default => 'bg-indigo-500',
        };
    }
}