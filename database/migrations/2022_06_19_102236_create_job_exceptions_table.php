<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VincentBean\HorizonDashboard\Models\JobInformation;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JobInformation::class)->index();
            $table->dateTime('occured_at');

            $table->string('uuid')->index();
            $table->integer('attempt');
            $table->float('runtime');

            $table->string('exception');
            $table->string('message');
            $table->json('trace');
            $table->json('data')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_exceptions');
    }
};
