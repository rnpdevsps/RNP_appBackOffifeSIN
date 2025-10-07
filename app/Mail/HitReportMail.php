<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HitReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->markdown('emails.hitReport')
                    ->with('details', $this->details);
                    
        /*return $this->subject('Reporte de HIT')
                    ->view('emails.hitReport')
                    ->with('details', $this->details);*/
    }
}
