<?php

namespace Modules\Email\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Email\Models\MailServer;
use Modules\Email\Http\Requests\EmailSMTPRequest;
use Illuminate\Support\Facades\Log;
use Settings;

class EmailSMTPController extends Controller
{
    /**
     * Vis formular til mailserver indstillinger
     */
    public function edit()
    {

        $config = MailServer::getMailConfig();

        return view('email::settings.mailserver', compact('config'));

    }

    /**
     * Opdater SMTP eller SendGrid indstillinger
     */
    public function update(EmailSMTPRequest $request)
    {

        $provider = $request->input('default');


        try {
          
            
            Settings::set('mail', [
                'default' => 'smtp',
                'mailers' => [
                    'smtp' => $request->input('smtp'),
                ],
                'from' => [
                    'name' => $request->input('from.name'),
                    'address' => $request->input('from.address'),
                    'reply_address' => $request->input('from.reply_address'),
                ],
            ]);

            
            return redirect()->back()->with('success', 'SMTP settings updated successfully.');

        
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()->withErrors(['error' => 'Failed to update mail settings.']);

        }


        return back()->withErrors(['default' => 'Provider ikke understøttet.']);

    }



    /**
     * Send test e-mail
     */
    public function send_test_mail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $email = $request->email;

            $emailSent = app(EmailService::class)->send([
                'to' => $email,
                'template' => 'mailserver-test',
                'params' => [
                    'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
                ],
            ]);

            if (!$emailSent || $emailSent->hasErrors()) {
                return response()->json([
                    'message' => 'E-mail is not sent',
                    'errors' => $emailSent?->errors
                ], 500);
            }

            return response("E-mail is sent successfully");

        } catch (\Exception $e) {
            Log::error("Failed to send test email: " . $e->getMessage());
            return response("Failed to send test email. Please check logs", 500);
        }
    }

}
