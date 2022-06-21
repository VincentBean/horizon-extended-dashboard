<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queue_statistics', function (Blueprint $table) {
            $table->id();

            $table->string('queue')->index();

            $table->integer('job_pushed_count');
            $table->integer('job_completed_count');
            $table->integer('job_fail_count');
            $table->integer('jobs_per_minute');

            $table->integer('throughput');
            $table->integer('wait');
            $table->float('average_runtime');

            $table->json('jobs');

            $table->dateTime('snapshot_at');

            $table->boolean('aggregated')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('queue_statistics');
    }
};
