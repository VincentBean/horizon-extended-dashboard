<?php

namespace VincentBean\HorizonDashboard\Http\Livewire;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Horizon\Contracts\JobRepository;
use Livewire\Component;
use VincentBean\HorizonDashboard\Actions\RetrieveBatchData;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Models\Batch;

class BatchDetail extends Component
{
    public string $batchIdentifier = '';

    public function mount(string $batchIdentifier)
    {
        $this->batchIdentifier = $batchIdentifier;
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.batch-detail');
    }

    public function getDetails(): array
    {
        /** @var RetrieveBatchData $retriever */
        $retriever = app(RetrieveBatchData::class);

        return $retriever->handle($this->batchIdentifier);
    }

    public function getFailedJobs(): array
    {
        /** @var JobRepository $repository */
        $repository = app(JobRepository::class);

        return collect($repository->getJobs(Batch::find($this->batchIdentifier)->failed_job_ids))
            ->map(fn($job) => Job::fromStdClass($job))
            ->toArray();
    }
}
