# Laravel Horizon Extended Dashboard

![Laravel Horizon Extended Dashboard](https://banners.beyondco.de/Horizon%20Extended%20Dashboard.png?theme=dark&packageManager=composer+require&packageName=vincentbean%2Fhorizon-extended-dashboard&pattern=deathStar&style=style_1&description=Alternative+Dashboard+for+Horizon&md=1&showWatermark=0&fontSize=100px&images=cube)

This is an alternative dashboard for Laravel Horizon written with Livewire.
The reason that I've created this is that the default Horizon dashboard lacks features such as controls and a queue filter.

## Features

- Detailed overview of Horizon, all data that is available is shown
- Pause and terminate controls from the dashboard for the master process and supervisors
- Batch views
- Job Views for recent, pending, completed and failed
  - Filtering on queue
- Catch all exceptions that occur on your jobs
- Detailed statistics
 

## Installation

Require this package via composer:

```shell
composer require vincentbean/horizon-extended-dashboard
```

Then publish the assets:
```shell
php artisan horizon-dashboard:publish
```

You can optionally add this to your post install or update composer script to make sure you always have the latest version published:
```json
{
  "scripts": {
    "post-update-cmd": [
      "@php artisan horizon-dashboard:publish"
    ]
  }
}
```

Finally, you should configure your scheduler to include these commands:
```php
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('horizon:snapshot')->everyFifteenMinutes();
        $schedule->command('horizon-dashboard:queue-snapshot')->everyFifteenMinutes();
        $schedule->command('horizon-dashboard:cleanup-statistics --hours=168')->daily();
        $schedule->command('horizon-dashboard:cleanup-exceptions --hours=168')->everyFifteenMinutes();
        $schedule->command('horizon-dashboard:aggregate-queue-statistics --interval=60 --keep=240')->everyFifteenMinutes();
        $schedule->command('horizon-dashboard:aggregate-job-statistics --interval=15 --keep=60')->everyFifteenMinutes();
    }
```

This package uses horizon's snapshot data and It's own to create the statistics.
To prevent your database from growing, this package includes two aggregate and cleanup commands. You can adjust the values to work best for your install.
The parameters for the aggregate commands are in minutes.

## Authentication

This package uses de same authentication middleware as Laravel Horizon. 

## Usage

You can access the dashboard by going to `/horizon-dashboard`

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
