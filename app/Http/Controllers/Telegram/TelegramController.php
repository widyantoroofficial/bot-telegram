<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function example(Request $request)
    {
        $update = Telegram::getWebhookUpdate();
        $message = $update->getMessage();
        $text = $message->getText();
        $chatId = $message->getChat()->getId();

        switch (strtolower($text)) {
            case '/hallo':
                $responseText = "Ada yang bisa dibantu?";
                Telegram::sendMessage(['chat_id' => $chatId, 'text' => $responseText]);
                break;

            default:
                $responseText = "Perintah tidak dikenal.";
                Telegram::sendMessage(['chat_id' => $chatId, 'text' => $responseText]);
                break;
        }
    }
}
