<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Database as DB;

class Emails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasTable("emails"))
        Schema::create('emails', function (Blueprint $table) {

            $table->id();
            $table->string('uuid');
            $table->string('to',200)->nullable();
            $table->string('subject',300)->nullable();
            $table->text('message')->nullable();
            $table->json('params')->nullable();
            $table->timestamp('sent')->nullable();
            $table->string('errors',500)->nullable(); 
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('template_id')->nullable();
            $table->timestamp('send_after')->default( DB::raw('CURRENT_TIMESTAMP') );
            $table->string('status',250)->nullable();
            $table->softDeletes();
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

        if(Schema::hasTable("emails"))
        Schema::dropIfExists('emails');

    }
}
