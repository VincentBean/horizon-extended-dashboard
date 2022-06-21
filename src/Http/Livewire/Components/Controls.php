<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Laravel\Horizon\Console\ContinueCommand;
use Laravel\Horizon\Console\ContinueSupervisorCommand;
use Laravel\Horizon\Console\PauseCommand;
use Laravel\Horizon\Console\PauseSupervisorCommand;
use Laravel\Horizon\Console\TerminateCommand;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Laravel\Horizon\Contracts\SupervisorRepository;
use Livewire\Component;

class Controls extends Component
{
    public bool $poll = false;
    public int $pollCount = 0;

    public string $supervisor = '';

    public function render(): View
    {
        return view('horizondashboard::livewire.components.controls', ['status' => $this->currentStatus()]);
    }

    public function hydrate()
    {
        if ($this->poll) {
            $this->pollCount++;

            if ($this->pollCount > 5) {
                $this->poll = false;
            }
        }
    }

    public function pause()
    {
        define('SIGUSR2', 12);

        if (blank($this->supervisor)) {
            Artisan::call(PauseCommand::class);
        } else {
            Artisan::call(PauseSupervisorCommand::class, ['name' => $this->supervisor]);
        }

        $this->startPolling();
    }

    public function continue()
    {
        define('SIGCONT', 18);

        if (blank($this->supervisor)) {
            Artisan::call(ContinueCommand::class);
        } else {
            Artisan::call(ContinueSupervisorCommand::class, ['name' => $this->supervisor]);
        }

        $this->startPolling();
    }

    public function terminate()
    {
        define('SIGTERM', 15);

        Artisan::call(TerminateCommand::class);

        $this->startPolling();
    }

    protected function startPolling()
    {
        $this->poll = true;
        $this->pollCount = 0;

        $this->emit('updateSupervisorStatus');
    }

    protected function currentStatus()
    {
        if (blank($this->supervisor)) {
            if (!$masters = app(MasterSupervisorRepository::class)->all()) {
                return 'inactive';
            }

            return collect($masters)->every(fn ($master) : bool => $master->status === 'paused') ? 'paused' : 'running';
        }

        /** @var SupervisorRepository $supervisorRepository */
        $supervisorRepository = app(SupervisorRepository::class);

        $supervisor = $supervisorRepository->find($this->supervisor);

        if ($supervisor === null) {
            return 'inactive';
        }

        return $supervisor->status === 'paused' ? 'paused' : 'running';
    }
}
