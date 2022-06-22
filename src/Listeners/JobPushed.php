<?php

namespace VincentBean\HorizonDashboard\Listeners;

use Laravel\Horizon\Events\JobPushed as JobPushedEvent;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobPushed
{
    public function handle(JobPushedEvent $event)
    {
        $payload = $event->payload->decoded;

        $jobInformation = JobInformation::query()
            ->firstOrCreate([
                'class' => data_get($payload, 'data.commandName')
            ]);

        $statisticIdentifierData = [
            'uuid' => $payload['uuid'],
            'job_information_id' => $jobInformation->id
        ];

        $statisticData = [
            'queue' => $event->queue,
            'queued_at' => $payload['pushedAt']
        ];

        JobStatistic::query()->firstOrCreate($statisticIdentifierData, $statisticData);
    }
}
