<?php

namespace VincentBean\HorizonDashboard\Listeners;

use Laravel\Horizon\Events\JobReserved as JobReservedEvent;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobReserved
{
    public function handle(JobReservedEvent $event)
    {
        $payload = $event->payload->decoded;

        $statisticData = [
            'reserved_at' => now()->getPreciseTimestamp(3)
        ];

        JobStatistic::query()
            ->where('uuid', $payload['uuid'])
            ->update($statisticData);
    }
}
