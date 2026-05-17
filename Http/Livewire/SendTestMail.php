<?php

namespace Modules\Email\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SendTestMail extends Component
{
    public $email = '';
    public $emailId = null; // <-- omdøbt fra id

    public $loading = false;
    public $msg = null;
    public $showSendTestMail = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function open($emailId = null)
    {
        $this->showSendTestMail = true;
        $this->emailId = $emailId;
        $this->emit('open', $this->email);
    }

    public function close()
    {
        $this->showSendTestMail = false;
    }

    public function send()
    {
        $this->validate();

        $this->loading = true;
        $this->msg = null;

        try {
            // Brug det nye property
            $url = route("settings.mailserver.send_test_mail");

            $response = Http::post($url, [
                'email' => $this->email,
            ]);

            if ($response->successful()) {
                $this->close();
            } else {
                $this->msg = $response->body();
            }
        } catch (\Exception $e) {
            $this->msg = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('email::livewire.send-test-mail');
    }
}
