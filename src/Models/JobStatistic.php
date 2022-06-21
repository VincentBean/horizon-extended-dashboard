<?php

namespace VincentBean\HorizonDashboard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VincentBean\HorizonDashboard\Database\Factories\JobStatisticFactory;

/**
 * @property string $id
 * @property int $job_information_id
 * @property string $uuid
 * @property string $queue
 * @property float $runtime
 * @property int $attempts
 * @property bool $failed
 * @property Carbon $queued_at
 * @property ?Carbon $reserved_at
 * @property ?Carbon $finished_at
 * @property bool $aggregated
 * @property ?int $aggregated_job_count
 * @property ?int $aggregated_failed_count
 */
class JobStatistic extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $guarded = [];

    public function jobInformation(): BelongsTo
    {
        return $this->belongsTo(JobInformation::class);
    }

    protected function queuedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromTimestampMs($value),
            set: fn($value) => is_a($value, Carbon::class) ? $value->getPreciseTimestamp(3) : $value
        );
    }

    protected function reservedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromTimestampMs($value),
            set: fn($value) => is_a($value, Carbon::class) ? $value->getPreciseTimestamp(3) : $value
        );
    }

    protected function finishedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromTimestampMs($value),
            set: fn($value) => is_a($value, Carbon::class) ? $value->getPreciseTimestamp(3) : $value
        );
    }

    public function calculateRuntime(): ?float
    {
        if ($this->reserved_at === null || $this->finished_at === null) {
            return null;
        }

        return $this->reserved_at->floatDiffInSeconds($this->finished_at);
    }

    protected static function newFactory(): JobStatisticFactory
    {
        return new JobStatisticFactory();
    }
}
