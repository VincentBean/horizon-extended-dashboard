<?php

namespace VincentBean\HorizonDashboard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $job_information_id
 * @property int $attempt
 * @property int $runtime
 * @property string $uuid
 * @property string $exception
 * @property array $trace
 */
class JobException extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'occured_at' => 'datetime',
        'trace' => 'array',
        'data' => 'array',
    ];

    public function jobInformation(): BelongsTo
    {
        return $this->belongsTo(JobInformation::class);
    }
}
