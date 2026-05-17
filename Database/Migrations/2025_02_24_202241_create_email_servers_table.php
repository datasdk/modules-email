<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailServersTable extends Migration
{
    public function up()
    {

        if(!Schema::hasTable("mail_servers"))
        Schema::create('mail_servers', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            // SMTP Configuration
            $table->string('host');
            $table->integer('port');
            $table->string('username');
            $table->string('password');
            $table->string('encryption');
            // From details
            $table->string('from_name');
            $table->string('from_address');
            $table->timestamps();
        });

    
    }

    public function down()
    {
        if(Schema::hasTable("mail_servers"))
        Schema::dropIfExists('mail_servers');
    }
}
