<?php

// resources/lang/en/email_templates.php

return [
    'subject' => 'Activate your account at {{app.name}}',
    'html_template' => '<p>Hello {{user.first_name}},</p><p><br></p><p>Thank you for registering at {{app.name}}! To complete your account setup, please activate it by clicking the link below:</p><p><br></p><p>If you did not create an account, you can ignore this email.</p><p>If you have any questions, please contact us at {{company.email}}.</p><p><br></p><p>Best regards,</p><p>{{app.name}}</p>',
    'text_template' => 'Activate now',
];
