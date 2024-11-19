<?php

use App\Http\Controllers\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('new_message', [WhatsAppController::class, 'new_message'])
    ->name('new_message')
    ->middleware(\App\Http\Middleware\TwilioRequestMiddleware::class);
