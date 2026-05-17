<?php

// resources/lang/en/email_templates.php

return [
    'subject' => 'You have been invited as an administrator',
    'html_template' => '<p>Hi {{user.first_name}},</p><p>You have been invited to be an administrator for {{app.name}}.</p><p>As an administrator, you will have access to manage settings, users, and content on the platform.</p><p>To accept your invitation and get started, please click the link below.</p><p><br></p><p>Best regards,</p><p>{{app.name}}</p>',
    'text_template' => 'You have been invited',
];
