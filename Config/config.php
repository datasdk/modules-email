<?php



return [

    'name' => 'Email',

    'cron' => [
        'limitPrRequest' => 5
    ],

    "allow_duplicate_emails" => false,

    "reloadTemplateBeforeSend" => true,

    "defaultTemplateParams" => [
        "test_param" => "test"
    ],
 
    'admin' => [

    
        'navigationbar'=>[
            
            "group" => "E-mail",  
            
            "sorting" => 700,

            "link" => ['name' => 'E-mail','icon'=> 'fas fa-envelope','link' => 'emails.index', 'new_window' => false],
   
            "submenu" => [
   
                [ "icon" => "fas fa-envelope", "name" => "Templates", "link" => "templates.index", 'new_window' => false], 

                [ "icon" => "fas fa-envelope", "name" => "Mailserver", "link" => "settings.mailserver.edit", 'new_window' => false],
    
            ],
              
        ],

 

    ]

];
