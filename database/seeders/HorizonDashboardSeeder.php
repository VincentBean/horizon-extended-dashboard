<?php

namespace VincentBean\HorizonDashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use VincentBean\HorizonDashboard\Actions\RetrieveQueues;
use VincentBean\HorizonDashboard\Models\JobInformation;
use VincentBean\HorizonDashboard\Models\JobStatistic;
use VincentBean\HorizonDashboard\Models\QueueStatistic;

class HorizonDashboardSeeder extends Seeder
{
    public function run()
    {
        JobInformation::factory()
            ->count(20)
            ->create();

        JobStatistic::factory()
            ->count(200)
            ->create();

        foreach (app(RetrieveQueues::class)->handle() as $queue) {
            QueueStatistic::factory()
                ->count(100)
                ->create(['queue' => $queue]);
        }

        // Data to easily aggregate
        $date = now()->subHour();

        JobStatistic::factory()->count(1000)->make()
            ->each(function (JobStatistic $statistic) use (&$date) {
                $date->addSecond();

                $statistic->queue = 'default';
                $statistic->queued_at = $date;
                $statistic->save();
            });

    }
}
