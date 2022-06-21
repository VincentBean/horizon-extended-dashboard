<?php

namespace VincentBean\HorizonDashboard\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TitleHelper
{
    public function formatToTitle(string $title): string
    {
        return Str::title(
            str_replace('-', ' ', Str::kebab(str_replace('_', ' ', Arr::last(explode('.', $title)))))
        );
    }
}
