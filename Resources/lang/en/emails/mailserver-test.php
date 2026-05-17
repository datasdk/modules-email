<?php

// resources/lang/en/email_templates.php

return [
    'subject' => 'Test mail from {{ domain }}',
    'html_template' => '<p><strong>Test mail</strong></p><p>This is a test mail sent from {{ domain }}</p><p>The SMTP server is configured correctly!</p>',
    'text_template' => "Test mail\nThis is a test mail sent from {{ domain }}\n\nThe SMTP server is configured correctly!",
];
