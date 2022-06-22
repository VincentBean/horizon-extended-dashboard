<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Http\Controllers\DashboardStatsController;
use Livewire\Component;
use stdClass;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Helpers\TimeHelper;

class TopBar extends Component
{
    public $listeners = [
        'updateSupervisorStatus' => '$refresh'
    ];

    public function render(DashboardStatsController $controller, TimeHelper $timeHelper): View
    {
        $data = $controller->index();

        $data['wait'] = collect($data['wait'])
            ->mapWithKeys(fn(int $wait, string $queue) => [Str::afterLast(':', $queue) => $timeHelper->secondsToTime($wait)]);

        return view('horizondashboard::livewire.components.top-bar', ['data' => $data]);
    }
}
