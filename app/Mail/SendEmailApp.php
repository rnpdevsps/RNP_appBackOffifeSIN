<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailApp extends Mailable
{
    use Queueable, SerializesModels;

    protected $emailSubject;
    protected $body;
    protected $base64Image;

    public function __construct($subject, $body, $base64Image)
    {
        $this->emailSubject = $subject;
        $this->body = $body;
        $this->base64Image = $base64Image;
    }

    public function build()
    {
        $email = $this->markdown('emails.send_email_app')
                    ->with(['emailValue' => $this->body])
                    ->subject('Asunto - ' . $this->emailSubject);

        if ($this->base64Image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->base64Image));
            $tempPath = storage_path('app/temp_img_' . uniqid() . '.png');

            file_put_contents($tempPath, $imageData);

            register_shutdown_function(function () use ($tempPath) {
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
            });

            $email->attach($tempPath, [
                'as' => 'imagen.png',
                'mime' => 'image/png',
            ]);
        }

        return $email;
    }
}
