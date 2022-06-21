<?php

namespace VincentBean\HorizonDashboard\Helpers;

class TimeHelper
{
    public function secondsToTime(int $seconds): string
    {
        if ($seconds < 60) {
            return "$seconds seconds";
        }

        if ($seconds < (60*60)) {
            return round($seconds/60, 2) . ' minutes';
        }

        if ($seconds < (60*60*24)) {
            return round($seconds/60/60, 2) . ' hours';
        }

        return round($seconds/60/60/24, 2) . ' days';
    }
}
