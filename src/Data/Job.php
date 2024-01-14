<?php

namespace VincentBean\HorizonDashboard\Data;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class Job implements Arrayable
{
    public function __construct(
        public string $id,
        public string $connection,
        public string $queue,
        public string $name,
        public string $status,
        public array $payload,
        public ?string $exception = '',
        public ?Carbon $failedAt = null,
        public ?Carbon $completedAt = null,
        public ?Carbon $reservedAt = null,
        public ?string $retriedBy = null
    ) {
    }

    public function getTriesString(): string
    {
        $defaults = collect(config('horizon.defaults', []));
        $maxTriesConfig = collect(config('horizon.environments.' . config('app.env')))
            ->map(fn($data, $key) => array_merge($defaults[$key] ?? [], $data))
            ->filter(fn($supervisor, $key) => in_array($this->queue, $supervisor['queue']))
            ->max('tries') ?? 'Inf.';

        $attempts = $this->payload['attempts'] ?? 0;
        $maxTries = $this->payload['maxTries'] ?? $maxTriesConfig;

        return "$attempts / $maxTries";
    }

    public function getTagString(): string
    {
        return Str::limit(implode(', ', $this->payload['tags'] ?? []));
    }

    public function getStatistic(): ?JobStatistic
    {
        return JobStatistic::query()
            ->where('uuid', $this->id)
            ->first();
    }

    public function isDelayed(): bool
    {
        $command = unserialize(data_get($this->payload, 'data.command'));

        if ($command->delay === null) {
            return false;
        }

        return now()->lessThan($command->delay);
    }

    public function getDelayedUntil(): string
    {
        $command = unserialize(data_get($this->payload, 'data.command'));

        if ($command->delay === null) {
            return false;
        }

        return $command->delay->toDateTimeString();
    }

    public static function fromStdClass(\stdClass $class): static
    {
        return new static(
            $class->id,
            $class->connection ?? '',
            $class->queue ?? '',
            $class->name ?? '',
            $class->status ?? '',
            json_decode($class->payload, true),
            $class->exception ?? '',
            blank($class->failed_at) ? null : Carbon::createFromTimestamp($class->failed_at ?? 0),
            blank($class->completed_at) ? null : Carbon::createFromTimestamp($class->completed_at ?? 0),
            blank($class->reserved_at) ? null : Carbon::createFromTimestamp($class->reserved_at ?? 0),
            $class->retried_by ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'connection' => $this->connection,
            'queue' => $this->queue,
            'name' => $this->name,
            'status' => $this->status,
            'payload' => $this->payload,
            'exception' => $this->exception,
            'failed_at' => !$this->failedAt->timestamp ? '' : $this->failedAt->toDateTimeString(),
            'completed_at' => !$this->completedAt->timestamp ? '' : $this->completedAt->toDateTimeString(),
            'reserved_at' => !$this->reservedAt->timestamp ? '' : $this->reservedAt->toDateTimeString(),
            'retried_by' => $this->retriedBy,
            'viewData' => [
                'tries' => $this->getTriesString(),
                'tags' => $this->getTagString(),
                'pushed_at' => Carbon::createFromTimestamp($this->payload['pushedAt'] ?? 0)->toDateTimeString(),
                'runtime' => optional($this->getStatistic())->runtime,
                'delayed' => $this->isDelayed(),
                'delayed_until' => $this->getDelayedUntil()
            ]
        ];
    }
}
