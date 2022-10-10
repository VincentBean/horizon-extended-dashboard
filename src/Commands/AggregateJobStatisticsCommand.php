<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use VincentBean\HorizonDashboard\Jobs\AggregateJobStatisticsJob;
use VincentBean\HorizonDashboard\Jobs\ChunkJobAggregationsJob;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class AggregateJobStatisticsCommand extends Command
{
    protected $signature = 'horizon-dashboard:aggregate-job-statistics {--interval=} {--keep=}';

    protected $description = 'Aggregate the data in the job_statistics table
                --interval: Interval in minutes
                --keep: Don\'t aggregate minutes';

    public function handle(): int
    {
        $interval = $this->option('interval');
        $keep = $this->option('keep');

        ChunkJobAggregationsJob::dispatch($keep, $interval);

        return static::SUCCESS;
    }
}
