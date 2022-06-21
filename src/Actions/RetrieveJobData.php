<?php

namespace VincentBean\HorizonDashboard\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use VincentBean\HorizonDashboard\Helpers\TitleHelper;
use VincentBean\HorizonDashboard\Helpers\ValueHelper;
use VincentBean\HorizonDashboard\Models\Batch;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class RetrieveJobData
{
    protected const FIELDS = [
        'id',
        'viewData.pushed_at',
        'completed_at',
        'failed_at',
        'reserved_at',
        'payload.type',
        'payload.failOnTimeout',
        'payload.maxTries',
        'payload.maxExceptions',
        'payload.backoff',
        'payload.timeout',
        'payload.retryUntil',
    ];

    public function __construct(
        protected ValueHelper $valueHelper,
        protected TitleHelper $titleHelper
    )
    {
    }

    public function handle(array $job): array
    {
        $data = [];

        /** @var JobStatistic $statistic */
        $statistic = JobStatistic::query()
            ->where('uuid', $job['id'])
            ->first();


        foreach (static::FIELDS as $field) {

            $value = data_get($job, $field);

            if (blank($value)) {
                continue;
            }

            $data[] = [
                'name' => $this->titleHelper->formatToTitle($field),
                'value' => $this->valueHelper->getValue($value)
            ];

        }

        if (!is_null($delay = $this->getDelay($job))) {
            $data[] = $delay;
        }

        if ($statistic !== null) {
            if ($statistic->runtime !== null) {
                $data[] = [
                    'name' => 'Runtime',
                    'value' => $statistic->runtime . 's'
                ];
            }

            $data[] = [
                'name' => 'Average Runtime',
                'value' => $statistic->jobInformation->averageRuntime() . 's'
            ];
        }

        return $data;
    }

    protected function getDelay(array $job): ?array
    {
        $command = unserialize(data_get($job, 'payload.data.command'));

        if ($command->delay === null || !is_a($command->delay, Carbon::class)) {
            return null;
        }

        return [
            'name' => 'Delay Until',
            'value' => $command->delay->toDateTimeString()
        ];
    }
}
