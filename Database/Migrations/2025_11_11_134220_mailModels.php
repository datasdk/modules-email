<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailModels extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('mail_models')) {

            Schema::create('mail_models', function (Blueprint $table) {

                $table->increments('id');

                // Polymorphic relation
                $table->morphs('model'); // model_type + model_id

                // Reference til email
                $table->unsignedBigInteger('email_id');

                $table->timestamps();

                // Index for performance
                $table->index('email_id');

            });


        }

        
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Schema::hasTable('mail_models')) {

            Schema::dropIfExists('mail_models');

        }

    }


}
