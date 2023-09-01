<?php

namespace VincentBean\HorizonDashboard;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Horizon\Console\ContinueCommand;
use Laravel\Horizon\Console\ContinueSupervisorCommand;
use Laravel\Horizon\Console\PauseCommand;
use Laravel\Horizon\Console\PauseSupervisorCommand;
use Laravel\Horizon\Console\TerminateCommand;
use Laravel\Horizon\Events\JobDeleted;
use Laravel\Horizon\Events\JobFailed;
use Laravel\Horizon\Events\JobPushed;
use Laravel\Horizon\Events\JobReserved;
use Livewire\Livewire;
use VincentBean\HorizonDashboard\Commands\AggregateJobStatisticsCommand;
use VincentBean\HorizonDashboard\Commands\AggregateQueueStatisticsCommand;
use VincentBean\HorizonDashboard\Commands\CleanupExceptionsCommand;
use VincentBean\HorizonDashboard\Commands\CleanupStatisticsCommand;
use VincentBean\HorizonDashboard\Commands\PublishCommand;
use VincentBean\HorizonDashboard\Commands\QueueSnapshotCommand;
use VincentBean\HorizonDashboard\Http\Livewire\BatchDetail;
use VincentBean\HorizonDashboard\Http\Livewire\BatchList;
use VincentBean\HorizonDashboard\Http\Livewire\Cards\SupervisorsCard;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\JobRuntimeChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\QueueCpuChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\QueueJobCountsChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\QueueJobPerMinuteChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\QueueJobTypesChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Charts\QueueMemoryChart;
use VincentBean\HorizonDashboard\Http\Livewire\Components\Controls;
use VincentBean\HorizonDashboard\Http\Livewire\JobDetail;
use VincentBean\HorizonDashboard\Http\Livewire\JobList;
use VincentBean\HorizonDashboard\Http\Livewire\Components\TopBar;
use VincentBean\HorizonDashboard\Http\Livewire\Cards\WorkloadCard;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->registerCommands();
    }

    protected function registerCommands(): static
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                QueueSnapshotCommand::class,
                AggregateJobStatisticsCommand::class,
                AggregateQueueStatisticsCommand::class,
                CleanupStatisticsCommand::class,
                CleanupExceptionsCommand::class,
                PublishCommand::class
            ]);
        } else {
            // Register Horizon commands so that we can use it in a http context
            $this->commands([
                ContinueCommand::class,
                ContinueSupervisorCommand::class,
                PauseCommand::class,
                PauseSupervisorCommand::class,
                TerminateCommand::class,
            ]);
        }

        return $this;
    }

    public function boot()
    {
        $this
            ->bootConfig()
            ->bootViews()
            ->bootRoutes()
            ->bootLivewire()
            ->bootEvents()
            ->bootMigrations()
            ->bootPublish();
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/horizon-dashboard.php' => config_path('horizon-dashboard.php'),
        ]);

        return $this;
    }

    protected function bootViews(): static
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'horizondashboard');

        return $this;
    }

    protected function bootRoutes(): static
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        return $this;
    }

    protected function bootLivewire(): static
    {
        Livewire::component('horizon-dashboard.components.top-bar', TopBar::class);
        Livewire::component('horizon-dashboard.components.controls', Controls::class);

        Livewire::component('horizon-dashboard.workload-card', WorkloadCard::class);
        Livewire::component('horizon-dashboard.supervisors-card', SupervisorsCard::class);

        Livewire::component('horizon-dashboard.job-list', JobList::class);
        Livewire::component('horizon-dashboard.job-detail', JobDetail::class);

        Livewire::component('horizon-dashboard.batch-list', BatchList::class);
        Livewire::component('horizon-dashboard.batch-detail', BatchDetail::class);

        Livewire::component('horizon-dashboard.components.charts.job-runtime-chart', JobRuntimeChart::class);
        Livewire::component('horizon-dashboard.components.charts.queue-jobcounts-chart', QueueJobCountsChart::class);
        Livewire::component('horizon-dashboard.components.charts.queue-jobsperminute-chart', QueueJobPerMinuteChart::class);
        Livewire::component('horizon-dashboard.components.charts.queue-jobtypes-chart', QueueJobTypesChart::class);
        Livewire::component('horizon-dashboard.components.charts.queue-cpu-chart', QueueCpuChart::class);
        Livewire::component('horizon-dashboard.components.charts.queue-memory-chart', QueueMemoryChart::class);

        return $this;
    }

    protected function bootEvents(): static
    {
        Event::listen(JobPushed::class, Listeners\JobPushed::class);
        Event::listen(JobReserved::class, Listeners\JobReserved::class);
        Event::listen(JobDeleted::class, Listeners\JobDeleted::class);
        Event::listen(JobFailed::class, Listeners\JobFailed::class);
        Event::listen(JobExceptionOccurred::class, Listeners\JobException::class);

        return $this;
    }

    protected function bootMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        return $this;
    }

    protected function bootPublish(): static
    {
        $this->publishes([
            realpath(__DIR__ . '/../public') => public_path('vendor/extended-horizon-dashboard'),
        ], ['extended-horizon-dashboard-assets']);

        return $this;
    }
}
