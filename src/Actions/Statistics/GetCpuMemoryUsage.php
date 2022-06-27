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

        $result = explode("\n", $result);

        if (count($result) != 3) {
            return [];
        }

        $result = explode(' ', $result[1]);

        if (count($result) != 4) {
            return [];
        }

        return [
            'cpu' => (float)$result[1],
            'memory' => (float)$result[3],
        ];
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