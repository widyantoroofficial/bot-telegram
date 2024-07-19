<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class WebhookController extends Controller
{
    public function example()
    {
        $response = Telegram::setWebhook(['url' => 'https://bot.latihanserver.my.id/example']);

        return $response ? 'Webhook Berhasil Diatur' : 'Failed to set webhook';
    }
}
