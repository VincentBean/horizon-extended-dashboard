<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components\Charts;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class QueueJobTypesChart extends Component
{
    protected const COLORS = [
        '#14B8A6',
        '#06B6D4',
        '#1D4ED8',
        '#4338CA',
        '#F87171',
        '#B91C1C',
        '#FB923C',
        '#7C2D12',
        '#FBBF24',
        '#D97706',
        '#A3E635',
        '#16A34A',
        '#047857',
        '#6D28D9',
        '#C026D3',
        '#DB2777'
    ];

    public string $queue;

    public function mount(string $queue)
    {
        $this->queue = $queue;
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.components.charts.queue-jobtypes-chart', ['model' => $this->getChartModel()]);
    }

    protected function getChartModel(): PieChartModel
    {
        $chartModel = LivewireCharts::pieChartModel();

        $jobCounts = [];

        QueueStatistic::query()
            ->where('queue', $this->queue)
            ->select(['jobs'])
            ->get()
            ->each(function (QueueStatistic $statistic) use (&$jobCounts) {
                foreach ($statistic->jobs as $job => $count) {

                    if (array_key_exists($job, $jobCounts)) {
                        $jobCounts[$job] += $count;
                    } else {
                        $jobCounts[$job] = $count;
                    }

                }
            });

        $index = 0;

        foreach ($jobCounts as $job => $count) {
            $chartModel->addSlice($job, $count, static::COLORS[$index % count(static::COLORS)]);
            $index++;
        }

        return $chartModel;
    }
}
