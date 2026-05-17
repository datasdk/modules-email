<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'emails',
    "middleware" => ["web","auth","role:admin|editor|analyzer|guest"],
], function () {



    Route::resource('templates', 'EmailTemplateController');
  
    // EMAIL OUTBOX
    Route::resource('emails', 'EmailController');


    // -------------------------
    // SMTP SETTINGS ROUTES
    // -------------------------
    Route::get('settings/mailserver', 'EmailSMTPController@edit')->name('settings.mailserver.edit');

    Route::patch('settings/mailserver', 'EmailSMTPController@update')->name('settings.mailserver.update');

    Route::post('settings/mailserver/send_test_mail', 'EmailSMTPController@sendTestMail')->name('settings.mailserver.send_test_mail');

 

});
