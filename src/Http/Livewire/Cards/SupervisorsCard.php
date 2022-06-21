<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Cards;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Laravel\Horizon\Contracts\WorkloadRepository;
use Laravel\Horizon\Http\Controllers\DashboardStatsController;
use Livewire\Component;
use stdClass;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Helpers\TimeHelper;

class SupervisorsCard extends Component
{
    public $listeners = [
        'updateSupervisorStatus' => '$refresh'
    ];

    public function render(MasterSupervisorRepository $masters,
                           SupervisorRepository $supervisors): View
    {
        $masters = collect($masters->all())->keyBy('name')->sortBy('name');

        $supervisors = collect($supervisors->all())->sortBy('name')->groupBy('master');

        $data = $masters->each(function ($master, $name) use ($supervisors) {
            $master->supervisors = $supervisors->get($name);
        });

        return view('horizondashboard::livewire.cards.supervisors-card', ['data' => $data]);
    }
}
