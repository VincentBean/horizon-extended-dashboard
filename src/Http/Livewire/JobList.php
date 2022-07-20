<?php

namespace VincentBean\HorizonDashboard\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Laravel\Horizon\Contracts\JobRepository;
use Livewire\Component;
use stdClass;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Data\Job;

class JobList extends Component
{
    public string $type = '';
    public string $queue = '';

    public int $fromIndex = -1;

    public bool $hasMore = true;

    public array $jobs = [];

    public function mount(string $type, string $queue = '')
    {
        $this->type = $type;
        $this->queue = $queue;

        $this->jobs = $this->getJobs()->toArray();
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.job-list');
    }

    public function loadMore(): void
    {
        $retrievedCount = 0;

        do {
            $retrievedJobs = $this->getJobs();

            $this->jobs = array_merge($this->jobs, $retrievedJobs->toArray());

            if ($retrievedJobs->isEmpty()) {
                $this->hasMore = false;
                break;
            }

            $retrievedCount += $retrievedJobs->count();

        } while($retrievedCount < 50);

    }

    public function getJobs(): Collection
    {
        /** @var JobRepository $repository */
        $repository = app(JobRepository::class);

        $jobs = match ($this->type) {
            'recent' => $repository->getRecent($this->fromIndex),
            'pending' => $repository->getPending($this->fromIndex),
            'completed' => $repository->getCompleted($this->fromIndex),
            'failed' => $repository->getFailed($this->fromIndex),
        };

        if (count($jobs) < 50) {
            $this->hasMore = false;
        }

        $jobs = $jobs->map(fn(stdClass $job) => Job::fromStdClass($job));


        if (!blank($this->queue)) {
            $jobs = $jobs->filter(fn(Job $job) => $job->queue == $this->queue);
        }

        $this->fromIndex += $jobs->count();

        return $jobs;
    }

    public function getQueues(): array
    {
        /** @var RetrieveQueues $action */
        $action = app(RetrieveQueues::class);

        return $action->handle()->toArray();
    }
}
