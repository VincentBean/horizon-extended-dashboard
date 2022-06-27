<?php

namespace VincentBean\HorizonDashboard\Actions\Statistics;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Horizon\Contracts\MetricsRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class TakeQueueSnapshot
{
    public function __construct(
        protected MetricsRepository $metricsRepository,
        protected SupervisorRepository $supervisors
    ) {
    }

    public function handle(string $queue): void
    {
        $lastSnapshot = $this->getLastSnapshot($queue);
        $since = $lastSnapshot === null ? now()->subMinutes(15)->utc() : $lastSnapshot->snapshot_at;

        $snapshot = $this->metricsRepository->snapshotsForQueue($queue);

        $cpuMem = $this->getCpuMemory($queue);

        QueueStatistic::create([
            'queue' => $queue,

            'job_pushed_count' => $this->getJobStatisticCount($queue,
                fn(Builder $query) => $query->where('queued_at', '>', $since->getTimestamp())
            ),

            'job_completed_count' => $this->getJobStatisticCount($queue,
                fn(Builder $query) => $query->where('finished_at', '>', $since->getTimestamp())
            ),

            'job_fail_count' => $this->getJobStatisticCount($queue,
                fn(Builder $query) => $query->where('finished_at', '>', $since->getTimestamp())->where('failed', true)
            ),

            'throughput' => $this->metricsRepository->throughputForQueue($queue),

            'wait' => $snapshot['wait'] ?? 0,

            'jobs_per_minute' => $this->metricsRepository->jobsProcessedPerMinute(),

            'average_runtime' => JobStatistic::query()
                ->where('queue', $queue)
                ->whereDate('finished_at', '>', $since)
                ->average('runtime') ?? 0,

            'jobs' => JobStatistic::query()
                ->selectRaw('job_information_id, COUNT(job_information_id) AS count')
                ->where('reserved_at', '>', $since->getTimestamp())
                ->where('queue', $queue)
                ->groupBy('job_information_id')
                ->get()
                ->mapWithKeys(fn(JobStatistic $statistic) => [$statistic->jobInformation->class => $statistic->count])
                ->toArray(),

            'cpu_usage' => $cpuMem['cpu'] ?? 0,
            'memory_usage' => $cpuMem['memory'] ?? 0,

            'snapshot_at' => now()
        ]);
    }

    protected function getLastSnapshot(string $queue): ?QueueStatistic
    {
        return QueueStatistic::query()
            ->where('queue', $queue)
            ->orderByDesc('snapshot_at')
            ->first();
    }

    protected function getJobStatisticCount(string $queue, ?callable $callback = null): int
    {
        $query = JobStatistic::query()
            ->where('queue', $queue);

        if ($callback !== null) {
            $callback($query);
        }

        return $query->count();
    }

    public function getCpuMemory(string $queue): array
    {
        $pid = $this->getPid($queue);

        $result = shell_exec("ps -p $pid -o %cpu,%mem");

        $result = explode("\n", $result);

        if (count($result) != 3) {
            return [];
        }

        $result = explode(' ', $result[1]);

        if (count($result) != 4) {
            return [];
        }

        return [
            'cpu' => (float) $result[1],
            'memory' => (float) $result[3],
        ];
    }
    
    protected function getPid(string $queue): ?string
    {
        $supervisors = $this->supervisors->all();

        foreach ($supervisors as $supervisor) {
            $queues = explode(',', $supervisor->options['queue']);

            if (in_array($queue, $queues)) {
                return $supervisor->pid;
            }

        }

        return null;
    }
    
}
