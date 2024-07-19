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
            case '/start':
                $responseText = "Selamat datang di bot!";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                $responseText = "Silahkan Masukan Password Untuk Export Database Pukesmas Prembun";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;

            default:
                $responseText = "Ulangi Password lu salah.";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
