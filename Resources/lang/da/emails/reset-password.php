<?php

// resources/lang/da/email_templates.php

return [
    'subject' => 'Anmodning om gendannelse af kodeord',
    'html_template' => '<p>Kære {{user.first_name}},</p><p>Vi har modtaget en anmodning om at nulstille dit kodeord hos {{app.name}}. </p><p>For at fortsætte med gendannelsen, klik venligst på linket nedenfor:</p><p><br></p><p>Hvis du ikke har anmodet om at nulstille dit kodeord, bedes du ignorere denne e-mail.</p><p><br></p><p>Hvis du har brug for yderligere assistance, er du velkommen til at kontakte os på {{company.email}}</p><p><br>[button url="{{url}}" text="Gendan password"]</p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
    'text_template' => 'Gendan kodeord',
];
