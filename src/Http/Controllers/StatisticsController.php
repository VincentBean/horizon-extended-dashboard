<?php

namespace VincentBean\HorizonDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
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
}
