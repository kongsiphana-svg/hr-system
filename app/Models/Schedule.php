<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
 