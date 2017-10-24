<?php

namespace App\Listeners;

use App\Exceptions\EmailSenderException;
use App\SentEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $message = $event->message;
        $email = new SentEmail;
        $email->to = array_keys($message->getTo())[0];
        $email->from = array_keys($message->getFrom())[0];
        $email->message_id = $message->getId();
        $email->subject = $message->getSubject();
        $email->body = $message->getBody();
        $email->save();
    }
}
