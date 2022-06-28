<?php

namespace VincentBean\HorizonDashboard\Actions\Statistics;

use Laravel\Horizon\Contracts\SupervisorRepository;

class GetCpuMemoryUsage
{
    public function __construct(
        protected SupervisorRepository $supervisors
    )
    {
    }

    public function getForQueue(string $queue): array
    {
        return $this->getForPid($this->getPid($queue));
    }

    public function getForPid(int $pid): array
    {
        $result = shell_exec("ps -p $pid -o %cpu,%mem");

        if (preg_match_all('/[\d.]+/m', $result, $matches)) {
            return [
                'cpu' => $matches[0][0],
                'memory' => $matches[0][1],
            ];
        }

        return [];
    }

    protected function getPid(string $queue): ?string
    {
        $supervisors = $this->supervisors->all();

        foreach ($supervisors as $supervisor) {
            $queues = explode(',', $supervisor->options['queue']);

            if (in_array($queue, $queues)) {
                return $supervisor->pid;
            }

        }

        return null;
    }

}