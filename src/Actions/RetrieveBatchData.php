<?php

namespace VincentBean\HorizonDashboard\Actions;

use VincentBean\HorizonDashboard\Helpers\TitleHelper;
use VincentBean\HorizonDashboard\Helpers\ValueHelper;
use VincentBean\HorizonDashboard\Models\Batch;

class RetrieveBatchData
{
    protected const FIELDS = [
        'id',
        'name',
        'total_jobs',
        'pending_jobs',
        'failed_jobs',
        'cancelled',
        'cancelled_at',
        'created_at',
        'finished_at',
    ];

    public function __construct(
        protected ValueHelper $valueHelper,
        protected TitleHelper $titleHelper
    ) {
    }

    public function handle(string $identifier): array
    {
        $data = [];

        $batch = Batch::find($identifier);

        foreach (static::FIELDS as $field) {

            $value = $batch->$field;

            if (blank($value)) {
                continue;
            }

            $data[] = [
                'name' => $this->titleHelper->formatToTitle($field),
                'value' => $this->valueHelper->getValue($value)
            ];

        }

        return $data;
    }
}
