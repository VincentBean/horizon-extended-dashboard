<?php

namespace VincentBean\HorizonDashboard\Http\Livewire;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\Factory as Queue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Horizon\Contracts\JobRepository;
use Livewire\Component;
use VincentBean\HorizonDashboard\Actions\RetrieveJobData;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Models\JobException;

class JobDetail extends Component
{
    public string $jobIdentifier = '';

    public array $job = [];

    public function mount(string $jobIdentifier)
    {
        $this->jobIdentifier = $jobIdentifier;

        $this->retrieveJob();
    }

    public function retrieveJob(): void
    {
        /** @var JobRepository $repository */
        $repository = app(JobRepository::class);

        $job = $repository->getJobs([$this->jobIdentifier])->first();

        if ($job === null) {
            return;
        }

        $this->job = Job::fromStdClass($job)->toArray();
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.job-detail');
    }

    public function getDetails(): array
    {
        /** @var RetrieveJobData $retriever */
        $retriever = app(RetrieveJobData::class);

        return $retriever->handle($this->job);
    }

    public function getData(ShouldQueue $job)
    {
        $ignoreVars = ['backoff', 'tries', 'maxExceptions', 'queue', 'connection', 'chained', 'delay'];

        return collect(get_object_vars($job))
            ->reject(fn(mixed $value, string $key): bool => blank($value) || in_array($key, $ignoreVars))
            ->map(function (mixed $value, string $key) {

                if (is_a($value, Arrayable::class)) {
                    $value = $value->toArray();
                }

                if (is_bool($value)) {
                    $value = $value ? 'Yes' : 'No';
                }

                return [
                    'name' => $key,
                    'value' => is_string($value)
                        ? $value
                        : json_encode($value)
                ];

            });
    }

    public function isUnique(): bool
    {
        return is_a($this->getCommand(), ShouldBeUnique::class);
    }

    public function getUniqueId(): ?string
    {
        $job = $this->getCommand();

        if (!is_a($job, ShouldBeUnique::class) || !method_exists($job, 'uniqueId')) {
            return null;
        }

        return $job->uniqueId();
    }

    public function hasChain(): bool
    {
        return count($this->getCommand()->chained ?? []) > 0;
    }

    public function getChain(): array
    {
        $chain = [];

        $jobs = $this->getCommand()->chained;

        foreach ($jobs as $serializedJob) {
            $job = unserialize($serializedJob);

            $chain[] = [
                'name' => get_class($job),
                'value' => json_encode($this->getData($job)->values()->mapWithKeys(fn(array $data) => [$data['name'] => $data['value']]))
            ];
        }

        return $chain;
    }

    public function enQueue(Queue $queue, JobRepository $jobs): void
    {
        $currentPayload = $this->job['payload'];


        $newId = Str::uuid();

        $retryUntil = $currentPayload['retryUntil'] ?? $currentPayload['timeoutAt'] ?? null;

        $retryUntil = $retryUntil
            ? CarbonImmutable::now()->addSeconds(ceil($retryUntil - $currentPayload['pushedAt']))->getTimestamp()
            : null;

        $rawPayload = json_encode(array_merge($currentPayload, [
            'id' => $newId,
            'uuid' => $newId,
            'attempts' => 0,
            'retry_of' => $this->jobIdentifier,
            'retryUntil' => $retryUntil
        ]));

        $queue->connection($this->job['connection'])->pushRaw($rawPayload, $this->job['queue']);

        $jobs->storeRetryReference($this->jobIdentifier, $newId);

        $this->retrieveJob();
    }
    
    public function getExceptions(): Collection
    {
        return JobException::query()
            ->where('uuid', $this->jobIdentifier)
            ->orderBy('attempt')
            ->get();
    }

    public function hasRetries(): bool
    {
        return isset($this->job['retried_by']) && !blank($this->job['retried_by']);
    }

    public function getRetries(): array
    {
        return json_decode($this->job['retried_by'], true) ?? [];
    }

    protected function getCommand(): ShouldQueue
    {
        return unserialize(data_get($this->job, 'payload.data.command'));
    }
}


