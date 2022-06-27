<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('queue_statistics', function (Blueprint $table) {
            $table->float('cpu_usage')->default(0);
            $table->float('memory_usage')->default(0);
        });
    }

    public function down()
    {
        Schema::dropColumns('queue_statistics', ['cpu_usage', 'memory_usage']);
    }
};
