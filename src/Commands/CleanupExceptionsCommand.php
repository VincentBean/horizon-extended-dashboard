<?php

namespace VincentBean\HorizonDashboard\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use VincentBean\HorizonDashboard\Models\JobException;

class CleanupExceptionsCommand extends Command
{
    protected $signature = 'horizon-dashboard:cleanup-exceptions {--hours=}';

    protected $description = 'Delete old exception data';

    public function handle()
    {
        $to = Carbon::now()->subHours($this->option('hours'));

        $query = JobException::query()
            ->where('occured_at', '<=', $to->toDateTimeString());

        $this->info("Deleting {$query->count()} job exceptions");

        $query->delete();

        return static::SUCCESS;
    }

}
