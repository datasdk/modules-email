<?php

// resources/lang/da/email_templates.php

return [
    'subject' => 'Du er blevet inviteret som administrator',
    'html_template' => '<p>Hej {{user.first_name}},</p><p>Du er blevet inviteret til at være administrator for {{app.name}}.</p><p>Som administrator får du adgang til at administrere indstillinger, brugere og indhold på platformen.</p><p>For at acceptere din invitation og komme i gang, klik på linket nedenfor.</p><p><br>[button url="{{url}}" text="Aktiver bruger"]</p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
    'text_template' => 'Du er blevet inviteret',

];
