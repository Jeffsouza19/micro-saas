<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\NewUserNotification;

class StripeServices
{
    public function payment(User $user)
    {
        $response = $user->checkout('price_1QMpV9P4P4OVFN7M3rbD3v00', [
            'phone_number_collection' => ['enabled' => true],
            'mode' => 'subscription',
            'success_url' => 'https://wa.me/' . str_replace('+','', config('twilio.from')),
            'cancel_url' => 'https://wa.me/' . str_replace('+','', config('twilio.from')),
        ])->toArray();

        $user->notify(new NewUserNotification($user->name, str_replace("https://checkout.stripe.com/c/pay/", '', $response['url'])));

        return $response;
    }
}
