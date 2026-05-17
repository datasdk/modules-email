<?php

use Illuminate\Http\Request;


Route::group([
    'as' => 'api.email.',
    'middleware' => 'auth:api',
    'prefix' => 'email'
], function ($router) {
   
    // EMAIL TEMPLATE

    Orion::resource('templates', 'Api\EmailTemplateController');
  
    // EMAIL OUTBOX
    Orion::resource('emails', 'Api\EmailController');
   


});





