<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
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
        $order = $this->data;
        return $this->from([
            'address' => env('MAIL_FROM_ADDRESS'),
            'name' => env('MAIL_FROM_NAME'),
        ])->view('mail.order_mail',compact('order'))->subject('Order from demo ecommerce');
    }
}