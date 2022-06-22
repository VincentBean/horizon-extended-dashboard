<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class AggregateJobStatisticsCommand extends Command
{
    protected $signature = 'horizon-dashboard:aggregate-job-statistics {--interval=} {--keep=}';

    protected $description = 'Aggregate the data in the job_statistics table
                --interval: Interval in minutes
                --keep: Don\'t aggregate minutes';

    public function handle()
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
        $steps = ceil($minuteDiff / $interval);

        $this->info("Aggregating from {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()} in $steps steps");

        for ($i = 0; $i < $steps; $i++) {
            $from = (clone $startDate)->addMinutes($i * $interval);
            $to = (clone $startDate)->addMinutes(($i + 1) * $interval);

            $targetStatisticsQuery = JobStatistic::query()
                ->where('aggregated', false)
                ->where('queued_at', '>=', $from->getPreciseTimestamp(3))
                ->where('queued_at', '<', $to->getPreciseTimestamp(3));

            $targetStatistics = $targetStatisticsQuery
                ->get();

            $grouped = $targetStatistics->groupBy('job_information_id')
                ->map(fn(Collection $group) => $group->groupBy('queue'));

            /**
             * @var int $jobId
             * @var Collection $queues
             */
            foreach ($grouped as $jobId => $queues) {
                /**
                 * @var string $queue
                 * @var Collection<JobStatistic> $statistics
                 */
                foreach ($queues as $queue => $statistics) {

                    if ($statistics->count() === 1) {
                        $statistics->first()->update([
                            'aggregated' => true,
                            'aggregated_job_count' => 1,
                            'aggregated_failed_count' => $statistics->first()->failed ? 1 : 0
                        ]);
                        continue;
                    }

                    $aggregated = JobStatistic::create([
                        'job_information_id' => $jobId,
                        'aggregated' => true,
                        'queued_at' => $from->getPreciseTimestamp(3),
                        'queue' => $queue,
                        'runtime' => $statistics->average('runtime'),
                        'attempts' => $statistics->average('attempts'),
                        'aggregated_job_count' => $statistics->count(),
                        'aggregated_failed_count' => $statistics->where('failed', true)->count()
                    ]);

                    JobStatistic::query()
                        ->whereIn('id', $statistics->pluck('id'))
                        ->delete();

                    $this->info("Aggregated {$aggregated->aggregated_job_count} from {$from->toDateTimeString()} to {$to->toDateTimeString()} jobs for ID $jobId on queue $queue");
                }
            }
        }

        return static::SUCCESS;
    }

}
