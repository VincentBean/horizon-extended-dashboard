<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Cards;

use Illuminate\Contracts\View\View;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Livewire\Component;
use VincentBean\HorizonDashboard\Http\Controllers\GetCpuMemoryUsage;

class SupervisorsCard extends Component
{
    public $listeners = [
        'updateSupervisorStatus' => '$refresh'
    ];

    public function render(
        MasterSupervisorRepository $masters,
        SupervisorRepository       $supervisors,
        GetCpuMemoryUsage          $getCpuMemoryUsage
    ): View
    {
        $masters = collect($masters->all())->keyBy('name')->sortBy('name');

        $supervisors = collect($supervisors->all())->sortBy('name')->groupBy('master');

        $data = $masters->each(function ($master, $name) use ($supervisors, $getCpuMemoryUsage) {
            $master->supervisors = collect($supervisors->get($name))
                ->each(fn($supervisor) => $supervisor->cpu_mem = $getCpuMemoryUsage->getForPid($supervisor->pid));

            $master->cpu_mem = $getCpuMemoryUsage->getForPid($master->pid);
        });

        return view('horizondashboard::livewire.cards.supervisors-card', ['data' => $data]);
    }
}
