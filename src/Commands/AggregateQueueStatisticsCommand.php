<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class AggregateQueueStatisticsCommand extends Command
{
    protected $signature = 'horizon-dashboard:aggregate-queue-statistics {--interval=} {--keep=}';

    protected $description = 'Aggregate the data in the queue_statistics table
                --interval: Interval in minutes
                --keep: Don\'t aggregate minutes';

    public function handle()
    {
        $interval = $this->option('interval');
        $keep = $this->option('keep');

        $lastAggregated = QueueStatistic::query()
            ->where('aggregated', true)
            ->orderByDesc('snapshot_at')
            ->first();

        /** @var Carbon $startDate */
        $startDate = $lastAggregated !== null
            ? $lastAggregated->snapshot_at
            : QueueStatistic::query()
                ->orderBy('snapshot_at')
                ->firstOrFail()->snapshot_at;

        $endDate = now()->subMinutes($keep);

        $minuteDiff = $startDate->diffInMinutes($endDate);
        $steps = ceil($minuteDiff / $interval);

        $this->info("Aggregating from {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}");

        for ($i = 0; $i < $steps; $i++) {
            $from = (clone $startDate)->addMinutes($i * $interval);
            $to = (clone $startDate)->addMinutes(($i + 1) * $interval);

            $targetStatisticsQuery = QueueStatistic::query()
                ->where('aggregated', false)
                ->where('snapshot_at', '>=', $from->toDateTimeString())
                ->where('snapshot_at', '<', $to->toDateTimeString());

            $targetStatistics = $targetStatisticsQuery->get();

            $queues = $targetStatistics->groupBy('queue');

            /**
             * @var string $queue
             * @var Collection<JobStatistic> $statistics
             */
            foreach ($queues as $queue => $statistics) {

                if ($statistics->count() === 1) {
                    $statistics->first()->update([
                        'aggregated' => true
                    ]);
                    continue;
                }

                $jobs = [];

                foreach ($statistics as $statistic) {
                    foreach ($statistic->jobs as $job => $count) {
                        if (array_key_exists($job, $jobs)) {
                            $jobs[$job]++;
                        } else {
                            $jobs[$job] = $count;
                        }
                    }
                }

                QueueStatistic::create([
                    'queue' => $queue,
                    'job_pushed_count' => $statistics->sum('job_pushed_count'),
                    'job_completed_count' => $statistics->sum('job_completed_count'),
                    'job_fail_count' => $statistics->sum('job_fail_count'),
                    'jobs_per_minute' => $statistics->average('jobs_per_minute'),
                    'throughput' => $statistics->average('throughput'),
                    'wait' => $statistics->average('wait'),
                    'average_runtime' => $statistics->average('average_runtime'),
                    'jobs' => $jobs,
                    'snapshot_at' => $from,
                    'aggregated' => true,
                ]);

                QueueStatistic::query()
                    ->whereIn('id', $statistics->pluck('id'))
                    ->delete();

                $this->info("Aggregated {$statistics->count()} entries from {$from->toDateTimeString()} to {$to->toDateTimeString()} for on queue $queue");
            }
        }

        return static::SUCCESS;
    }

}
