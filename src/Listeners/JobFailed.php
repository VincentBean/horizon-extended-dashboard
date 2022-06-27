<?php

namespace VincentBean\HorizonDashboard\Listeners;

use Laravel\Horizon\Events\JobFailed as JobFailedEvent;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobFailed
{
    public function handle(JobFailedEvent $event)
    {
        $payload = $event->payload->decoded;

        JobStatistic::query()
            ->where('uuid', $payload['uuid'])
            ->update(['failed' => true, 'finished_at' => now()->getTimestamp()]);
    }
}
