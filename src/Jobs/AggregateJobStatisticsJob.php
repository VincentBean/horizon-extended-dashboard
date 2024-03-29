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

class AggregateJobStatisticsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $backoff = 10;
    public int $tries = 5;
    public int $timeout = 3600;

    public function __construct(
        public int $jobInformationId,
        public string $jobQueue,
        public Carbon $from,
        public Carbon $to,
    ) {
    }

    public function handle()
    {
        $statisticsQuery = JobStatistic::query()
            ->where('aggregated', false)
            ->where('queued_at', '>=', $this->from->getTimestamp())
            ->where('queued_at', '<', $this->to->getTimestamp())
            ->where('job_information_id', $this->jobInformationId)
            ->where('queue', $this->jobQueue);

        $count = $statisticsQuery->count();

        if ($count === 0) {
            return;
        }

        $aggregatedData = [
            'job_information_id' => $this->jobInformationId,
            'queued_at' => $this->from->getTimestamp(),
            'queue' => $this->jobQueue,
            'runtime' => $statisticsQuery->average('runtime') ?? 0,
            'attempts' => $statisticsQuery->average('attempts') ?? 0,
            'aggregated' => true,
            'aggregated_job_count' => $count,
            'aggregated_failed_count' => $statisticsQuery->clone()->where('failed', true)->count()
        ];

        $statisticsQuery->delete();

        JobStatistic::query()->create($aggregatedData);
    }

    public function uniqueId(): string
    {
        return implode('-', $this->tags());
    }

    public function tags(): array
    {
        return [
            $this->jobInformationId,
            $this->jobQueue,
            $this->from->toDateTimeString(),
            $this->to->toDateTimeString(),
        ];
    }
}
