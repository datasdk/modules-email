<?php

// resources/lang/da/email_templates.php

return [
    'subject' => 'Du er blevet inviteret til Datas',
    'html_template' => '<p>Hej {{user.first_name}},</p><p>Du er blevet inviteret til at oprette en konto på {{app.name}}.</p><p>For at acceptere invitationen og komme i gang, klik på linket nedenfor:</p><p><br>[button url="{{url}}" text="Aktiver bruger"]</p><p>Har du spørgsmål? Kontakt os på {{company.email}}.</p><p>Vi glæder os til at byde dig velkommen! </p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
    'text_template' => 'Du er blevet inviteret',
];
