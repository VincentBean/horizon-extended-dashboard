<?php

namespace VincentBean\HorizonDashboard\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Laravel\Horizon\Contracts\JobRepository;
use Livewire\Component;
use stdClass;
use VincentBean\HorizonDashboard\Data\Job;
use VincentBean\HorizonDashboard\Models\Batch;

class BatchList extends Component
{

    public function render(): View
    {
        return view('horizondashboard::livewire.batch-list', [
            'batches' => Batch::paginate(10)
        ]);
    }

}
