<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_information', function (Blueprint $table) {
            $table->id();
            $table->string('class')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_information');
    }
};
