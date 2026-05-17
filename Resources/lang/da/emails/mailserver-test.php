<?php

// resources/lang/da/email_templates.php

return [
    'subject' => 'Test-mail fra {{ domain }}',
    'html_template' => '<p><strong>Testmail</strong></p><p>Dette er en testmail sendt fra {{ domain }}</p><p>SMTP-serveren er konfigureret korrekt!</p>',
    'text_template' => "Testmail\nThis is a test-mail sent from {{ domain }}\n\nThe SMTP-server is configured correctly!",
];
