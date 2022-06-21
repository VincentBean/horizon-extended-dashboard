<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VincentBean\HorizonDashboard\Models\JobInformation;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JobInformation::class)->index();

            $table->string('uuid')->index()->nullable();
            $table->string('queue');

            $table->float('runtime')->nullable();
            $table->integer('attempts')->default(0);

            $table->boolean('failed')->default(false);

            $table->string('queued_at', 16)->nullable();
            $table->string('reserved_at', 16)->nullable();
            $table->string('finished_at', 16)->nullable();

            $table->boolean('aggregated')->default(false);
            $table->integer('aggregated_job_count')->nullable();
            $table->integer('aggregated_failed_count')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_statistics');
    }
};
