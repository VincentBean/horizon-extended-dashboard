<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Jobs\AggregateJobStatisticsJob;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class AggregateJobStatisticsCommand extends Command
{
    protected $signature = 'horizon-dashboard:aggregate-job-statistics {--interval=} {--keep=}';

    protected $description = 'Aggregate the data in the job_statistics table
                --interval: Interval in minutes
                --keep: Don\'t aggregate minutes';

    public function handle(RetrieveQueues $retrieveQueues)
    {
        $interval = $this->option('interval');
        $keep = $this->option('keep');

        $lastAggregated = JobStatistic::query()
            ->where('aggregated', true)
            ->orderByDesc('queued_at')
            ->first();

        /** @var Carbon $startDate */
        $startDate = $lastAggregated !== null
            ? $lastAggregated->queued_at
            : JobStatistic::query()
                ->orderBy('queued_at')
                ->firstOrFail()->queued_at;

        $endDate = now()->subMinutes($keep);

        $minuteDiff = $startDate->diffInMinutes($endDate);

        if ($minuteDiff < 0) {
            return static::SUCCESS;
        }

        $steps = ceil($minuteDiff / $interval);

        $this->info("Aggregating from {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}");

        for ($i = 0; $i < $steps; $i++) {
            $from = (clone $startDate)->addMinutes($i * $interval);
            $to = (clone $startDate)->addMinutes(($i + 1) * $interval);

            $jobIdsAndQueue = JobStatistic::query()
                ->where('aggregated', false)
                ->where('queued_at', '>=', $from->getPreciseTimestamp(3))
                ->where('queued_at', '<', $to->getPreciseTimestamp(3))
                ->distinct();

            $jobIds = $jobIdsAndQueue->select(['job_information_id'])->get()->pluck('job_information_id');
            $queues = $jobIdsAndQueue->select(['queue'])->get()->pluck('queue');

            foreach ($queues as $queue) {
                foreach ($jobIds as $jobId) {
                    AggregateJobStatisticsJob::dispatch($jobId, $queue, $from, $to);
                }
            }

        }

        $this->info('Dispatched aggregate jobs into the queue');

        return static::SUCCESS;
    }

}
