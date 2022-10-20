<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Booking_email extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details )
    {
        $this->details = $details; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->details->action === 'Decline' || $this->details->action === 'Cancel'){ 
            return $this->subject($this->details->subject)->view('email.booking_decline_cancel_email');
        }else if($this->details->action === 'Edit'){  
            return $this->subject($this->details->subject)->view('email.booking_edit_email');
        }else{ 
            return $this->subject($this->details->subject)->view('email.booking_email');
        }
    }
}
