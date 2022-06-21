<?php

namespace VincentBean\HorizonDashboard\Helpers;

use Illuminate\Support\Carbon;

class ValueHelper
{
    public function getValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_a($value, Carbon::class)) {
            return $value->toDateTimeString();
        }

        return (string) $value;
    }
}
