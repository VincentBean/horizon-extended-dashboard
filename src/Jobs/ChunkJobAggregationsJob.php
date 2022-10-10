<?php

namespace VincentBean\HorizonDashboard\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class ChunkJobAggregationsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected int $keep,
        protected int $interval,
    ) {
    }

    public function handle(): void
    {
        /** @var Carbon $startDate */
        $startDate = JobStatistic::query()
            ->where('aggregated', false)
            ->orderBy('queued_at')
            ->firstOrFail()->queued_at;

        $endDate = now()->subMinutes($this->keep);

        $minuteDiff = $startDate->diffInMinutes($endDate);

        if ($minuteDiff < 0) {
            return;
        }

        $steps = ceil($minuteDiff / $this->interval);

        for ($i = 0; $i < $steps; $i++) {
            $from = $startDate->copy()->addMinutes($i * $this->interval);
            $to = $startDate->copy()->addMinutes(($i + 1) * $this->interval);

            $jobIdsAndQueue = JobStatistic::query()
                ->where('aggregated', false)
                ->where('queued_at', '>=', $from->getTimestamp())
                ->where('queued_at', '<', $to->getTimestamp())
                ->distinct();

            $jobIds = $jobIdsAndQueue->select(['job_information_id'])->get()->pluck('job_information_id');
            $queues = $jobIdsAndQueue->select(['queue'])->get()->pluck('queue');

            foreach ($queues as $queue) {
                foreach ($jobIds as $jobId) {
                    AggregateJobStatisticsJob::dispatchSync($jobId, $queue, $from, $to);
                }
            }

        }
    }

}
