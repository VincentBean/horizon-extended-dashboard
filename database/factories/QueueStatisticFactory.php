<?php

namespace VincentBean\HorizonDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class QueueStatisticFactory extends Factory
{
    protected $model = QueueStatistic::class;

    public function definition()
    {
        $jobs = JobInformation::all()
            ->shuffle()
            ->take(rand(0, 3))
            ->mapWithKeys(fn(JobInformation $information) => [$information->class => rand(0, 100)])
            ->toArray();

        return [
            'average_runtime' => rand(100, 10000) / 100,
            'job_pushed_count' => rand(0, 1000),
            'job_completed_count' => rand(0, 1000),
            'job_fail_count' => rand(0, 1000),
            'jobs_per_minute' => rand(0, 100),
            'throughput' => rand(0, 100),
            'wait' => rand(0, 100),
            'jobs' => $jobs,
            'snapshot_at' => now()->subHours(rand(0, 24))->subMinutes(rand(0, 60))->subDays(rand(0, 30))
        ];
    }
}
