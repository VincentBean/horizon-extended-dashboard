<?php

namespace VincentBean\HorizonDashboard\Observers;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class QueueObserver
{
    public function before(JobProcessing $event): void
    {


    }

    public function after(JobProcessed $event): void
    {
        $job = $event->job;
        $payload = $job->payload();

        $statisticData = [
            'attempts' => $event->job->attempts(),
            'finished_at' => now()
        ];

        JobStatistic::query()
            ->where('uuid', $payload['uuid'])
            ->update($statisticData);
    }

}
