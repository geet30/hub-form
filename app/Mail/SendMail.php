<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Services\Firebase as FirebaseService;
use Auth;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $firebase;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->firebase = new FirebaseService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_mail = !empty(Auth::user()->email) ? Auth::user()->email : 'admin@gmail.com' ;
        $template_link = $this->firebase->createDynamicLink('share_template?id='.$this->data['temp_id']);
        $this->data['template_link'] = $template_link;
        return $this->from($from_mail)->subject('Shared Template')
                    ->view('admin.template.share-template-email')->with('data', $this->data);
    }

}
