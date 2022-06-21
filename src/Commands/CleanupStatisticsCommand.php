<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class CleanupStatisticsCommand extends Command
{
    protected $signature = 'horizon-dashboard:cleanup-statistics {--hours=}';

    protected $description = 'Delete old statistic data';

    public function handle()
    {
        $to = Carbon::now()->subHours($this->option('hours'));

        $queueQuery = QueueStatistic::query()
            ->where('snapshot_at', '<=', $to->toDateTimeString());

        $this->info("Deleting {$queueQuery->count()} queue statistics");
        $queueQuery->delete();

        $jobQuery = JobStatistic::query()
            ->where('finished_at', '<=', $to->getPreciseTimestamp(3));

        $this->info("Deleting {$jobQuery->count()} job statistics");
        $jobQuery->delete();

        return static::SUCCESS;
    }

}
