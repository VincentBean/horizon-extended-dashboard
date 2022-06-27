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

    public function boot()
    {
        $this->defineSignals();
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
        if (blank($this->supervisor)) {
            Artisan::call(PauseCommand::class);
        } else {
            Artisan::call(PauseSupervisorCommand::class, ['name' => $this->supervisor]);
        }

        $this->startPolling();
    }

    public function continue()
    {
        if (blank($this->supervisor)) {
            Artisan::call(ContinueCommand::class);
        } else {
            Artisan::call(ContinueSupervisorCommand::class, ['name' => $this->supervisor]);
        }

        $this->startPolling();
    }

    public function terminate()
    {
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

            return collect($masters)->every(fn($master): bool => $master->status === 'paused') ? 'paused' : 'running';
        }

        /** @var SupervisorRepository $supervisorRepository */
        $supervisorRepository = app(SupervisorRepository::class);

        $supervisor = $supervisorRepository->find($this->supervisor);

        if ($supervisor === null) {
            return 'inactive';
        }

        return $supervisor->status === 'paused' ? 'paused' : 'running';
    }

    protected function defineSignals()
    {
        $signals = [
            'SIGTERM' => 15,
            'SIGCONT' => 18,
            'SIGUSR2 ' => 12
        ];

        foreach ($signals as $signal => $code) {
            if (!defined($signal)) {
                define($signal, $code);
            }
        }

    }
}
