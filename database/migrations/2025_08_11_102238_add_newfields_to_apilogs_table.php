<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('api_logs', function (Blueprint $table) {
            $table->string('method')->nullable();
            $table->string('url')->nullable();
            $table->json('headers')->nullable();
            $table->json('request_body')->nullable();
            $table->integer('status_code')->nullable();
            $table->json('response_body')->nullable();
            $table->float('execution_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_logs', function (Blueprint $table) {
            //
        });
    }
};
