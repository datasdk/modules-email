<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailTableAttachme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable("mail_templates_attachments"))
        Schema::create('mail_templates_attachments', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('mail_templates_id');
            $table->string('url',500)->nullable();
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

        if(Schema::hasTable("mail_templates_attachments"))
        Schema::dropIfExists('mail_templates_attachments');
    }
}
