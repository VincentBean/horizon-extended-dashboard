<?php

namespace VincentBean\HorizonDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobStatisticFactory extends Factory
{
    protected $model = JobStatistic::class;

    public function definition()
    {
        $last = JobStatistic::query()->orderBy('queued_at')->first();

        $queuedAt = $last !== null
            ? $last->queued_at->addSeconds(rand(0, 60))
            : now()->subHours(rand(0, 24))->subMinutes(rand(0, 60))->subDays(rand(0, 30));

        return [
            'job_information_id' => JobInformation::query()->select(['id'])->get()->pluck('id')->random(),
            'uuid' => Str::uuid(),
            'queue' => app(RetrieveQueues::class)->handle()->random(),
            'runtime' => rand(100, 10000) / 100,
            'attempts' => rand(0, 3),
            'failed' => rand(0, 10) < 4,
            'queued_at' => $queuedAt,
            'reserved_at' => $queuedAt->addSeconds(rand(0, 120)),
            'finished_at' => $queuedAt->addSeconds(rand(120, 240)),
        ];
    }
}
