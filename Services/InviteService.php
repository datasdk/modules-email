<?php
namespace Modules\Email\Services;

use App\Models\User;
use Modules\Email\Services\EmailService;

class InviteService
{
    /**
     * Send an invitation email to the user.
     *
     * @param \App\Models\User $user The user to whom the invitation will be sent
     * @return bool Result of the email sending process
     */
    public function send(User $user)
    {
        // Check if the user is a moderator
        if ($user->isModerator()) {
            // Redirect URL for invited moderators
            $return_url = config("redirects.INVITED_USERS_REDIRECT_ADMIN", url('/'));
            $template = "admin-invitation"; // Template for moderator invitation
        } else {
            // Redirect URL for other users
            $return_url = config("redirects.INVITED_USERS_REDIRECT", url('/user-invitation/success'));
            $template = "user-invitation"; // Template for user invitation
        }

        // Send the invitation email
        return $this->sendInvite($user, $template, $return_url);
    }

    /**
     * Prepare and send the invitation email.
     *
     * @param \App\Models\User $user The user to send the invitation to
     * @param string $template The email template to use
     * @param string $return_url The URL to redirect the user to after invitation
     * @return array Result of the email sending process
     */
    private function sendInvite(User $user, string $template, $return_url)
    {
        // Create an access token for the user for the invitation form
        $accessToken = $user->createToken('InviteToken')->plainTextToken;
        
        // Generate the invitation URL with the token
        $url = url('/user/invitation') . '?token=' . urlencode($accessToken);
        $url .= "&return_url=" . urlencode($return_url);

        // Prepare parameters for the email template
        $userParams = collect($user)->only([
            "first_name",
            "middle_name",
            "last_name",
            "email",
            "username",
        ])->toArray();

        // Ensure templates is an array
        $templates = is_array($template) ? $template : [$template];

        $return = []; // Array to store the email sending results

        // Define recipient's email
        $to = $user->email; 

        // Send emails using the given templates
        foreach ($templates as $tpl) {
            // Send the email using the EmailService
            $return[] = app(EmailService::class)->send([
                "to" => $to,
                "template" => $tpl,
                "params" => [
                    "url" => $url, // URL with the invitation token
                    "password" => $user->getPassword(), // The user's password
                    "user" => $userParams,
                    "company" => [
                        "name" => $_SERVER["HTTP_HOST"] ?? "localhost",
                        "email" => config("mail.from.reply_address") ?? "info@example.com",
                    ]
                ],
            ]);
        }

        return $return; // Return the result of the email sending
    }
}
