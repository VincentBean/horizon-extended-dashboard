<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Cards;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Contracts\WorkloadRepository;
use Laravel\Horizon\Http\Controllers\DashboardStatsController;
use Livewire\Component;
use stdClass;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Helpers\TimeHelper;

class WorkloadCard extends Component
{
    public function render(WorkloadRepository $workload, TimeHelper $timeHelper): View
    {
        $queues = collect($workload->get())
            ->map(function(array $workload) use ($timeHelper): array {

                $workload['wait'] = $timeHelper->secondsToTime($workload['wait']);

                return $workload;
            });

        return view('horizondashboard::livewire.cards.workload-card', ['queues' => $queues]);
    }
}
