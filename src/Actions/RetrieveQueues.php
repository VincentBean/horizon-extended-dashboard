<?php

namespace VincentBean\HorizonDashboard\Actions;

use Illuminate\Support\Collection;

class RetrieveQueues
{
    public function handle(): Collection
    {
        $defaults = collect(config('horizon.defaults', []));

        return collect(config('horizon.environments.' . config('app.env')))
            ->map(fn($data, $key) => array_merge($defaults[$key] ?? [], $data)['queue'])
            ->flatten()
            ->unique();
    }
}
