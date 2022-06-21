<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components\Charts;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class QueueJobPerMinuteChart extends Component
{
    public string $queue;

    public function mount(string $queue)
    {
        $this->queue = $queue;
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.components.charts.queue-jobsperminute-chart', ['model' => $this->getChartModel()]);
    }

    protected function getChartModel(): LineChartModel
    {
        $chartModel = LivewireCharts::lineChartModel();

        QueueStatistic::query()
            ->where('queue', $this->queue)
            ->get()
            ->each(function(QueueStatistic $statistic) use ($chartModel) {

                $chartModel->addPoint($statistic->snapshot_at->toDateTimeString(), $statistic->jobs_per_minute);

            });

        return $chartModel;
    }
}
