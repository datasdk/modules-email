<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailCampaigne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasTable("mail_campaigns"))
        Schema::create('mail_campaigns', function (Blueprint $table) {

            $table->increments('id');
            $table->string('slug')->unique(); 
            $table->integer('template_id');
            $table->integer('send_day');
            $table->time('send_time');
            $table->integer('sorting')->nullable();
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
        if(Schema::hasTable("mail_campaigns"))
        Schema::dropIfExists('mail_campaigns');
    }
}
