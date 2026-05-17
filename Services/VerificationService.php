<?php

namespace Modules\Email\Services;

use App\Models\User;
use Modules\Email\Services\EmailService;

class VerificationService
{
    /**
     * Send the user activation email.
     *
     * @param \App\Models\User $user The user to whom the activation email will be sent
     * @return bool Result of the email sending process
     */
    public function send(User $user)
    {
        // Send activation email using a specific template
        return $this->sendActivationMail($user, "user-activation");
    }

    /**
     * Verify the user's email if not already verified.
     *
     * @param \App\Models\User $user The user whose email will be verified
     * @return \App\Models\User The updated user object
     */
    public function verify(User $user)
    {
        // If the user has not verified their email, update the 'email_verified_at' timestamp
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
            $user->save(); // Save the changes
        }

        return $user; // Return the updated user
    }

    /**
     * Send an activation email with the specified template.
     *
     * @param \App\Models\User $user The user to send the activation email to
     * @param string $template The email template to use for the activation email
     * @return bool Result of the email sending process
     */
    public function sendActivationMail(User $user, string $template)
    {
        // Create a new EmailService instance to send the email
        $emailService = app(EmailService::class);;

        // Generate an activation token for the user
        $accessToken = $user->createToken('activationToken')->plainTextToken;

        // Create the activation URL with the token
        $url = url('/user/activation') . '?token=' . urlencode($accessToken);

        // Define the redirect URL after activation
        $returnUrl = config("redirects.VERTIFY_USERS_REDIRECT", "/");

        // Append the return URL to the activation link
        $url .= "&return_url=" . urlencode($returnUrl);

        // Get the user's email
        $email = $user->email;

        // Prepare the parameters for the email
        $params = [
            "to" => $email,
            "user_id" => $user->id, // User's ID
            "template" => $template, // Template to be used
            "params" => [
                "user" => $user->toArray(), // User data
                "company" => [
                    "name" => $_SERVER["HTTP_HOST"] ?? 'company_name',
                    "email" => config('mail.from.reply_address')
                ],
                "url" => $url // Activation URL
            ]
        ];

        // Send the email using the EmailService
        return $emailService->send($params);
    }
}
