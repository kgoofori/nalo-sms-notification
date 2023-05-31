<?php

namespace NotificationChannels\NaloSms;

use Illuminate\Support\Arr;

class NaloSmsMessage
{
    protected $recipient, $sender, $message;
    // Message structure here

    public function sender($sender)
    {
        $this->sender = $sender;
        return $this;
    }
    

    public function recipient($recipient)
    {
        $this->recipient = $recipient ;
        return $this;

    }

    public function message($message = '')
    {
        $this->message = $message ;
        return $this;

    }

    public function getRecipient()
    {
        return $this->recipient;

    }

    public function toArray()
    {
        return [
            "key" => config('broadcasting.connections.nalo_sms.key', null),
            "sender_id" => $this->sender ??  config('broadcasting.connections.nalo_sms.sender') ?? 'Nalo',
            "message" => $this->message,
            "msisdn" => $this->recipient,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
