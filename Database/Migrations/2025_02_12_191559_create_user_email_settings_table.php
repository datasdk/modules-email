<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable("user_email_settings"))
        Schema::create('user_email_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');  // Reference to users table
            $table->integer('template_id');  // Name of the template
            $table->boolean('active')->default(true);  // Active status (whether the template is active or not)
            $table->timestamps();

            // Add unique constraint to ensure each user-template combination is unique
            $table->unique(['user_id', 'template_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable("user_email_settings"))
        Schema::dropIfExists('user_email_settings');
    }
}
