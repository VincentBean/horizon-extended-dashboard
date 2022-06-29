<?php

namespace VincentBean\HorizonDashboard\Listeners;

use Carbon\Carbon;
use Illuminate\Queue\Events\JobExceptionOccurred;
use VincentBean\HorizonDashboard\Models\JobException as JobExceptionModel;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobException
{
    public function handle(JobExceptionOccurred $event)
    {
        $payload = $event->job->payload();
        $class = unserialize(data_get($payload, 'data.command'));

        $jobInformation = JobInformation::query()
            ->firstOrCreate([
                'class' => data_get($payload, 'data.commandName')
            ]);

        $statistic = JobStatistic::query()
            ->where('uuid', $payload['uuid'])
            ->first();

        $runtime = $statistic !== null
            ? $statistic['reserved_at']->floatDiffInSeconds(now())
            : 0;

        JobExceptionModel::create([
            'job_information_id' => $jobInformation->id,
            'occured_at' => now(),
            'uuid' => $payload['uuid'] ?? '',
            'attempt' => $payload['attempts'] ?? 0,
            'runtime' => $runtime,
            'exception' => get_class($event->exception),
            'message' => $event->exception->getMessage(),
            'trace' => $event->exception->getTrace(),
            'data' => get_object_vars($class)
        ]);
    }
}
