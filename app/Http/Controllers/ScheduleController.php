<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule

class ScheduleController extends Controller
{
    public function index() {
        return Schedule::OrderBy('date')->OrderBy('start_time')->paginate(10);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'employee_id' => 'required|integer',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'shift_type'  => 'nullable|string',
            'location'    => 'nullable|string',
            'notes'       => 'nullable|string',
            'status'      => 'nullable|string',
        ]);

        return Schedule::create($data);
    }


    public function update(Request $request, Schedule $schedule) {
        $data = $request->validate([
            'employee_id' => 'sometimes|required|integer',
            'date'        => 'sometimes|required|date',
            'start_time'  => 'sometimes|required',
            'end_time'    => 'sometimes|required',
            'shift_type'  => 'nullable|string',
            'location'    => 'nullable|string',
            'notes'       => 'nullable|string',
            'status'      => 'nullable|string',
        ]);

        $schedule->update($data);
        return $schedule;
    }

    public function destory(Schedule $schedule) {
        $schedule->delete();
        return response()->noContent();
    }
}
