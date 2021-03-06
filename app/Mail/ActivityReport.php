<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityReport extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html($this->data['message'])
                    ->from("alerts@newluckyelectricals.com.gh", "Alerts - New Lucky Electricals")
                    ->to("dev@michaelselby.me")
                    ->subject($this->data['subject'])
                    ->attachFromStorage('/reports/activity/activity-report-'.date('d-m-Y-his').'.csv');
    }
}
