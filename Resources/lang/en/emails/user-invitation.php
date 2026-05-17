<?php

// resources/lang/en/email_templates.php

return [
    'subject' => 'You have been invited to Datas',
    'html_template' => '<p>Hello {{user.first_name}},</p><p>You have been invited to create an account at {{app.name}}.</p><p>To accept the invitation and get started, please click the link below:</p><p><br></p><p>If you have any questions, please contact us at {{company.email}}.</p><p>We look forward to welcoming you! </p><p><br></p><p>Best regards,</p><p>{{app.name}}</p>',
    'text_template' => 'You have been invited',
];
