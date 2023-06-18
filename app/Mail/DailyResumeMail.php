<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyResumeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attatchment;

    /**
     * Create a new message instance.

     * * @param mixed $attatchment
     * @return void
     */
    public function __construct($attatchment)
    {
        $this->attatchment = $attatchment;
    }

    public function build(){
        return $this->subject('Dagelijkse omzet overzicht')
            ->view('mail.daily_resume')
            ->attach($this->attatchment, ['as' => 'counts.xlsx']);
    }

}
