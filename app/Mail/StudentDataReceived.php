<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentDataReceived extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * The StudentDataReceived instance.
     *
     */
    protected $student_data_received;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $count, $semester)
    {
        $this->params = [
            'user' => $user,
            'count' => $count,
            'semester' => $semester,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com', 'Example')
                    ->view('emails.studentdatareceived')
                    ->with( $this->params );
    }
}

