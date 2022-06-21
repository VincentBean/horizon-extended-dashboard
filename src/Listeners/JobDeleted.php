<?php

namespace VincentBean\HorizonDashboard\Listeners;

use Laravel\Horizon\Events\JobDeleted as JobDeletedEvent;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobDeleted
{
    public function handle(JobDeletedEvent $event)
    {
        $payload = $event->payload->decoded;

        /** @var JobStatistic $statistic */
        $statistic = JobStatistic::query()
            ->where('uuid', $payload['uuid'])
            ->first();

        $statistic->fill(
            [
                'attempts' => $payload['attempts'],
                'finished_at' => now()
            ]
        );


        $statistic->runtime = $statistic->calculateRuntime();

        $statistic->save();
    }
}
