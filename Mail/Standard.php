<?php

namespace Modules\Email\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Email;
use Illuminate\Support\Facades\Log;
use Throwable;

class Standard extends Mailable implements ShouldQueue
{

    use Queueable, SerializesModels;


    public function __construct(
        public Email $email,
        public array $params = []
    ) {}


    public function build(): self
    {
  
        try {

            // Konfigurer grundlæggende mailindhold
            $email = $this->to($this->email->to)
                          ->subject($this->email->subject)
                          ->view('emails.standard', [
                              'body' => $this->email->message,
                              'params' => $this->params,
                          ]);


            // Hent vedhæftede filer fra Media Library
            $attachments = $this->email->getMedia("attachments");


            foreach ($attachments as $media) {

                try {

                    $path = $media->getPath();


                    if ($path && file_exists($path)) {

                        $email->attach($path, [
                            'as'   => $media->file_name,
                            'mime' => $media->mime_type,
                        ]);

                    } else {

                        Log::warning('Vedhæftet fil eksisterer ikke eller er ugyldig', [
                            'media_id'  => $media->id ?? null,
                            'fileName'  => $media->file_name,
                            'filePath'  => $path,
                        ]);

                    }


                } catch (Throwable $e) {

                    Log::error('Fejl ved vedhæftning af fil', [
                        'error'     => $e->getMessage(),
                        'media_id'  => $media->id ?? null,
                        'fileName'  => $media->file_name,
                    ]);

                }

            }


            return $email;


        } catch (Throwable $e) {


            Log::critical('Fejl i Standard Mailable build()', [
                'error'    => $e->getMessage(),
                'email_id'=> $this->email->id,
            ]);

            // (Valgfrit) Kaste fejl videre hvis du vil afbryde hele køen
            throw $e;

        }

    }
    
}
