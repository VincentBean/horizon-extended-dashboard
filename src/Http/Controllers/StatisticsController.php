<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class StatisticsController extends Controller
{
    public function index(RetrieveQueues $retrieveQueues)
    {
        return view('horizondashboard::statistics.index', [
            'queues' => $retrieveQueues->handle(),
            'jobs' => JobInformation::all()
        ]);
    }

    public function job(int $id)
    {
        return view('horizondashboard::statistics.job', ['job' => JobInformation::find($id)]);
    }

    public function queue(string $queue)
    {
        return view('horizondashboard::statistics.queue', [
            'queue' => $queue,
            'averageJobsPerMinute' => round(QueueStatistic::query()->where('queue', $queue)->average('jobs_per_minute'), 2),
            'averageRuntime' => round(QueueStatistic::query()->where('queue', $queue)->average('average_runtime'), 2),
            'averageThroughput' => round(QueueStatistic::query()->where('queue', $queue)->average('throughput'), 2),
            'averageWait' => round(QueueStatistic::query()->where('queue', $queue)->average('wait'), 2),
        ]);
    }

    public function jobOverview()
    {
        return view('horizondashboard::statistics.job-overview', [
            'jobs' => JobInformation::all()->sortByDesc(fn(JobInformation $info) => $info->averageRuntime())
        ]);
    }

    public function queueOverview(RetrieveQueues $retrieveQueues)
    {
        $queues = $retrieveQueues->handle()
            ->mapWithKeys(function(string $queue) {

                $query = QueueStatistic::query()
                    ->where('queue', $queue);

                return [
                    $queue => [
                        'average_job_pushed' => $query->average('job_pushed_count'),
                        'total_job_pushed' => $query->sum('job_pushed_count'),

                        'average_job_completed' => $query->average('job_completed_count'),
                        'total_job_completed' => $query->sum('job_completed_count'),

                        'average_job_failed' => $query->average('job_fail_count'),
                        'total_job_failed' => $query->sum('job_fail_count'),

                        'average_jobs_per_min' => $query->average('jobs_per_minute'),
                        'average_throughput' => $query->average('throughput'),
                        'average_wait' => $query->average('wait'),
                        'average_runtime' => $query->average('average_runtime'),
                    ]
                ];
            });

        return view('horizondashboard::statistics.queue-overview', [
            'queues' => $queues
        ]);
    }
}
