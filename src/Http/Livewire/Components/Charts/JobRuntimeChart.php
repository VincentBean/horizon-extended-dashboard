<?php

namespace VincentBean\HorizonDashboard\Http\Livewire\Components\Charts;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;

class JobRuntimeChart extends Component
{
    public int $jobId;

    public function mount(int $jobId)
    {
        $this->jobId = $jobId;
    }

    public function render(): View
    {
        return view('horizondashboard::livewire.components.charts.job-runtime-chart', ['model' => $this->getChartModel()]);
    }

    protected function getChartModel(): LineChartModel
    {
        /** @var JobInformation $job */
        $job = JobInformation::find($this->jobId);

        /** @var Collection<JobStatistic> $statistics */
        $statistics = $job->statistics;

        $chartModel = LivewireCharts::lineChartModel()
            ->setXAxisVisible(false)
            ->setSmoothCurve();

        $statistics->each(fn(JobStatistic $statistic, $index) => $chartModel->addPoint($statistic->queued_at->toDateTimeString(), $statistic->runtime));

        return $chartModel;
    }
}
