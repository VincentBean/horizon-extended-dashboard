<?php

namespace VincentBean\HorizonDashboard\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class AggregateQueueStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $backoff = 10;
    public int $tries = 5;

    public function __construct(
        public string $jobQueue,
        public Carbon $from,
        public Carbon $to,
    ){
    }

    public function handle()
    {
        $statisticsQuery = QueueStatistic::query()
            ->where('queue', $this->jobQueue)
            ->where('aggregated', false)
            ->where('snapshot_at', '>=', $this->from->toDateTimeString())
            ->where('snapshot_at', '<', $this->to->toDateTimeString());

        $statistics = $statisticsQuery->get();

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

        QueueStatistic::query()->create([
            'queue' => $this->jobQueue,
            'job_pushed_count' => $statistics->sum('job_pushed_count') ?? 0,
            'job_completed_count' => $statistics->sum('job_completed_count') ?? 0,
            'job_fail_count' => $statistics->sum('job_fail_count') ?? 0,
            'jobs_per_minute' => $statistics->average('jobs_per_minute') ?? 0,
            'throughput' => $statistics->average('throughput') ?? 0,
            'wait' => $statistics->average('wait') ?? 0,
            'average_runtime' => $statistics->average('average_runtime') ?? 0,
            'jobs' => $jobs,
            'snapshot_at' => $this->from,
            'aggregated' => true,
        ]);

        $statisticsQuery->delete();
    }
}
