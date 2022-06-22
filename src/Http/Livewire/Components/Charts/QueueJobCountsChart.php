<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components\Charts;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class QueueJobCountsChart extends Component
{
    public string $queue;

    public function mount(string $queue)
    {
        $this->queue = $queue;
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.components.charts.queue-jobcounts-chart', ['model' => $this->getChartModel()]);
    }

    protected function getChartModel(): LineChartModel
    {
        $chartModel = LivewireCharts::multiLineChartModel();

        QueueStatistic::query()
            ->where('queue', $this->queue)
            ->get()
            ->each(function(QueueStatistic $statistic) use ($chartModel) {

                $chartModel->addSeriesPoint('Pushed', $statistic->snapshot_at->toDateTimeString(), $statistic->job_pushed_count);
                $chartModel->addSeriesPoint('Failed', $statistic->snapshot_at->toDateTimeString(), $statistic->job_failed_count);
                $chartModel->addSeriesPoint('Completed', $statistic->snapshot_at->toDateTimeString(), $statistic->job_completed_count);

            });

        return $chartModel;
    }
}
