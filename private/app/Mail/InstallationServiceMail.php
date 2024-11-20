<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstallationServiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $to, $cc = [])
    {
        $this->data = $data;
        $this->to = $to;
        if (count($cc) > 0) {
            $this->cc = $cc;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Installation & Service More Than 7 Days | ' . date('d M Y'))->view('email.installation-service-notify');
    }
}
