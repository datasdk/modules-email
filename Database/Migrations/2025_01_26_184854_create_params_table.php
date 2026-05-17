<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable("mail_template_params"))
        Schema::create('mail_template_params', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id');
            $table->string('name');
            $table->string('label');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('mail_template_params'))
        Schema::dropIfExists('mail_template_params');
    }
}
