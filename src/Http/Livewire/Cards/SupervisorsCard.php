<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Cards;

use Illuminate\Contracts\View\View;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Livewire\Component;

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
