<?php

namespace VincentBean\HorizonDashboard\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class AggregateJobStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $backoff = 10;
    public int $tries = 5;

    public function __construct(
        public int    $jobInformationId,
        public string $jobQueue,
        public Carbon $from,
        public Carbon $to,
    ) {
    }

    public function handle()
    {
        $statisticsQuery = JobStatistic::query()
            ->where('aggregated', false)
            ->where('queued_at', '>=', $this->from->getPreciseTimestamp(3))
            ->where('queued_at', '<', $this->to->getPreciseTimestamp(3))
            ->whereNotNull('finished_at')
            ->where('job_information_id', $this->jobInformationId)
            ->where('queue', $this->jobQueue);

        $count = $statisticsQuery->count();

        if ($count === 0) {
            return;
        }

        JobStatistic::query()->create([
            'job_information_id' => $this->jobInformationId,
            'queued_at' => $this->from->getPreciseTimestamp(3),
            'queue' => $this->jobQueue,
            'runtime' => $statisticsQuery->average('runtime') ?? 0,
            'attempts' => $statisticsQuery->average('attempts') ?? 0,
            'aggregated' => true,
            'aggregated_job_count' => $count,
            'aggregated_failed_count' => $statisticsQuery->where('failed', true)->count()
        ]);

        $statisticsQuery->delete();
    }
}
