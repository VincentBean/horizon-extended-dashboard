<?php

namespace VincentBean\HorizonDashboard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property int $total_jobs
 * @property int $pending_jobs
 * @property int $failed_jobs
 * @property array $failed_job_ids
 * @property ?bool $cancelled
 * @property ?Carbon $cancelled_at
 * @property ?Carbon $created_at
 * @property ?Carbon $finished_at
 */
class Batch extends Model
{
    public $table = 'job_batches';

    public $keyType = 'string';

    public $guarded = [];

    public $timestamps = false;

    public $casts = [
      'failed_job_ids' => 'array'
    ];

    public function options(): Attribute
    {
        return Attribute::make(
            get: fn($value) => unserialize($value),
            set: fn ($value) => serialize($value)
        );
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => Carbon::createFromTimestamp($value),
            set: fn (Carbon $value) => $value->getTimestamp()
        );
    }

    public function cancelledAt(): Attribute
    {
        return Attribute::make(
            get: fn(?int $value) => $value === null ? null : Carbon::createFromTimestamp($value),
            set: fn (Carbon $value) => $value->getTimestamp()
        );
    }

    public function finishedAt(): Attribute
    {
        return Attribute::make(
            get: fn(?int $value) => $value === null ? null : Carbon::createFromTimestamp($value),
            set: fn (Carbon $value) => $value->getTimestamp()
        );
    }
}
