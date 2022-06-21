<?php

namespace VincentBean\HorizonDashboard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VincentBean\HorizonDashboard\Database\Factories\QueueStatisticFactory;

/**
 * @property string $id
 * @property string $queue
 * @property int $job_pushed_count
 * @property int $job_completed_count
 * @property int $job_failed_count
 * @property int $jobs_per_minute
 * @property int $throughput
 * @property int $wait
 * @property float $average_runtime
 * @property array $jobs
 * @property Carbon snapshot_at
 * @property bool $aggregated
 */
class QueueStatistic extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $guarded = [];

    public $casts = [
        'jobs' => 'array',
        'snapshot_at' => 'datetime',
    ];

    protected static function newFactory(): QueueStatisticFactory
    {
        return new QueueStatisticFactory();
    }
}
