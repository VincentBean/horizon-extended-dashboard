<?php

namespace VincentBean\HorizonDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VincentBean\HorizonDashboard\Database\Factories\JobInformationFactory;

/**
 * @property string $id
 * @property string $class
 */
class JobInformation extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $guarded = [];

    public function statistics(): HasMany
    {
        return $this->hasMany(JobStatistic::class);
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(JobException::class);
    }

    public function averageRuntime(): float
    {
        return round($this->statistics()->average('runtime'), 2) ?? 0;
    }

    public function averageAttempts(): int
    {
        return $this->statistics()->average('attempts') ?? 0;
    }

    public function failRatio(): float
    {
        $data = $this->statistics()->get();
        $failed = $data->where('failed', true);
        $success = $data->where('failed', false);

        $successCount = $success->where('aggregated', false)->count() + $data->sum('aggregated_job_count');
        $failedCount = $failed->where('aggregated', false)->where('failed', true)->count() + $data->sum('aggregated_failed_count');

        if ($successCount === 0) {
            return 1;
        }

        if ($failedCount === 0) {
            return 0;
        }
        return round($failedCount / $successCount, 4);
    }


    public static function findByClass(string $class): ?static
    {
        return static::query()
            ->where('class', $class)
            ->first();
    }

    protected static function newFactory(): JobInformationFactory
    {
        return new JobInformationFactory();
    }
}
