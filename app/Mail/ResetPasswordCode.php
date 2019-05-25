<?php

namespace App\Mail;

use App\Models\ResetCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordCode extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var ResetPasswordCode
     */
    private $resetPasswordCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ResetCode $resetPasswordCode)
    {
        //
        $this->resetPasswordCode = $resetPasswordCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset_code')->withCode($this->resetPasswordCode);
    }
}
