<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use VincentBean\HorizonDashboard\Jobs\AggregateQueueStatisticsJob;
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

        if ($minuteDiff < 0) {
            return static::SUCCESS;
        }

        $steps = ceil($minuteDiff / $interval);

        $this->info("Aggregating from {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}");

        for ($i = 0; $i < $steps; $i++) {
            $from = (clone $startDate)->addMinutes($i * $interval);
            $to = (clone $startDate)->addMinutes(($i + 1) * $interval);

            QueueStatistic::query()
                ->where('aggregated', false)
                ->where('snapshot_at', '>=', $from->toDateTimeString())
                ->where('snapshot_at', '<', $to->toDateTimeString())
                ->select(['queue'])
                ->distinct()
                ->get()
                ->each(fn(QueueStatistic $statistic) => AggregateQueueStatisticsJob::dispatch($statistic->queue, $from, $to));

        }

        $this->info('Dispatched aggregate jobs into the queue');

        return static::SUCCESS;
    }

}
