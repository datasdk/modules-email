<?php

// resources/lang/en/email_templates.php

return [
    'subject' => 'Password Reset Request',
    'html_template' => '<p>Dear {{user.first_name}},</p><p>We have received a request to reset your password at {{app.name}}.</p><p>To proceed with the reset, please click the link below:</p><p><br></p><p>If you did not request a password reset, please ignore this email.</p><p><br></p><p>If you need further assistance, feel free to contact us at {{company.email}}</p><p><br></p><p>Best regards,</p><p>{{app.name}}</p>',
    'text_template' => 'Reset password',
];
