<?php

namespace VincentBean\HorizonDashboard\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VincentBean\HorizonDashboard\Models\JobInformation;

class JobInformationFactory extends Factory
{
    protected $model = JobInformation::class;

    public function definition()
    {
        return [
          'class' => 'VincentBean\\HorizonDashboard\\Jobs\\TestJob' . rand(0, 1000)
        ];
    }
}
