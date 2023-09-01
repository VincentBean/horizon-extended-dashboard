<?php

use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Http\Middleware\Authenticate;
use VincentBean\HorizonDashboard\Http\Controllers\BatchController;
use VincentBean\HorizonDashboard\Http\Controllers\DashboardController;
use VincentBean\HorizonDashboard\Http\Controllers\ExceptionController;
use VincentBean\HorizonDashboard\Http\Controllers\JobController;
use VincentBean\HorizonDashboard\Http\Controllers\StatisticsController;

Route::middleware(['web', Authenticate::class])->prefix(config('horizon-dashboard.prefix'))->group(function () {

    Route::get('/', DashboardController::class)
        ->name('horizon-dashboard');

    Route::get('jobs/{type}/{queue?}', [JobController::class, 'index'])
        ->name('horizon-dashboard.job-list');

    Route::get('job/{id}', [JobController::class, 'show'])
        ->name('horizon-dashboard.job');

    Route::get('batches', [BatchController::class, 'index'])
        ->name('horizon-dashboard.batches');

    Route::get('batch/{id}', [BatchController::class, 'show'])
        ->name('horizon-dashboard.batch');

    Route::get('exceptions', [ExceptionController::class, 'index'])
        ->name('horizon-dashboard.exception-list');

    Route::get('exception/{id}', [ExceptionController::class, 'show'])
        ->name('horizon-dashboard.exception');

    Route::prefix('statistics')->group(function () {
        Route::get('/', [StatisticsController::class, 'index'])
            ->name('horizon-dashboard.statistics');

        Route::get('job/{id}', [StatisticsController::class, 'job'])
            ->name('horizon-dashboard.statistics-job');

        Route::get('queue/{queue}', [StatisticsController::class, 'queue'])
            ->name('horizon-dashboard.statistics-queue');

        Route::get('jobs', [StatisticsController::class, 'jobOverview'])
            ->name('horizon-dashboard.statistics-jobs');

        Route::get('queues', [StatisticsController::class, 'queueOverview'])
            ->name('horizon-dashboard.statistics-queues');
    });

});


