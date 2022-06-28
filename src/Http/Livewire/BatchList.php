<?php

namespace VincentBean\HorizonDashboard\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use VincentBean\HorizonDashboard\Models\Batch;

class BatchList extends Component
{

    public function render(): View
    {
        return view('horizondashboard::livewire.batch-list', [
            'batches' => Batch::query()->orderByDesc('created_at')->paginate(10)
        ]);
    }

}
