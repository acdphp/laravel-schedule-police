<?php

namespace Acdphp\SchedulePolice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $key
 * @property string $expression
 * @property Carbon $created_at
 * @property ?string $by
 */
class StoppedScheduledEvent extends Model
{
    protected $fillable = [
        'key',
        'expression',
        'by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
