<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'shift_type',
        'location',
        'notes',
        'status',
    ];

   protected $casts = [
    'date' => 'date',
   ];
    protected $appends = ['hours', 'start_time_formatted', 'end_time_formatted'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStartTimeFormattedAttribute(): string
    {
        return $this->formatTime($this->start_time);
    }

    public function getEndTimeFormattedAttribute(): string
    {
        return $this->formatTime($this->end_time);
    }

    public function getHoursAttribute(): string
    {
        $start = Carbon::parse($this->date->format('Y-m-d').' '.$this->formatTime($this->start_time));
        $end = Carbon::parse($this->date->format('Y-m-d').' '.$this->formatTime($this->end_time));

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        return number_format($start->diffInMinutes($end) / 60, 1).'h';
    }

    protected function formatTime(mixed $value): string
    {
        if ($value instanceof Carbon) {
            return $value->format('H:i');
        }

        return substr((string) $value, 0, 5);
    }
}
