<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewUserNotification;
use App\Services\StripeServices;
use App\Services\UserServices;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{

    public function __construct(protected UserServices $userServices, protected StripeServices $stripeServices)
    {
    }

    public function new_message(Request $request)
    {

        $phone = "+" .$request->post('WaId');
        $user = User::query()->where('phone', $phone)->first();

        if (!$user) {
            $user = $this->userServices->store($request->all());
        }

        if (!$user->subscribed()){
            $this->stripeServices->payment($user);
        }

    }
}
