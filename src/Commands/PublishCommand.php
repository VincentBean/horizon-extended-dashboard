<?php

namespace VincentBean\HorizonDashboard\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected const TAGS = [
        'extended-horizon-dashboard-assets',
        'livewire-charts:public'
    ];

    protected $signature = 'horizon-dashboard:publish';

    protected $description = 'Publish assets';

    public function handle()
    {
        foreach (static::TAGS as $tag) {
            $this->call('vendor:publish', [
                '--tag' => $tag,
                '--force' => true,
            ]);
        }

        return static::SUCCESS;
    }

}
