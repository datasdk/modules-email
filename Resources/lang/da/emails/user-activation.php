<?php

// resources/lang/da/email_templates.php

return [
    'subject' => 'Aktivér din konto hos {{app.name}}',
    'html_template' => '<p>Hej {{user.first_name}}</p><p><br></p><p>Tak for din registrering på {{app.name}}! For at fuldføre oprettelsen af din konto, skal du aktivere den ved at klikke på linket nedenfor:</p><p><br>[button url="{{url}}" text="Aktiver bruger"]</p><p>Hvis du ikke har oprettet en konto, kan du ignorere denne e-mail.</p><p>Har du spørgsmål? Kontakt os på {{user.email}}.</p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
    'text_template' => 'Aktiver nu',
];
