<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration
{
    public function up()
    {

        if(!Schema::hasTable("mail_templates"))
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique(); 
            $table->string('mailable');
            $table->json('subject')->nullable();
            $table->json('html_template');
            $table->json('text_template')->nullable();
            $table->string('contract')->nullable();
            $table->json('params')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        if(Schema::hasTable("mail_templates"))
        Schema::dropIfExists('mail_templates');
    }

}
