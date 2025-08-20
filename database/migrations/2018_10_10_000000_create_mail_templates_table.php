<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mailable',191);
            $table->string('subject',200)->nullable();
            $table->string('html_template', 4000)->nullable();
            $table->string('text_template', 4000)->nullable();
            $table->timestamps();
        });
    }
}
