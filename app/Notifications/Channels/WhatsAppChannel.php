<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function send($notifiable, Notification $notification): MessageInstance
    {
        $message = $notification->toWhatsApp($notifiable);

        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('twilio.from');

        $twilio = new Client(config('twilio.account_sid'), config('twilio.auth_token'));

        if ($message->contentSid){
            return $twilio->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => "whatsapp:" . $from,
                    'contentSid' => $message->contentSid,
                    'contentVariables' => $message->variables
                ]
            );
        }

        return $twilio->messages->create(
            'whatsapp:' . $to,
            [
              'from' =>"whatsapp:" . $from,
              'body' => $message
            ]
        );
    }
}
