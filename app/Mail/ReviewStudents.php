<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewStudents extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * The ReviewStudents instance.
     *
     */
    protected $student_data_received;

    /** @var array 
    * Parameters to pass to the view.
    *
    */
    public $params = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $count, $semester, $faculty, $delegate)
    {
        $this->params = [
            'name' => $name,
            'count' => $count,
            'semester' => $semester,
            'faculty' => $faculty,
            'delegate' => $delegate,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->params['semester'] . ' Undergraduate Student Research Applications')
                    ->view('emails.reviewstudents')
                    ->with( $this->params );
    }
}

