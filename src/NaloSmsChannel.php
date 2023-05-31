<?php

namespace NotificationChannels\NaloSms;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Client\RequestException;
use NotificationChannels\NaloSms\Exceptions\CouldNotSendNotification;

class NaloSmsChannel
{
    const DEFAULT_SMS_URL = 'https://sms.nalosolutions.com/smsbackend/Nal_resl/send-message/';

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \App\Channels\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {

        // Get the message from the notification class
        if (! $notifiable->routeNotificationFor('nalo-sms')) {
            throw CouldNotSendNotification::serviceRespondedWithAnError('Route notification not found.');
        }

        $message = $notification->toNaloSms($notifiable);

        if (empty($message)) {
            throw CouldNotSendNotification::serviceRespondedWithAnError('toNaloSms is not well implemented');
        }

        if(empty($message->getRecipient())){
            $message->recipient($notifiable->routeNotificationForNaloSms());
        }

        try {

            // dd($message->toArray());
            
            $response = Http::post(static::DEFAULT_SMS_URL, $message->toArray())->throw()->json();

            if(@$response['status'] != "1701"){
                throw CouldNotSendNotification::serviceRespondedWithAnError($response['message']);
            }

        } catch (RequestException $requestException) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($requestException);
        }

    }
}
