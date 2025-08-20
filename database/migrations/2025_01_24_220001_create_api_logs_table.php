<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLogsTable extends Migration
{
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('api_key')->nullable();
            $table->json('request_data')->nullable();
            $table->string('status')->default('success'); // success o fail
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
