<?php

namespace Modules\Email\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Email\Services\EmailService;
use App\Models\User;

class ForgotPasswordService
{
    /**
     * Sends a password reset email to the user.
     *
     * @param array $data Data containing the email, token, and redirect URL
     * @return mixed Email sending result
     */
    public function send(array $data)
    {

        $email = $data["email"];
        $token = $data["token"] ?? Str::random(64);; // Brug det token der blev genereret tidligere
        $redirect = $data["redirect"];

        $user = User::findByEmail($email);

        if(!$user){ return false; }


        // Indsæt eller opdatér token i password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'redirect' => $redirect,
                'created_at' => now()
            ]
        );

        $emailService = app(EmailService::class);

        $userDate = collect($user)->only($user->fillable)->toArray();

     
     
        $emailResult = $emailService->send([
            "to" => $email,
            "template" => "reset-password",
            "params" => [
                "user" => $userDate,
                "url" => url('/reset-password/' . $token),
                "company" => [
                    "name" => $_SERVER["HTTP_HOST"] ?? "localhost",
                    "email" => config("mail.from.reply_address") ?? "info@example.com",
                ]
            ]
        ]);

 
        return $emailResult;
        
    }
}
