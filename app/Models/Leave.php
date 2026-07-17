<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    /**
     * Get the employee that owns the leave request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}