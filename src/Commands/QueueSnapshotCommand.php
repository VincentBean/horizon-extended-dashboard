<?php

namespace VincentBean\HorizonDashboard\Commands;

use Illuminate\Console\Command;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Actions\Statistics\TakeQueueSnapshot;

class QueueSnapshotCommand extends Command
{
    protected $signature = 'horizon-dashboard:queue-snapshot';

    protected $description = 'Take a snapshot of all queues to store in the queue statistics';

    public function handle(RetrieveQueues $retrieveQueuesAction, TakeQueueSnapshot $action)
    {
        $retrieveQueuesAction->handle()
            ->each(fn(string $queue) => $action->handle($queue));

        return static::SUCCESS;
    }

}
